<?php

namespace App\Services;

use App\Models\Income;
use App\Models\User;
use App\Models\UserReward;
use Illuminate\Support\Facades\DB;

class AwardRewardService
{
    public static function getMilestones(): array
    {
        return [
            ['rank' => 'Rank 1', 'target' => 50, 'power' => 30, 'other' => 20, 'reward' => 10],
            ['rank' => 'Rank 2', 'target' => 150, 'power' => 90, 'other' => 60, 'reward' => 30],
            ['rank' => 'Rank 3', 'target' => 400, 'power' => 240, 'other' => 160, 'reward' => 60],
            ['rank' => 'Rank 4', 'target' => 1000, 'power' => 600, 'other' => 400, 'reward' => 100],
            ['rank' => 'Rank 5', 'target' => 1700, 'power' => 1020, 'other' => 680, 'reward' => 200],
            ['rank' => 'Rank 6', 'target' => 3000, 'power' => 1800, 'other' => 1200, 'reward' => 500],
            ['rank' => 'Rank 7', 'target' => 7000, 'power' => 4200, 'other' => 2800, 'reward' => 1000],
            ['rank' => 'Rank 8', 'target' => 15000, 'power' => 9000, 'other' => 6000, 'reward' => 2000],
        ];
    }

    public function checkAndAwardRewards(User $user): void
    {
        // STRICT RULE: Only ACTIVE accounts qualify for rank award rewards!
        if ($user->status !== 'active') {
            return;
        }

        $volumes = $user->calculateLegVolumes();
        $powerLeg = $volumes['power_leg'];
        $otherLegs = $volumes['other_legs'];

        $milestones = self::getMilestones();

        foreach ($milestones as $milestone) {
            if ($powerLeg >= $milestone['power'] && $otherLegs >= $milestone['other']) {
                $alreadyClaimed = UserReward::where('user_id', $user->id)
                    ->where('target_usd', $milestone['target'])
                    ->exists();

                if (!$alreadyClaimed) {
                    DB::transaction(function () use ($user, $milestone) {
                        UserReward::create([
                            'user_id' => $user->id,
                            'target_usd' => $milestone['target'],
                            'power_leg_req' => $milestone['power'],
                            'other_legs_req' => $milestone['other'],
                            'reward_coins' => $milestone['reward'],
                        ]);

                        $user->working_coin_balance += $milestone['reward'];
                        $user->save();

                        Income::create([
                            'user_id' => $user->id,
                            'type' => 'award_reward',
                            'amount_coins' => $milestone['reward'],
                            'description' => "Award & Rank Reward Bonus (" . $milestone['rank'] . ") credited for reaching $" . number_format($milestone['target']) . " USD Total Team Volume (Power: $" . number_format($milestone['power']) . " / Other: $" . number_format($milestone['other']) . ") - " . number_format($milestone['reward']) . " Coins",
                        ]);
                    });
                }
            }
        }
    }
}
