<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'mobile',
        'referral_code',
        'sponsor_id',
        'status',
        'password',
        'usdt_balance',
        'coin_balance',
        'working_coin_balance',
        'usdt_wallet_address',
        'total_staked_usd',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'usdt_balance' => 'decimal:4',
        'coin_balance' => 'decimal:4',
        'working_coin_balance' => 'decimal:4',
        'total_staked_usd' => 'decimal:4',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(self::class, 'sponsor_id');
    }

    public function directs(): HasMany
    {
        return $this->hasMany(self::class, 'sponsor_id');
    }

    public function referral(): HasOne
    {
        return $this->hasOne(UserReferral::class);
    }

    public function stakes(): HasMany
    {
        return $this->hasMany(UserStake::class);
    }

    public function getFirstActivationDateAttribute()
    {
        $firstStake = $this->stakes()->oldest()->first();
        return $firstStake ? $firstStake->created_at : null;
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function incomes(): HasMany
    {
        return $this->hasMany(Income::class);
    }

    public function rewards(): HasMany
    {
        return $this->hasMany(UserReward::class);
    }

    public function activeDirectsCount(): int
    {
        return $this->directs()
            ->where(function ($q) {
                $q->where('total_staked_usd', '>', 0)
                  ->orWhereHas('stakes', function ($sq) {
                      $sq->where('status', 'active');
                  });
            })->count();
    }

    public function getSubtreeVolume(User $user): float
    {
        $ownStaked = (float) $user->total_staked_usd;
        $directsVolume = 0;
        foreach ($user->directs as $direct) {
            $directsVolume += $this->getSubtreeVolume($direct);
        }
        return $ownStaked + $directsVolume;
    }

    public function getSubtreeTeamStats(User $user): array
    {
        $active = 0;
        $inactive = 0;

        foreach ($user->directs as $direct) {
            if ($direct->status === 'active' || (float)$direct->total_staked_usd > 0) {
                $active++;
            } else {
                $inactive++;
            }
            $subStats = $this->getSubtreeTeamStats($direct);
            $active += $subStats['active'];
            $inactive += $subStats['inactive'];
        }

        return [
            'active' => $active,
            'inactive' => $inactive,
            'total' => $active + $inactive,
        ];
    }

    public function calculateLegVolumes(): array
    {
        $legVolumes = [];
        foreach ($this->directs as $direct) {
            $volume = $this->getSubtreeVolume($direct);
            $legVolumes[] = [
                'direct_id' => $direct->id,
                'name' => $direct->name ?? $direct->email,
                'volume' => $volume,
            ];
        }

        usort($legVolumes, fn($a, $b) => $b['volume'] <=> $a['volume']);

        $powerLeg = isset($legVolumes[0]) ? $legVolumes[0]['volume'] : 0.0;
        $otherLegs = 0.0;

        for ($i = 1; $i < count($legVolumes); $i++) {
            $otherLegs += $legVolumes[$i]['volume'];
        }

        return [
            'power_leg' => $powerLeg,
            'other_legs' => $otherLegs,
            'total_volume' => $powerLeg + $otherLegs,
            'total_team_volume' => $powerLeg + $otherLegs,
            'legs' => $legVolumes,
        ];
    }

    public function isAncestorOf(int $childId): bool
    {
        if ($this->id === $childId) return true;

        $child = self::find($childId);
        while ($child && $child->sponsor_id) {
            if ($child->sponsor_id === $this->id) {
                return true;
            }
            $child = self::find($child->sponsor_id);
        }
        return false;
    }

    public function isAdmin(): bool
    {
        return $this->role && $this->role->name === 'admin';
    }
}
