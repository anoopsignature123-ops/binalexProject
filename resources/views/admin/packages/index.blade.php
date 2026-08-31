@extends('admin.layouts.app')

@section('title', 'Staking Packages Management')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-box-archive"></i> Staking Packages Overview</h2>
        <p style="color: var(--text-muted);">Inspect available BINALEX growth packages, multipliers, and system active stakes.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <a href="{{ route('admin.reports.packages') }}" class="btn-gold"><i class="fa-solid fa-list-check"></i> View User Stakes History</a>
    </div>
</div>

<!-- Stat Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
    <div class="card" style="margin-bottom: 0; background: linear-gradient(145deg, rgba(245, 184, 46, 0.15) 0%, rgba(16, 22, 34, 0.95) 100%);">
        <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Total Active Stakes</div>
        <div style="font-size: 2rem; font-weight: 800; color: var(--gold-light); margin: 0.4rem 0;">
            {{ number_format($activeStakesCount) }} <span style="font-size: 0.9rem; color: var(--text-muted);">/ {{ number_format($totalStakesCount) }} Total</span>
        </div>
        <div style="font-size: 0.8rem; color: var(--text-muted);">Active investment packages in system.</div>
    </div>

    <div class="card" style="margin-bottom: 0;">
        <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Total System Staked Capital</div>
        <div style="font-size: 1.9rem; font-weight: 800; color: #34d399; margin: 0.4rem 0;">
            ${{ number_format($totalStakedVolumeUSD, 2) }} <span style="font-size: 1rem;">USD</span>
        </div>
        <div style="font-size: 0.8rem; color: var(--text-muted);">Cumulative staked USD across all users.</div>
    </div>
</div>

<!-- Packages List Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
    @foreach($packages as $code => $pkg)
        <div class="card" style="position: relative; border-color: var(--border-gold); background: rgba(18, 24, 38, 0.95);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <span class="badge-status badge-active" style="background: rgba(245, 184, 46, 0.2); color: var(--gold-light); font-size: 0.85rem;">
                    PACKAGE {{ $pkg['code'] }}
                </span>
                <span style="font-size: 1.4rem; font-weight: 800; color: #34d399;">${{ number_format($pkg['price_usd'], 2) }} USD</span>
            </div>

            <h3 style="color: #fff; font-size: 1.3rem; margin-bottom: 0.5rem;">{{ $pkg['name'] }}</h3>

            <div style="background: rgba(8, 10, 15, 0.6); padding: 1rem; border-radius: 12px; margin-bottom: 1rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.88rem;">
                    <span style="color: var(--text-muted);">Coins Staked:</span>
                    <strong style="color: var(--gold-light);">{{ number_format($pkg['coins_staked'], 2) }} Coins</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.88rem;">
                    <span style="color: var(--text-muted);">Duration:</span>
                    <strong>{{ $pkg['duration_days'] }} Days</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.88rem;">
                    <span style="color: var(--text-muted);">Yield Multiplier:</span>
                    <strong style="color: #34d399;">{{ $pkg['multiplier'] }}x Growth</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.88rem;">
                    <span style="color: var(--text-muted);">Total Yield:</span>
                    <strong style="color: var(--gold-light);">{{ number_format($pkg['total_yield_coins'], 2) }} Coins</strong>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.88rem;">
                    <span style="color: var(--text-muted);">Direct Bonus:</span>
                    <strong style="color: #38bdf8;">{{ $pkg['direct_percent'] }}% ({{ number_format($pkg['direct_coin_reward'], 2) }} Coins)</strong>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.8rem; color: var(--text-muted);"><i class="fa-solid fa-circle-check" style="color: #34d399;"></i> Active Plan</span>
                <a href="{{ route('admin.reports.packages') }}?status=active" class="btn-gold" style="padding: 0.5rem 1rem; font-size: 0.8rem;"><i class="fa-solid fa-eye"></i> View Active Stakes</a>
            </div>
        </div>
    @endforeach
</div>
@endsection
