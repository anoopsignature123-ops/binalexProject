<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('withdrawals', function (Blueprint $table): void {
            if (!Schema::hasColumn('withdrawals', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('withdrawals', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('approved_at');
            }
            if (!Schema::hasColumn('withdrawals', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('paid_at');
            }
            if (!Schema::hasColumn('withdrawals', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('rejection_reason');
            }
        });

        // Expand status enum to support 'pending', 'approved', 'completed', 'rejected', 'paid'
        try {
            DB::statement("ALTER TABLE withdrawals MODIFY COLUMN status ENUM('pending', 'approved', 'completed', 'rejected', 'paid') NOT NULL DEFAULT 'pending'");
        } catch (\Exception $e) {
            // Ignore if already altered or not supported
        }
    }

    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table): void {
            $table->dropColumn(['approved_at', 'paid_at', 'rejection_reason', 'approved_by']);
        });
    }
};
