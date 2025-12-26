<?php

namespace Database\Seeders;

use App\Domains\Auth\Models\User;
use App\Domains\Complaint\Enum\ComplaintStatusEnum;
use App\Domains\Complaint\Models\Complaint;
use App\Domains\Complaint\Models\ComplaintReply;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $complaintsCount = 1000;
        $maxRepliesPerComplaint = 5;

        $customers = User::query()->whereHas('roles', fn($q) => $q->where('name', 'customer'))->pluck('id')->all();
        $staff     = User::query()->whereHas('roles', fn($q) => $q->whereIn('name', ['staff', 'admin']))->pluck('id')->all();


        if (empty($customers)) {
            $this->command?->warn('No customers found. Please seed users first.');
            return;
        }

        DB::transaction(function () use ($faker, $complaintsCount, $maxRepliesPerComplaint, $customers, $staff) {

            for ($i = 1; $i <= $complaintsCount; $i++) {

                $userId = $faker->randomElement($customers);

                $complaint = Complaint::query()->create([
                    'reference_number'     => 'CMP-' . now()->format('Y') . '-' . str_pad((string)$i, 6, '0', STR_PAD_LEFT),
                    'user_id'              => $userId,
                    'title'                => $faker->sentence(6),
                    'description'          => $faker->paragraph(3),
                    'is_read'              => $faker->boolean(40),
                    'status'               => $faker->randomElement(array_map(fn($c) => $c->value, ComplaintStatusEnum::cases())),
                    'assigned_to'        => null,
                    'version'            => 1,
                ]);

                $repliesCount = $faker->numberBetween(0, $maxRepliesPerComplaint);

                for ($r = 1; $r <= $repliesCount; $r++) {
                    $isFromStaff = !empty($staff) ? $faker->boolean(60) : false;

                    $replyUserId = $isFromStaff && !empty($staff)
                        ? $faker->randomElement($staff)
                        : $faker->randomElement($customers);

                    ComplaintReply::query()->create([
                        'complaint_id'   => $complaint->id,
                        'user_id'        => $replyUserId,
                        'reply'        => $faker->sentence(12),
                        'is_from_staff'  => $isFromStaff,
                        'created_at'     => now()->subDays($faker->numberBetween(0, 60))->subMinutes($faker->numberBetween(0, 1440)),
                        'updated_at'     => now(),
                    ]);
                }
            }
        });

    }
}
