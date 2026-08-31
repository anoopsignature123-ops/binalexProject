<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\TokenPricing;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's initial clean fresh database.
     */
    public function run(): void
    {
        // 1. System Roles
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'user']);

        // 2. Token Valuation & Pool Pricing ($300,000 USD Valuation / 1.5 Crore BINALEX Coins Pool = $0.0200 USD/Coin)
        TokenPricing::updateOrCreate(
            ['id' => 1],
            [
                'total_pool_usd' => 300000.00,
                'total_pool_coins' => 15000000.00,
                'coin_rate_usd' => 0.02000000,
            ]
        );

        // 3. Call Sub-seeders
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
            StakePackageSeeder::class,
        ]);
    }
}