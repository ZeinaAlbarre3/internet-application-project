<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    public function handle(): int
    {
        $backupDir = storage_path('app/backups');
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $file = $backupDir . DIRECTORY_SEPARATOR . 'backup_' . now()->format('Y_m_d_His') . '.sql';

        $db = config('database.connections.mysql');

        $mysqldump = '"C:\xampp\mysql\bin\mysqldump.exe"';

        $user = escapeshellarg($db['username']);
        $host = escapeshellarg($db['host'] ?? '127.0.0.1');
        $port = escapeshellarg((string)($db['port'] ?? 3306));
        $database = escapeshellarg($db['database']);
        $resultFile = escapeshellarg($file);

        $passPart = '';
        if (!empty($db['password'])) {
            $passPart = ' --password=' . escapeshellarg($db['password']);
        }

        $command = "{$mysqldump} --user={$user}{$passPart} --host={$host} --port={$port} {$database} --result-file={$resultFile}";

        exec($command, $out, $code);

        if ($code !== 0 || !file_exists($file) || filesize($file) === 0) {
            $this->error('Backup failed.');
            return Command::FAILURE;
        }

        $this->info('Backup created: ' . basename($file));
        return Command::SUCCESS;
    }
}
