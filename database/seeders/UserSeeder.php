<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserReferral;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $userRole = Role::firstOrCreate(['name' => 'user']);

        $rootUser = User::updateOrCreate(
            ['email' => 'user@binalex.com'],
            [
                'role_id' => $userRole->id,
                'name' => 'Demo Root User',
                'mobile' => '9876543210',
                'referral_code' => 'BT1000001',
                'status' => 'inactive',
                'password' => Hash::make('User@123'),
                'usdt_balance' => 00.00,
                'coin_balance' => 00.00,
                'working_coin_balance' => 00.00,
            ]
        );

        UserReferral::updateOrCreate(
            ['user_id' => $rootUser->id],
            ['sponsor_id' => null, 'sponsor_referral_code' => null]
        );
    }
}