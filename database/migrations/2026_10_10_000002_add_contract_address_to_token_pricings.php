<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('token_pricings', 'contract_address')) {
            Schema::table('token_pricings', function (Blueprint $table): void {
                $table->string('contract_address')->nullable()->default('0xd69F8458CeE7381b2D9272a5dB27fa39699bB138');
            });
        }
    }

    public function down(): void
    {
        Schema::table('token_pricings', function (Blueprint $table): void {
            $table->dropColumn('contract_address');
        });
    }
};
