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
        Schema::create('token_pricings', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_pool_usd', 15, 2)->default(1000000.00); // e.g. $1,000,000
            $table->decimal('total_pool_coins', 20, 2)->default(1000000000.00); // e.g. 1,000,000,000 Coins
            $table->decimal('coin_rate_usd', 10, 4)->default(0.0010); // Automatically calculated 1 Coin value in USD or 1 USD = X Coins
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_pricings');
    }
};