<?php

namespace App\Services;

use App\Models\Income;
use App\Models\TokenPricing;
use App\Models\User;
use App\Models\UserStake;
use Exception;
use Illuminate\Support\Facades\DB;

class StakingService
{
    public static function getPackages(): array
    {
        $pricing = TokenPricing::first();
        $rate = $pricing ? (float) $pricing->coin_rate_usd : 0.0200;
        if ($rate <= 0) $rate = 0.0200;

        return [
            'A' => [
                'code' => 'A',
                'name' => '$2 Stake Plan',
                'coins_staked' => 10.00,
                'price_usd' => 2.00,
                'duration_days' => 300,
                'multiplier' => 2.00,
                'total_yield_coins' => 20.00,
                'daily_payout_coins' => 0.06,
                'direct_percent' => 10.00,
                'direct_coin_reward' => 1.00,
            ],
            'B' => [
                'code' => 'B',
                'name' => '$4 Stake Plan',
                'coins_staked' => 20.00,
                'price_usd' => 4.00,
                'duration_days' => 300,
                'multiplier' => 2.50,
                'total_yield_coins' => 50.00,
                'daily_payout_coins' => 0.16,
                'direct_percent' => 17.50,
                'direct_coin_reward' => 3.50,
            ],
            'C' => [
                'code' => 'C',
                'name' => '$6 Stake Plan',
                'coins_staked' => 30.00,
                'price_usd' => 6.00,
                'duration_days' => 300,
                'multiplier' => 3.00,
                'total_yield_coins' => 90.00,
                'daily_payout_coins' => 0.30,
                'direct_percent' => 30.00,
                'direct_coin_reward' => 9.00,
            ],
        ];
    }

    public static function getLevelConfigs(): array
    {
        return [
            1 => ['percent' => 7.0, 'req_directs' => 0],
            2 => ['percent' => 6.0, 'req_directs' => 2],
            3 => ['percent' => 5.0, 'req_directs' => 3],
            4 => ['percent' => 4.0, 'req_directs' => 4],
            5 => ['percent' => 3.0, 'req_directs' => 5],
            6 => ['percent' => 2.0, 'req_directs' => 6],
            7 => ['percent' => 1.0, 'req_directs' => 7],
            8 => ['percent' => 1.0, 'req_directs' => 8],
            9 => ['percent' => 0.5, 'req_directs' => 9],
            10 => ['percent' => 0.5, 'req_directs' => 10],
        ];
    }

    public function purchaseStake(User $user, string $packageCode): UserStake
    {
        return $this->activatePackage($user, $packageCode);
    }

    public function purchaseCustomStake(User $user, float $amountUsd): UserStake
    {
        if ($amountUsd <= 0) {
            throw new Exception("Please enter a valid investment USD amount.");
        }

        $pricing = TokenPricing::first();
        $rate = $pricing ? (float) $pricing->coin_rate_usd : 0.0200;
        if ($rate <= 0) $rate = 0.0200;

        $coinsStaked = round($amountUsd / $rate, 2);

        // Determine multiplier, direct bonus percent, and package code dynamically based on investment tier
        if ($amountUsd < 4.00) {
            $code = 'A';
            $multiplier = 2.00;
            $directPercent = 10.00;
        } elseif ($amountUsd < 6.00) {
            $code = 'B';
            $multiplier = 2.50;
            $directPercent = 17.50;
        } else {
            $code = 'C';
            $multiplier = 3.00;
            $directPercent = 30.00;
        }

        $totalYieldCoins = round($coinsStaked * $multiplier, 2);
        $dailyPayoutCoins = round(($coinsStaked * 0.01), 2);
        if ($dailyPayoutCoins < 0.01) $dailyPayoutCoins = round($coinsStaked / 300, 4);

        $directCoinReward = round(($coinsStaked * $directPercent) / 100, 2);

        $pkg = [
            'code' => $code,
            'name' => 'Stake $' . number_format($amountUsd, 2),
            'price_usd' => $amountUsd,
            'coins_staked' => $coinsStaked,
            'duration_days' => 300,
            'multiplier' => $multiplier,
            'total_yield_coins' => $totalYieldCoins,
            'daily_payout_coins' => $dailyPayoutCoins,
            'direct_percent' => $directPercent,
            'direct_coin_reward' => $directCoinReward,
        ];

        if ((float) $user->usdt_balance < $amountUsd && (float) $user->coin_balance < $coinsStaked) {
            throw new Exception("Insufficient balance. Required: $" . number_format($amountUsd, 2) . " USDT (or " . number_format($coinsStaked, 2) . " Coins). Please deposit USDT first.");
        }

        return DB::transaction(function () use ($user, $pkg, $pricing, $amountUsd, $coinsStaked) {
            // Deduct USDT or Coin balance & update staked USD
            if ((float) $user->usdt_balance >= $amountUsd) {
                $user->usdt_balance -= $amountUsd;
            } else {
                $user->coin_balance -= $coinsStaked;
            }

            $user->total_staked_usd += $amountUsd;
            $user->status = 'active';
            $user->save();

            // Update Token Pricing & Supply Pool (+USD raised, -Coins from pool)
            if ($pricing) {
                $pricing->total_pool_usd += $amountUsd;
                if ($pricing->total_pool_coins >= $coinsStaked) {
                    $pricing->total_pool_coins -= $coinsStaked;
                }
                $pricing->save();
            }

            // Create UserStake record
            $stake = UserStake::create([
                'user_id' => $user->id,
                'package_code' => $pkg['code'],
                'package_name' => $pkg['name'],
                'amount_usd' => $pkg['price_usd'],
                'coins_staked' => $pkg['coins_staked'],
                'duration_days' => $pkg['duration_days'],
                'multiplier' => $pkg['multiplier'],
                'total_yield_coins' => $pkg['total_yield_coins'],
                'daily_payout_coins' => $pkg['daily_payout_coins'],
                'earned_coins' => 0,
                'days_completed' => 0,
                'status' => 'active',
            ]);

            // Distribute Direct Referral Income & Level Income into Earning Wallet
            $this->distributeDirectAndLevelIncome($user, $stake, $pkg);

            // Check Award & Rewards for Upline Tree
            $awardService = new AwardRewardService();
            $current = $user->sponsor;
            while ($current) {
                if ($current->status === 'active') {
                    $awardService->checkAndAwardRewards($current);
                }
                $current = $current->sponsor;
            }

            return $stake;
        });
    }

