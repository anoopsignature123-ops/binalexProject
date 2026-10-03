<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deposits', function (Blueprint $table): void {
            if (!Schema::hasColumn('deposits', 'wallet_address')) {
                $table->string('wallet_address')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('deposits', 'gateway_url')) {
                $table->text('gateway_url')->nullable()->after('wallet_address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table): void {
            $table->dropColumn(['wallet_address', 'gateway_url']);
        });
    }
};
