<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class RecalculateTeamVolumes extends Command
{
    protected $signature = 'binalex:recalculate-volumes {user_id?}';
    protected $description = 'Recalculate Power Leg and Other Legs 60:40 team volume for a specific user or all users';

    public function handle(): int
    {
        $userId = $this->argument('user_id');

        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("User ID {$userId} not found.");
                return Command::FAILURE;
            }

            $volumes = $user->calculateLegVolumes();
            $this->info("User #{$user->id} ({$user->name} / {$user->referral_code}):");
            $this->info(" - Power Leg Volume: $" . number_format($volumes['power_leg'], 2));
            $this->info(" - Other Legs Volume: $" . number_format($volumes['other_legs'], 2));
            $this->info(" - Total Team Volume: $" . number_format($volumes['total_team_volume'], 2));
        } else {
            $this->info("Recalculating leg volumes for all active users...");
            $activeUsers = User::where('status', 'active')->get();
            $bar = $this->output->createProgressBar(count($activeUsers));
            $bar->start();

            foreach ($activeUsers as $user) {
                $user->calculateLegVolumes();
                $bar->advance();
            }

            $bar->finish();
            $this->info("\nSuccessfully recalculated team volumes for all active users.");
        }

        return Command::SUCCESS;
    }
}
