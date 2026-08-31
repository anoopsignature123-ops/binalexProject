<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (!Schema::hasColumn('users', 'usdt_balance')) {
                $table->decimal('usdt_balance', 16, 4)->default(0)->after('status');
            }
            if (!Schema::hasColumn('users', 'coin_balance')) {
                $table->decimal('coin_balance', 16, 4)->default(0)->after('usdt_balance');
            }
            if (!Schema::hasColumn('users', 'working_coin_balance')) {
                $table->decimal('working_coin_balance', 16, 4)->default(0)->after('coin_balance');
            }
            if (!Schema::hasColumn('users', 'usdt_wallet_address')) {
                $table->string('usdt_wallet_address')->nullable()->after('working_coin_balance');
            }
            if (!Schema::hasColumn('users', 'total_staked_usd')) {
                $table->decimal('total_staked_usd', 16, 4)->default(0)->after('usdt_wallet_address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['usdt_balance', 'coin_balance', 'working_coin_balance', 'usdt_wallet_address', 'total_staked_usd']);
        });
    }
};
