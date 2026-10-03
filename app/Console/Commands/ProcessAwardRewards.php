<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\AwardRewardService;
use Illuminate\Console\Command;

class ProcessAwardRewards extends Command
{
    protected $signature = 'binalex:process-awards';
    protected $description = 'Scan active users and grant eligible Award & Rank Rewards based on 60/40 leg volumes';

    public function handle(): int
    {
        $this->info("Starting Binalex Award & Reward Scanning Process...");

        $activeUsers = User::where('status', 'active')->get();
        $awardService = new AwardRewardService();
        $count = 0;

        foreach ($activeUsers as $user) {
            $awardService->checkAndAwardRewards($user);
            $count++;
        }

        $this->info("Successfully scanned awards and rank rewards for {$count} active users.");
        return Command::SUCCESS;
    }
}
