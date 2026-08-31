@extends('user.layouts.app')

@section('title', 'Income Logs')

@section('content')
<h2 style="color: var(--gold-light); margin-bottom: 0.5rem;"><i class="fa-solid fa-receipt"></i> Income & Earnings Logs</h2>
<p style="color: var(--text-muted); margin-bottom: 1.5rem;">Filter and audit all daily ROI payouts, direct referral bonuses, 10-level commissions, and award rewards.</p>

<!-- Filter Bar -->
<div class="card" style="padding: 1rem 1.2rem;">
    <form action="{{ route('user.reports.incomes') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        <div>
            <label style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 4px;">Income Type</label>
            <select name="type" style="padding: 0.55rem 0.9rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 8px; color: #fff; outline: none;">
                <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All Incomes</option>
                <option value="daily_stake" {{ $type === 'daily_stake' || $type === 'roi' ? 'selected' : '' }}>Daily Staking ROI</option>
                <option value="direct_income" {{ $type === 'direct_income' || $type === 'direct' ? 'selected' : '' }}>Direct Income</option>
                <option value="level_income" {{ $type === 'level_income' || $type === 'level' ? 'selected' : '' }}>Level Income</option>
                <option value="award_reward" {{ $type === 'award_reward' || $type === 'matching' ? 'selected' : '' }}>Award & Rewards</option>
            </select>
        </div>
        <div>
            <label style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 4px;">From Date</label>
            <input type="date" name="from_date" value="{{ $fromDate ?? '' }}" style="padding: 0.55rem 0.9rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 8px; color: #fff; outline: none;">
        </div>
        <div>
            <label style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 4px;">To Date</label>
            <input type="date" name="to_date" value="{{ $toDate ?? '' }}" style="padding: 0.55rem 0.9rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 8px; color: #fff; outline: none;">
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn-gold" style="padding: 0.6rem 1.2rem;"><i class="fa-solid fa-filter"></i> Apply Filter</button>
            <a href="{{ route('user.reports.incomes') }}" class="btn-gold" style="background: rgba(255,255,255,0.1); color: #fff; padding: 0.6rem 1rem;">Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Income Type</th>
                    <th>Level</th>
                    <th>From Member</th>
                    <th>Coins Earned</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incomes as $inc)
                    <tr>
                        <td>{{ $inc->created_at->format('M d, Y H:i') }}</td>
                        <td>
                            <span class="badge-status badge-approved">{{ strtoupper(str_replace('_', ' ', $inc->type)) }}</span>
                        </td>
                        <td>{{ $inc->level ? 'Level ' . $inc->level : '-' }}</td>
                        <td>{{ $inc->fromUser ? ($inc->fromUser->name ?? $inc->fromUser->email) : '-' }}</td>
                        <td style="color: var(--gold-light); font-weight: 800;">+{{ number_format($inc->amount_coins, 2) }} Coins</td>
                        <td>{{ $inc->description }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted);">No income records found for this filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1rem;">
        {{ $incomes->links() }}
    </div>
</div>
@endsection
