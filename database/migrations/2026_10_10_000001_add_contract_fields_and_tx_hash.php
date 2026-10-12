<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('withdrawals', 'tx_hash')) {
            Schema::table('withdrawals', function (Blueprint $table): void {
                $table->string('tx_hash')->nullable()->after('wallet_address');
            });
        }

        if (!Schema::hasColumn('token_pricings', 'withdrawal_contract_address')) {
            Schema::table('token_pricings', function (Blueprint $table): void {
                $table->string('withdrawal_contract_address')->nullable()->default('0xd69F8442343sdsaasd58CeE7381b2D9272a5dB27fa3bB138');
                $table->string('usdt_contract_address')->nullable()->default('0x55d398326f99059ff775485246999027b3197955');
                $table->string('network_rpc_url')->nullable()->default('https://bsc-dataseed.binance.org/');
                $table->integer('chain_id')->default(56);
            });
        }
    }

    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table): void {
            $table->dropColumn('tx_hash');
        });

        Schema::table('token_pricings', function (Blueprint $table): void {
            $table->dropColumn(['withdrawal_contract_address', 'usdt_contract_address', 'network_rpc_url', 'chain_id']);
        });
    }
};
