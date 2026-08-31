<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserReferral;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $admin = User::updateOrCreate(
            ['email' => 'admin@binalex.com'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Binalex Admin',
                'mobile' => '9999999999',
                'referral_code' => 'BT1000000',
                'status' => 'active',
                'password' => Hash::make('Admin@123'),
                'usdt_balance' => 00.00,
                'coin_balance' => 00.00,
                'working_coin_balance' => 00.00,
            ]
        );

        UserReferral::updateOrCreate(
            ['user_id' => $admin->id],
            ['sponsor_id' => null, 'sponsor_referral_code' => null]
        );
    }
}