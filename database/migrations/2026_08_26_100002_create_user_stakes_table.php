<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_stakes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('package_code', ['A', 'B', 'C']);
            $table->string('package_name');
            $table->decimal('amount_usd', 16, 4);
            $table->decimal('coins_staked', 16, 4);
            $table->integer('duration_days')->default(300);
            $table->decimal('multiplier', 8, 2);
            $table->decimal('total_yield_coins', 16, 4);
            $table->decimal('daily_payout_coins', 16, 4);
            $table->decimal('earned_coins', 16, 4)->default(0);
            $table->integer('days_completed')->default(0);
            $table->enum('status', ['active', 'completed'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_stakes');
    }
};
