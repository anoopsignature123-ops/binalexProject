<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProcessAllCrons extends Command
{
    protected $signature = 'binalex:process-all';
    protected $description = 'Run all daily Binalex system cron jobs in sequence (ROI Payouts + Rank Rewards Scan)';

    public function handle(): int
    {
        $this->info("=================================================");
        $this->info("   BINALEX ECOSYSTEM DAILY CRON PROCESSOR");
        $this->info("=================================================");

        // 1. Process Daily Staking ROI
        $this->info("\n[1/2] Processing Daily Staking ROI Payouts...");
        $this->call('binalex:process-daily-staking');

        // 2. Process Rank & Award Rewards Scanning
        $this->info("\n[2/2] Processing Rank & Award Rewards Scan...");
        $this->call('binalex:process-awards');

        $this->info("\n=================================================");
        $this->info("   ALL DAILY CRON JOBS EXECUTED SUCCESSFULLY!");
        $this->info("=================================================");

        return Command::SUCCESS;
    }
}
