<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_PAID = 'paid';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'user_id',
        'coins_amount',
        'conversion_rate',
        'gross_usd',
        'deduction_percent',
        'deduction_usd',
        'net_usd',
        'wallet_address',
        'tx_hash',
        'status',
        'admin_note',
        'rejection_reason',
        'approved_at',
        'paid_at',
        'approved_by',
    ];

    protected $casts = [
        'coins_amount' => 'decimal:4',
        'conversion_rate' => 'decimal:4',
        'gross_usd' => 'decimal:4',
        'deduction_percent' => 'decimal:2',
        'deduction_usd' => 'decimal:4',
        'net_usd' => 'decimal:4',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isPaid(): bool
    {
        return in_array($this->status, [self::STATUS_PAID, self::STATUS_COMPLETED], true);
    }

    public function isCompleted(): bool
    {
        return $this->isPaid();
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    // Accessors for funEarn view & logic compatibility
    public function getAmountAttribute()
    {
        return (float) ($this->attributes['gross_usd'] ?? ($this->coins_amount * ($this->conversion_rate ?? 0.1)));
    }

    public function getPayableAmountAttribute()
    {
        return (float) ($this->attributes['net_usd'] ?? 0);
    }

    public function getChargeAttribute()
    {
        return (float) ($this->attributes['deduction_usd'] ?? 0);
    }

    public function getWithdrawNoAttribute()
    {
        return 'WTH-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getBep20UsdtAddressAttribute()
    {
        return $this->wallet_address;
    }

    public function getTransactionHashAttribute()
    {
        return $this->tx_hash;
    }

    public function getTransactionRefAttribute()
    {
        return $this->withdraw_no;
    }
}
