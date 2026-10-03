<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 1. Daily Staking Return Payout (Runs every day at Midnight 00:00)
Schedule::command('binalex:process-daily-staking')->dailyAt('00:00')->withoutOverlapping();

// 2. Award & Rank Reward Volume Check (Runs every day at 00:05)
Schedule::command('binalex:process-awards')->dailyAt('00:05')->withoutOverlapping();

// 3. Prune Failed Queue Jobs Daily
// Schedule::command('queue:prune-failed --hours=48')->dailyAt('01:00');