    public function activatePackage(User $user, string $packageCode): UserStake
    {
        $packages = self::getPackages();
        if (!isset($packages[$packageCode])) {
            throw new Exception("Invalid staking package selected.");
        }

        $pkg = $packages[$packageCode];

        if ((float) $user->usdt_balance < (float) $pkg['price_usd'] && (float) $user->coin_balance < (float) $pkg['coins_staked']) {
            throw new Exception("Insufficient balance to activate " . $pkg['name'] . ". Required: " . $pkg['coins_staked'] . " Coins (or $" . $pkg['price_usd'] . " USDT).");
        }

        return DB::transaction(function () use ($user, $pkg) {
            // Deduct USDT or Coin balance & update staked USD
            if ((float) $user->usdt_balance >= (float) $pkg['price_usd']) {
                $user->usdt_balance -= $pkg['price_usd'];
            } else {
                $user->coin_balance -= $pkg['coins_staked'];
            }

            $user->total_staked_usd += $pkg['price_usd'];
            
            // STRICT RULE: Account status activates ONLY upon package purchase!
            $user->status = 'active';
            $user->save();

            // Update Token Pricing & Supply Pool (+USD raised, -Coins from pool)
            $pricing = TokenPricing::first();
            if ($pricing) {
                $pricing->total_pool_usd += (float) $pkg['price_usd'];
                if ($pricing->total_pool_coins >= (float) $pkg['coins_staked']) {
                    $pricing->total_pool_coins -= (float) $pkg['coins_staked'];
                }
                $pricing->save();
            }

            // Create UserStake record
            $stake = UserStake::create([
                'user_id' => $user->id,
                'package_code' => $pkg['code'],
                'package_name' => $pkg['name'],
                'amount_usd' => $pkg['price_usd'],
                'coins_staked' => $pkg['coins_staked'],
                'duration_days' => $pkg['duration_days'],
                'multiplier' => $pkg['multiplier'],
                'total_yield_coins' => $pkg['total_yield_coins'],
                'daily_payout_coins' => $pkg['daily_payout_coins'],
                'earned_coins' => 0,
                'days_completed' => 0,
                'status' => 'active',
            ]);

            // Distribute Direct Referral Income & Level Income into Earning Wallet
            $this->distributeDirectAndLevelIncome($user, $stake, $pkg);

            // Check Award & Rewards for Upline Tree
            $awardService = new AwardRewardService();
            $current = $user->sponsor;
            while ($current) {
                if ($current->status === 'active') {
                    $awardService->checkAndAwardRewards($current);
                }
                $current = $current->sponsor;
            }

            return $stake;
        });
    }

