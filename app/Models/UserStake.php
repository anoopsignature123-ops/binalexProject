<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserStake extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_code',
        'package_name',
        'amount_usd',
        'coins_staked',
        'duration_days',
        'multiplier',
        'total_yield_coins',
        'daily_payout_coins',
        'earned_coins',
        'days_completed',
        'status',
    ];

    protected $casts = [
        'amount_usd' => 'decimal:4',
        'coins_staked' => 'decimal:4',
        'multiplier' => 'decimal:2',
        'total_yield_coins' => 'decimal:4',
        'daily_payout_coins' => 'decimal:4',
        'earned_coins' => 'decimal:4',
        'days_completed' => 'integer',
        'duration_days' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function incomes(): HasMany
    {
        return $this->hasMany(Income::class, 'stake_id');
    }
}
