<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_user_id',
        'stake_id',
        'type',
        'amount_coins',
        'level',
        'description',
    ];

    protected $casts = [
        'amount_coins' => 'decimal:4',
        'level' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function stake(): BelongsTo
    {
        return $this->belongsTo(UserStake::class, 'stake_id');
    }
}
