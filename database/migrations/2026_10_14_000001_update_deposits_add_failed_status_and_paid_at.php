<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Extend status enum to include 'failed' (gateway expired/not paid)
        // MySQL requires ALTER TABLE MODIFY for enum changes
        DB::statement("ALTER TABLE deposits MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'failed') NOT NULL DEFAULT 'pending'");

        // Add paid_at timestamp for tracking when deposit was confirmed
        Schema::table('deposits', function (Blueprint $table): void {
            if (!Schema::hasColumn('deposits', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table): void {
            if (Schema::hasColumn('deposits', 'paid_at')) {
                $table->dropColumn('paid_at');
            }
        });

        // Revert enum (failed rows would need to be cleaned first in real rollback)
        DB::statement("ALTER TABLE deposits MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending'");
    }
};
