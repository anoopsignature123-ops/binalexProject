<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount_usd',
        'payment_method',
        'wallet_address',
        'gateway_url',
        'transaction_hash',
        'status',
        'admin_note',
        'paid_at',
    ];

    protected $casts = [
        'amount_usd' => 'decimal:4',
        'paid_at'    => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
