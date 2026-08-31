<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_rewards', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('target_usd', 16, 4);
            $table->decimal('power_leg_req', 16, 4);
            $table->decimal('other_legs_req', 16, 4);
            $table->decimal('reward_coins', 16, 4);
            $table->timestamp('claimed_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_rewards');
    }
};
