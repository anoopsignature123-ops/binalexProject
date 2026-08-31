<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('coins_amount', 16, 4);
            $table->decimal('conversion_rate', 16, 4)->default(0.10);
            $table->decimal('gross_usd', 16, 4);
            $table->decimal('deduction_percent', 8, 2)->default(10.00);
            $table->decimal('deduction_usd', 16, 4);
            $table->decimal('net_usd', 16, 4);
            $table->string('wallet_address');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
