@extends('user.layouts.app')

@section('title', 'Award & Reward Income Report')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-trophy"></i> Award & Rank Rewards</h2>
        <p style="color: var(--text-muted);">Track your rank achievements and milestone coin bonuses in real-time.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <div style="background: rgba(245, 184, 46, 0.15); border: 1px solid var(--border-gold); padding: 0.6rem 1.2rem; border-radius: 12px; color: var(--gold-light); font-weight: 800; font-size: 1rem;">
            Total Reward Earned: {{ number_format($totalRewardCommission, 2) }} Coins
        </div>
    </div>
</div>

<!-- Power Leg & Other Legs Live Business Summary -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.2rem; margin-bottom: 2rem;">
    <div class="card" style="border-left: 4px solid var(--gold-primary); background: rgba(14, 18, 28, 0.95);">
        <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800;">POWER LEG VOLUME (60%)</span>
        <h3 style="color: var(--gold-light); font-size: 1.8rem; margin: 0.3rem 0;">${{ number_format($legVolumes['power_leg'], 2) }} USD</h3>
        <span style="font-size: 0.85rem; color: #34d399;"><i class="fa-solid fa-shield-halved"></i> Main Strongest Leg Volume</span>
    </div>

    <div class="card" style="border-left: 4px solid #34d399; background: rgba(14, 18, 28, 0.95);">
        <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800;">OTHER LEGS COMBINED (40%)</span>
        <h3 style="color: #34d399; font-size: 1.8rem; margin: 0.3rem 0;">${{ number_format($legVolumes['other_legs'], 2) }} USD</h3>
        <span style="font-size: 0.85rem; color: var(--gold-light);"><i class="fa-solid fa-network-wired"></i> All Other Direct Legs Combined</span>
    </div>

    <div class="card" style="border-left: 4px solid #60a5fa; background: rgba(14, 18, 28, 0.95);">
        <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800;">TOTAL TEAM BUSINESS VOLUME</span>
        <h3 style="color: #60a5fa; font-size: 1.8rem; margin: 0.3rem 0;">${{ number_format($legVolumes['total_team_volume'] ?? $legVolumes['total_volume'] ?? 0, 2) }} USD</h3>
        <span style="font-size: 0.85rem; color: var(--text-muted);"><i class="fa-solid fa-users"></i> System Overall Team Business</span>
    </div>
</div>

<!-- PDF Page 9 Live 8-Tier Award & Reward Progression Table -->
<div class="card" style="margin-bottom: 2.5rem; border: 2px solid var(--border-gold); background: rgba(14, 18, 28, 0.95);">
    <h3 style="color: var(--gold-light); margin-bottom: 1.2rem;"><i class="fa-solid fa-award"></i> 8-Tier Award & Rank Milestones</h3>

    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Rank Title</th>
                    <th>Target Volume</th>
                    <th>Power Leg (60%)</th>
                    <th>Other Legs (40%)</th>
                    <th>Reward Bonus</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($milestones as $m)
                    @php
                        $isClaimed = in_array($m['target'], $claimedTargets);
                        $powerPct = min(100, round(($legVolumes['power_leg'] / $m['power']) * 100, 1));
                        $otherPct = min(100, round(($legVolumes['other_legs'] / $m['other']) * 100, 1));
                        $isQualified = ($legVolumes['power_leg'] >= $m['power']) && ($legVolumes['other_legs'] >= $m['other']);
                    @endphp
                    <tr>
                        <td>
                            <strong style="color: var(--gold-light); font-size: 1rem;"><i class="fa-solid fa-crown" style="color: var(--gold-primary);"></i> {{ $m['rank'] }}</strong>
                        </td>
                        <td><strong style="color: #fff;">${{ number_format($m['target']) }} USD</strong></td>

                        <!-- Power Leg 60% Progress Bar -->
                        <td style="min-width: 160px;">
                            <div style="font-size: 0.8rem; display: flex; justify-content: space-between; margin-bottom: 3px;">
                                <span style="color: var(--text-muted);">${{ number_format($legVolumes['power_leg'], 0) }} / ${{ number_format($m['power']) }}</span>
                                <strong style="color: var(--gold-light);">{{ $powerPct }}%</strong>
                            </div>
                            <div style="background: rgba(255,255,255,0.08); height: 6px; border-radius: 10px; overflow: hidden;">
                                <div style="background: var(--gold-gradient); height: 100%; width: {{ $powerPct }}%; border-radius: 10px;"></div>
                            </div>
                        </td>

                        <!-- Other Legs 40% Progress Bar -->
                        <td style="min-width: 160px;">
                            <div style="font-size: 0.8rem; display: flex; justify-content: space-between; margin-bottom: 3px;">
                                <span style="color: var(--text-muted);">${{ number_format($legVolumes['other_legs'], 0) }} / ${{ number_format($m['other']) }}</span>
                                <strong style="color: #34d399;">{{ $otherPct }}%</strong>
                            </div>
                            <div style="background: rgba(255,255,255,0.08); height: 6px; border-radius: 10px; overflow: hidden;">
                                <div style="background: #34d399; height: 100%; width: {{ $otherPct }}%; border-radius: 10px;"></div>
                            </div>
                        </td>

                        <td>
                            <strong style="color: var(--gold-light); font-size: 1.1rem;"><i class="fa-solid fa-coins"></i> {{ number_format($m['reward']) }} COINS</strong>
                        </td>

                        <td>
                            @if($isClaimed || $isQualified)
                                <span class="badge-status badge-active" style="background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; color: #34d399; font-weight: 800;"><i class="fa-solid fa-check-circle"></i> ACTIVATED</span>
                            @else
                                <span class="badge-status badge-pending" style="background: rgba(245,184,46,0.12); border: 1px solid var(--border-gold); color: var(--gold-light); font-weight: 800;"><i class="fa-solid fa-clock"></i> PENDING</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Transaction History Log -->
<div class="card">
    <h3 style="color: var(--gold-light); margin-bottom: 1rem;"><i class="fa-solid fa-list-check"></i> Reward Bonus Transaction Logs</h3>
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Reward ID</th>
                    <th>Achievement Rank Title</th>
                    <th>Coins Rewarded</th>
                    <th>Description</th>
                    <th>Achieved Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incomes as $inc)
                    <tr>
                        <td><strong style="color: var(--gold-light);">#RWD-{{ $inc->id }}</strong></td>
                        <td><strong style="color: var(--gold-light);"><i class="fa-solid fa-award"></i> Rank Milestone Reward</strong></td>
                        <td style="color: #34d399; font-weight: 800; font-size: 0.95rem;">
                            +{{ number_format($inc->amount_coins, 2) }} Coins
                        </td>
                        <td>
                            <span style="font-size: 0.85rem; color: #f8fafc;"><i class="fa-solid fa-circle-info" style="color: var(--gold-light);"></i> {{ $inc->description }}</span>
                        </td>
                        <td>{{ $inc->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">No award & reward achievements recorded yet. Reach team volume milestones to unlock rewards!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $incomes->links() }}
</div>
@endsection
