<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coins_amount',
        'conversion_rate',
        'gross_usd',
        'deduction_percent',
        'deduction_usd',
        'net_usd',
        'wallet_address',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'coins_amount' => 'decimal:4',
        'conversion_rate' => 'decimal:4',
        'gross_usd' => 'decimal:4',
        'deduction_percent' => 'decimal:2',
        'deduction_usd' => 'decimal:4',
        'net_usd' => 'decimal:4',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
