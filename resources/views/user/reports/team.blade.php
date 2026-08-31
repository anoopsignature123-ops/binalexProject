@extends('user.layouts.app')

@section('title', 'Team Structure')

@section('content')
<h2 style="color: var(--gold-light); margin-bottom: 0.5rem;"><i class="fa-solid fa-sitemap"></i> Referral Team & Level Breakdown</h2>
<p style="color: var(--text-muted); margin-bottom: 2rem;">Monitor your direct downlines, 10-level counts, and Leg Volume breakdown.</p>

<!-- Leg Volume Breakdown -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card">
        <div style="font-size: 0.85rem; color: var(--text-muted);">POWER LEG (STRONGEST)</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: var(--gold-light); margin-top: 0.5rem;">${{ number_format($volumes['power_leg'], 2) }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.3rem;">60% Max Countable for Rewards</div>
    </div>
    <div class="card">
        <div style="font-size: 0.85rem; color: var(--text-muted);">OTHER LEGS COMBINED</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: var(--gold-light); margin-top: 0.5rem;">${{ number_format($volumes['other_legs'], 2) }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.3rem;">40% Countable for Rewards</div>
    </div>
    <div class="card">
        <div style="font-size: 0.85rem; color: var(--text-muted);">TOTAL TEAM STAKED VOLUME</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: var(--text-main); margin-top: 0.5rem;">${{ number_format($volumes['total_volume'], 2) }}</div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.3rem;">Sum of All Downlines</div>
    </div>
</div>

<!-- 10 Level Summary Table -->
<div class="card" style="margin-bottom: 2rem;">
    <h3 style="color: var(--gold-light); margin-bottom: 1rem;"><i class="fa-solid fa-list-ol"></i> 10-Level Downline & Unlocking Summary</h3>
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Level</th>
                    <th>Commission %</th>
                    <th>Required Directs</th>
                    <th>Your Active Directs</th>
                    <th>Members Count</th>
                    <th>Level Status</th>
                </tr>
            </thead>
            <tbody>
                @php $activeDirects = $user->activeDirectsCount(); @endphp
                @foreach($levelConfigs as $lvl => $cfg)
                    @php
                        $unlocked = ($activeDirects >= $cfg['req_directs']);
                    @endphp
                    <tr>
                        <td><strong style="color: var(--gold-light);">Level {{ $lvl }}</strong></td>
                        <td>{{ $cfg['percent'] }}%</td>
                        <td>{{ $cfg['req_directs'] }} Directs</td>
                        <td>{{ $activeDirects }} Directs</td>
                        <td>{{ $levelCounts[$lvl] ?? 0 }} Members</td>
                        <td>
                            @if($unlocked)
                                <span class="badge-status badge-approved">UNLOCKED</span>
                            @else
                                <span class="badge-status badge-rejected">LOCKED (Need {{ $cfg['req_directs'] }} Directs)</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Direct Downlines Listing -->
<div class="card">
    <h3 style="color: var(--gold-light); margin-bottom: 1rem;"><i class="fa-solid fa-users"></i> Direct Referrals List</h3>
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Member Name</th>
                    <th>Email / Code</th>
                    <th>Joined Date</th>
                    <th>Total Staked USD</th>
                    <th>Subtree Volume</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($directs as $d)
                    <tr>
                        <td><strong style="color: var(--text-main);">{{ $d->name ?? 'User' }}</strong></td>
                        <td>{{ $d->email }} <br><span style="font-size: 0.8rem; color: var(--gold-light);">({{ $d->referral_code }})</span></td>
                        <td>{{ $d->created_at->format('M d, Y') }}</td>
                        <td>${{ number_format($d->total_staked_usd, 2) }}</td>
                        <td style="color: var(--gold-light); font-weight: 800;">${{ number_format($user->getSubtreeVolume($d), 2) }}</td>
                        <td>
                            @if($d->status === 'active')
                                <span class="badge-status badge-active">ACTIVE</span>
                            @else
                                <span class="badge-status badge-pending">INACTIVE</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted);">No direct referrals yet. Share your referral link to invite members!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1rem;">
        {{ $directs->links() }}
    </div>
</div>
@endsection
