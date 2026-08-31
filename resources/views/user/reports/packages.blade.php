@extends('user.layouts.app')

@section('title', 'Package Activation History')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-box-archive"></i> Package Activation History</h2>
        <p style="color: var(--text-muted);">Inspect all your active and completed staking investment packages.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <div style="background: rgba(245, 184, 46, 0.15); border: 1px solid var(--border-gold); padding: 0.6rem 1.2rem; border-radius: 10px; color: var(--gold-light); font-weight: 700; font-size: 0.9rem;">
            Total Staked: ${{ number_format($totalStakedAmount, 2) }} USD
        </div>
        <a href="{{ route('user.staking.index') }}" class="btn-gold"><i class="fa-solid fa-plus"></i> Stake New Package</a>
    </div>
</div>

<!-- Filter Bar -->
<div class="card" style="padding: 1rem 1.2rem;">
    <form action="{{ route('user.reports.packages') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        <div>
            <label style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 4px;">Status</label>
            <select name="status" style="padding: 0.55rem 0.9rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 8px; color: #fff; outline: none;">
                <option value="">All Status</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div>
            <label style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 4px;">From Date</label>
            <input type="date" name="from_date" value="{{ $fromDate }}" style="padding: 0.55rem 0.9rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 8px; color: #fff; outline: none;">
        </div>
        <div>
            <label style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 4px;">To Date</label>
            <input type="date" name="to_date" value="{{ $toDate }}" style="padding: 0.55rem 0.9rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 8px; color: #fff; outline: none;">
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn-gold" style="padding: 0.6rem 1.2rem;"><i class="fa-solid fa-filter"></i> Apply Filter</button>
            <a href="{{ route('user.reports.packages') }}" class="btn-gold" style="background: rgba(255,255,255,0.1); color: #fff; padding: 0.6rem 1rem;">Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Package Code</th>
                    <th>Package Title</th>
                    <th>USD Amount</th>
                    <th>Coins Staked</th>
                    <th>Total Yield</th>
                    <th>Daily Payout</th>
                    <th>Duration (Days)</th>
                    <th>Status</th>
                    <th>Activated Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stakes as $s)
                    <tr>
                        <td><strong style="color: var(--gold-light);">#STK-{{ $s->id }}</strong></td>
                        <td><strong>{{ $s->package_name }}</strong></td>
                        <td style="color: var(--gold-light); font-weight: 700;">${{ number_format($s->amount_usd, 2) }}</td>
                        <td>{{ number_format($s->coins_staked, 2) }} Coins</td>
                        <td style="color: #34d399; font-weight: 700;">{{ number_format($s->total_yield_coins, 2) }} Coins</td>
                        <td>{{ number_format($s->daily_payout_coins, 2) }} Coins/day</td>
                        <td>{{ $s->days_completed }} / {{ $s->duration_days }} Days</td>
                        <td><span class="badge-status badge-{{ $s->status }}">{{ strtoupper($s->status) }}</span></td>
                        <td>{{ $s->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 2rem;">No staking package activations recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $stakes->links() }}
</div>
@endsection
