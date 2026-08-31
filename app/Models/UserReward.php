<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'target_usd',
        'power_leg_req',
        'other_legs_req',
        'reward_coins',
        'claimed_at',
    ];

    protected $casts = [
        'target_usd' => 'decimal:4',
        'power_leg_req' => 'decimal:4',
        'other_legs_req' => 'decimal:4',
        'reward_coins' => 'decimal:4',
        'claimed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
