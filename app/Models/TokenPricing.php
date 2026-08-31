<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenPricing extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_pool_usd',
        'total_pool_coins',
        'coin_rate_usd',
    ];

    public function getCoinRateUsdAttribute($value)
    {
        if ((float) $this->total_pool_coins > 0 && (float) $this->total_pool_usd > 0) {
            return (float) ($this->total_pool_usd / $this->total_pool_coins);
        }
        return (float) ($value ?? 0.0200);
    }
}