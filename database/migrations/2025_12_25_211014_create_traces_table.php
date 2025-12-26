<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('traces', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 64)->index()->nullable();
            $table->string('span_id', 64)->index()->nullable();
            $table->string('action')->index();
            $table->string('entity_type')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('route')->nullable();
            $table->string('method', 10)->nullable();
            $table->ipAddress('ip')->nullable();
            $table->integer('status_code')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('occurred_at')->useCurrent()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traces');
    }
};