    protected function distributeDirectAndLevelIncome(User $staker, UserStake $stake, array $pkg): void
    {
        $sponsor = $staker->sponsor;

        // 1. Direct Income -> Earning Wallet (ONLY IF SPONSOR IS ACTIVE!)
        if ($sponsor && $sponsor->status === 'active') {
            $directRewardCoins = (float) $pkg['direct_coin_reward'];

            $sponsor->working_coin_balance += $directRewardCoins;
            $sponsor->save();

            Income::create([
                'user_id' => $sponsor->id,
                'from_user_id' => $staker->id,
                'stake_id' => $stake->id,
                'type' => 'direct_income',
                'amount_coins' => $directRewardCoins,
                'level' => 1,
                'description' => "Direct Referral Commission (" . $pkg['direct_percent'] . "%) credited from member " . $staker->name . " (" . $staker->referral_code . ") for activating " . $pkg['name'] . " (" . number_format($directRewardCoins, 2) . " Coins)",
            ]);
        }

        // 2. Level Income (10 Levels) -> Earning Wallet (ONLY IF UPLINE IS ACTIVE!)
        $levelConfigs = self::getLevelConfigs();
        $currentUpline = $staker->sponsor;
        $level = 1;

        while ($currentUpline && $level <= 10) {
            // STRICT RULE: Upline must be ACTIVE to earn Level Income!
            if ($currentUpline->status === 'active') {
                $config = $levelConfigs[$level];
                $activeDirects = $currentUpline->activeDirectsCount();

                // Check if upline satisfies level direct requirements
                if ($activeDirects >= $config['req_directs']) {
                    $levelPercent = (float) $config['percent'];
                    $levelRewardCoins = ((float) $pkg['coins_staked'] * $levelPercent) / 100.0;

                    if ($levelRewardCoins > 0) {
                        $currentUpline->working_coin_balance += $levelRewardCoins;
                        $currentUpline->save();

                        Income::create([
                            'user_id' => $currentUpline->id,
                            'from_user_id' => $staker->id,
                            'stake_id' => $stake->id,
                            'type' => 'level_income',
                            'amount_coins' => $levelRewardCoins,
                            'level' => $level,
                            'description' => "Level " . $level . " Generation Bonus (" . $levelPercent . "%) credited from team member " . $staker->name . " (" . $staker->referral_code . ") on " . $pkg['name'] . " (" . number_format($levelRewardCoins, 2) . " Coins)",
                        ]);
                    }
                }
            }

            $currentUpline = $currentUpline->sponsor;
            $level++;
        }
    }

    public function processDailyRoi(int $days = 1, ?string $customDate = null): int
    {
        $days = max(1, $days);
        $totalProcessedIncomes = 0;

        for ($dayIndex = 0; $dayIndex < $days; $dayIndex++) {
            $currentDate = $customDate 
                ? \Carbon\Carbon::parse($customDate)->addDays($dayIndex) 
                : null;

            $activeStakes = UserStake::where('status', 'active')
                ->whereHas('user', fn($q) => $q->where('status', 'active'))
                ->get();

            foreach ($activeStakes as $stake) {
                if ($stake->earned_coins >= $stake->total_yield_coins || $stake->days_completed >= $stake->duration_days) {
                    $stake->status = 'completed';
                    $stake->save();
                    continue;
                }

                $payout = (float) $stake->daily_payout_coins;
                $remaining = (float) $stake->total_yield_coins - (float) $stake->earned_coins;
                $payout = min($payout, $remaining);

                if ($payout > 0) {
                    DB::transaction(function () use ($stake, $payout, $currentDate) {
                        $user = $stake->user;
                        $user->coin_balance += $payout; // Daily ROI goes to ROI Wallet
                        $user->save();

                        $stake->earned_coins += $payout;
                        $stake->days_completed += 1;

                        if ($stake->earned_coins >= $stake->total_yield_coins || $stake->days_completed >= $stake->duration_days) {
                            $stake->status = 'completed';
                        }
                        $stake->save();

                        $incomeData = [
                            'user_id' => $user->id,
                            'from_user_id' => $user->id,
                            'stake_id' => $stake->id,
                            'type' => 'daily_stake',
                            'amount_coins' => $payout,
                            'level' => 0,
                            'description' => "Daily 1% Staking Yield credited for " . $stake->package_name . " ($" . number_format($stake->amount_usd, 2) . " USD) - Day " . $stake->days_completed . " of " . $stake->duration_days . " (" . number_format($payout, 2) . " Coins)" . ($currentDate ? " [Backdated: " . $currentDate->format('Y-m-d') . "]" : ""),
                        ];

                        if ($currentDate) {
                            $incomeData['created_at'] = $currentDate;
                            $incomeData['updated_at'] = $currentDate;
                        }

                        Income::create($incomeData);
                    });

                    $totalProcessedIncomes++;
                }
            }
        }

        return $totalProcessedIncomes;
    }
}
