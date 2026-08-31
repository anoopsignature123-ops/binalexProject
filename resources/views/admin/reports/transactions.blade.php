@extends('admin.layouts.app')

@section('title', 'Financial Transactions Log')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-receipt"></i> System Transactions & Income Report</h2>
        <p style="color: var(--text-muted);">Comprehensive financial transaction audit log across all user accounts.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); padding: 0.6rem 1.2rem; border-radius: 10px; color: #34d399; font-weight: 700; font-size: 0.9rem;">
            Total Distributed: {{ number_format($totalCoinsDistributed, 2) }} Coins
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="card" style="padding: 1rem 1.2rem;">
    <form action="{{ route('admin.reports.transactions') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        <div>
            <label style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 4px;">User Search</label>
            <input type="text" name="search" value="{{ $search }}" placeholder="Name, email, code..." style="padding: 0.55rem 0.9rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 8px; color: #fff; outline: none;">
        </div>
        <div>
            <label style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 4px;">Transaction Type</label>
            <select name="type" style="padding: 0.55rem 0.9rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 8px; color: #fff; outline: none;">
                <option value="">All Types</option>
                <option value="direct_income" {{ $type === 'direct_income' || $type === 'direct' ? 'selected' : '' }}>Direct Income</option>
                <option value="level_income" {{ $type === 'level_income' || $type === 'level' ? 'selected' : '' }}>Level Income</option>
                <option value="daily_stake" {{ $type === 'daily_stake' || $type === 'roi' ? 'selected' : '' }}>Daily Staking ROI</option>
                <option value="award_reward" {{ $type === 'award_reward' || $type === 'matching' ? 'selected' : '' }}>Award & Reward</option>
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
            <a href="{{ route('admin.reports.transactions') }}" class="btn-gold" style="background: rgba(255,255,255,0.1); color: #fff; padding: 0.6rem 1rem;">Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Tx ID</th>
                    <th>Beneficiary User</th>
                    <th>Referral Code</th>
                    <th>Income Type</th>
                    <th>Amount Distributed</th>
                    <th>Transaction Remark / Description</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incomes as $inc)
                    <tr>
                        <td><strong style="color: var(--gold-light);">#TX-{{ $inc->id }}</strong></td>
                        <td>
                            @if($inc->user)
                                <strong>{{ $inc->user->name }}</strong>
                                <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $inc->user->email }}</div>
                            @else
                                <span style="color: var(--text-muted);">User Account</span>
                            @endif
                        </td>
                        <td><strong style="color: var(--gold-light);">{{ $inc->user->referral_code ?? 'N/A' }}</strong></td>
                        <td>
                            <span class="badge-status badge-active" style="background: rgba(245, 184, 46, 0.18); color: var(--gold-light);">
                                {{ strtoupper(str_replace('_', ' ', $inc->type)) }}
                            </span>
                        </td>
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
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 2rem;">No transaction logs found matching the filter criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $incomes->links() }}
</div>
@endsection
