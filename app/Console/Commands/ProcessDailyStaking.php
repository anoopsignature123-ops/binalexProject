<?php

namespace App\Console\Commands;

use App\Models\Income;
use App\Models\UserStake;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessDailyStaking extends Command
{
    protected $signature = 'binalex:process-daily-staking {--days=1 : Number of days ROI payout to credit} {--date= : Optional backdate YYYY-MM-DD}';
    protected $description = 'Process daily coin payouts for active 300-day stakes in Binalex Ecosystem (Supports backdating)';

    public function handle(): int
    {
        $days = (int) ($this->option('days') ?? 1);
        $date = $this->option('date');

        $this->info("Starting Binalex Daily Staking Payout Process (Days: {$days}" . ($date ? ", Backdate: {$date}" : "") . ")...");

        $count = (new \App\Services\StakingService())->processDailyRoi($days, $date);

        $this->info("Successfully processed {$count} daily ROI payout record(s) across {$days} day(s).");
        return Command::SUCCESS;
    }
}