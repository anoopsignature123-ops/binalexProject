@extends('admin.layouts.app')

@section('title', 'View User Details - ' . $user->name)

@section('content')
<style>
    .user-banner-card {
        background: linear-gradient(135deg, rgba(245, 184, 46, 0.15) 0%, rgba(16, 22, 34, 0.95) 100%);
        border: 1px solid var(--border-gold);
        border-radius: 20px;
        padding: 1.8rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .metric-small-card {
        background: rgba(8, 10, 15, 0.75);
        border: 1px solid var(--border-gold);
        border-radius: 14px;
        padding: 1.2rem;
    }
</style>

<!-- Top Navigation & Action Buttons -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <a href="{{ route('admin.users.index') }}" style="color: var(--gold-light); text-decoration: none; font-size: 0.88rem; font-weight: 700;">
            <i class="fa-solid fa-arrow-left"></i> Back to All Users List
        </a>
        <h2 style="color: var(--gold-light); margin-top: 0.4rem;"><i class="fa-solid fa-id-card"></i> Member Profile Overview</h2>
    </div>

    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <form action="{{ route('admin.users.impersonate', $user->id) }}" method="POST" target="_blank" style="margin: 0;">
            @csrf
            <button type="submit" class="btn-gold" style="font-size: 0.85rem;"><i class="fa-solid fa-right-to-bracket"></i> Login in New Tab</button>
        </form>

        <a href="{{ route('admin.users.tree', $user->id) }}" class="btn-gold" style="font-size: 0.85rem; background: rgba(255,255,255,0.1); color: #fff;"><i class="fa-solid fa-sitemap"></i> Tree View</a>
        <a href="{{ route('admin.users.directs', $user->id) }}" class="btn-gold" style="font-size: 0.85rem; background: rgba(255,255,255,0.1); color: #fff;"><i class="fa-solid fa-users"></i> Directs ({{ $user->directs_count ?? 0 }})</a>
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-gold" style="font-size: 0.85rem; background: rgba(245,184,46,0.2); color: var(--gold-light);"><i class="fa-solid fa-pen-to-square"></i> Edit Profile</a>
    </div>
</div>

<!-- User Main Header Card -->
<div class="user-banner-card">
    <div style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
        <div class="user-profile-avatar" style="width: 70px; height: 70px; font-size: 1.8rem; border-width: 2px;">
            {{ strtoupper(substr($user->name ?? $user->email, 0, 1)) }}
        </div>

        <div style="flex-grow: 1;">
            <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 4px;">
                <h2 style="color: #fff; font-size: 1.6rem; font-weight: 800;">{{ $user->name }}</h2>
                <span class="badge-status badge-{{ $user->status }}" style="font-size: 0.8rem; padding: 4px 12px;">{{ strtoupper($user->status) }}</span>
            </div>

            <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; color: var(--text-muted); font-size: 0.9rem; margin-top: 6px;">
                <div>
                    Self ID: <strong style="color: var(--gold-light);">{{ $user->referral_code }}</strong>
                    <i class="fa-solid fa-copy copy-icon-btn" onclick="copyToClipboard('{{ $user->referral_code }}', 'Self ID')"></i>
                </div>
                <div><i class="fa-solid fa-envelope" style="color: var(--gold-light);"></i> {{ $user->email }}</div>
                <div><i class="fa-solid fa-phone" style="color: var(--gold-light);"></i> {{ $user->mobile ?? 'N/A' }}</div>
            </div>

            <div style="display: flex; gap: 1.8rem; flex-wrap: wrap; color: var(--text-muted); font-size: 0.82rem; margin-top: 10px; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.08);">
                <div>Registration Date & Time: <strong style="color: #fff;">{{ $user->created_at->format('M d, Y h:i A') }}</strong></div>
                <div>Activation Date & Time: 
                    @if($user->first_activation_date)
                        <strong style="color: #34d399;">{{ $user->first_activation_date->format('M d, Y h:i A') }}</strong>
                    @else
                        <span class="badge-status badge-inactive" style="font-size: 0.7rem;">NOT ACTIVATED</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Financial Balances Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.2rem; margin-bottom: 2rem;">
    <div class="metric-small-card">
        <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Deposit Wallet Balance</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: #34d399; margin-top: 4px;">${{ number_format($user->usdt_balance, 2) }} <span style="font-size: 0.8rem; color: var(--text-muted);">USDT</span></div>
    </div>

    <div class="metric-small-card">
        <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">ROI Wallet Balance</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: var(--gold-light); margin-top: 4px;">{{ number_format($user->coin_balance, 2) }} <span style="font-size: 0.8rem; color: var(--text-muted);">Coins</span></div>
    </div>

    <div class="metric-small-card">
        <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Earning Wallet Balance</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: var(--gold-light); margin-top: 4px;">{{ number_format($user->working_coin_balance, 2) }} <span style="font-size: 0.8rem; color: var(--text-muted);">Coins</span></div>
    </div>

    <div class="metric-small-card">
        <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Total Staked Investment</div>
        <div style="font-size: 1.6rem; font-weight: 800; color: #38bdf8; margin-top: 4px;">${{ number_format($user->total_staked_usd, 2) }} <span style="font-size: 0.8rem; color: var(--text-muted);">USD</span></div>
    </div>
</div>

<!-- Downline Team & Leg Volume Breakdown -->
<div class="card" style="margin-bottom: 2rem;">
    <h3 style="color: var(--gold-light); margin-bottom: 1rem;"><i class="fa-solid fa-network-wired"></i> Sponsor & Downline Network Performance</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; font-size: 0.9rem;">
        <div style="background: rgba(8,10,15,0.7); padding: 1rem; border-radius: 12px;">
            <div style="font-size: 0.78rem; color: var(--text-muted);">Sponsor Details</div>
            <div style="font-weight: 700; color: #fff; margin-top: 4px;">{{ $user->sponsor ? $user->sponsor->name : 'No Sponsor (Root)' }}</div>
            @if($user->sponsor)
                <div style="font-size: 0.8rem; color: var(--gold-light);">{{ $user->sponsor->referral_code }}</div>
            @endif
        </div>

        <div style="background: rgba(8,10,15,0.7); padding: 1rem; border-radius: 12px;">
            <div style="font-size: 0.78rem; color: var(--text-muted);">Power Leg Volume</div>
            <div style="font-weight: 800; color: var(--gold-light); font-size: 1.2rem; margin-top: 4px;">${{ number_format($volumes['power_leg'], 2) }} USD</div>
        </div>

        <div style="background: rgba(8,10,15,0.7); padding: 1rem; border-radius: 12px;">
            <div style="font-size: 0.78rem; color: var(--text-muted);">Other Legs Volume</div>
            <div style="font-weight: 800; color: var(--gold-light); font-size: 1.2rem; margin-top: 4px;">${{ number_format($volumes['other_legs'], 2) }} USD</div>
        </div>

        <div style="background: rgba(8,10,15,0.7); padding: 1rem; border-radius: 12px;">
            <div style="font-size: 0.78rem; color: var(--text-muted);">Total Downline Team Business</div>
            <div style="font-weight: 800; color: #34d399; font-size: 1.2rem; margin-top: 4px;">${{ number_format($volumes['total_volume'], 2) }} USD</div>
        </div>
    </div>
</div>

<!-- Active Staking Packages -->
<div class="card" style="margin-bottom: 2rem;">
    <h3 style="color: var(--gold-light); margin-bottom: 1rem;"><i class="fa-solid fa-box-archive"></i> Staking Packages History ({{ $user->stakes->count() }})</h3>
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Package</th>
                    <th>Amount USD</th>
                    <th>Staked Coins</th>
                    <th>Daily Payout</th>
                    <th>Completed Days</th>
                    <th>Earned Yield</th>
                    <th>Status</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($user->stakes as $stk)
                    <tr>
                        <td><strong style="color: var(--gold-light);">{{ $stk->package_name }}</strong></td>
                        <td style="color: #34d399; font-weight: 700;">${{ number_format($stk->amount_usd, 2) }} USD</td>
                        <td>{{ number_format($stk->coins_staked, 2) }} Coins</td>
                        <td>{{ number_format($stk->daily_payout_coins, 2) }} Coins/day</td>
                        <td>{{ $stk->days_completed }} / {{ $stk->duration_days }} Days</td>
                        <td style="color: var(--gold-light); font-weight: 700;">{{ number_format($stk->earned_coins, 2) }} Coins</td>
                        <td><span class="badge-status badge-{{ $stk->status }}">{{ strtoupper($stk->status) }}</span></td>
                        <td>{{ $stk->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-muted);">No staking package activations found for this user.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Income Ledger -->
<div class="card">
    <h3 style="color: var(--gold-light); margin-bottom: 1rem;"><i class="fa-solid fa-receipt"></i> Recent Income Transactions</h3>
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Income Type</th>
                    <th>Amount Coins</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @forelse($user->incomes->take(10) as $inc)
                    <tr>
                        <td>{{ $inc->created_at->format('M d, Y h:i A') }}</td>
                        <td>
                            <span class="badge-status badge-approved" style="font-size: 0.72rem;">
                                {{ strtoupper(str_replace('_', ' ', $inc->type)) }}
                            </span>
                        </td>
                        <td style="color: var(--gold-light); font-weight: 800;">+{{ number_format($inc->amount_coins, 2) }} Coins</td>
                        <td style="font-size: 0.85rem; color: var(--text-muted);">{{ $inc->description }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted);">No income transactions generated yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
