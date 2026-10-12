<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 1. Daily Staking ROI Yield Payout (Runs every day at Midnight 00:00)
Schedule::command('binalex:process-daily-staking')->dailyAt('00:00')->withoutOverlapping();

// 2. Award & Rank Reward Volume Scanning (Runs every day at 00:05)
Schedule::command('binalex:process-awards')->dailyAt('00:05')->withoutOverlapping();

// 3. Token Pool & Capital Metrics Sync (Runs every day at 00:10)
Schedule::command('binalex:sync-token-pool')->dailyAt('00:10')->withoutOverlapping();