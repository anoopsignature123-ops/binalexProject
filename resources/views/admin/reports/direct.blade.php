@extends('admin.layouts.app')

@section('title', 'Direct Referral Commission Report')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-bolt"></i> Admin Direct Income Report</h2>
        <p style="color: var(--text-muted);">Audit direct referral commissions paid out across all system users.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <div style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); padding: 0.6rem 1.2rem; border-radius: 10px; color: #34d399; font-weight: 700; font-size: 0.9rem;">
            Total Direct Paid: {{ number_format($totalDirectCommission, 2) }} Coins
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="card" style="padding: 1rem 1.2rem;">
    <form action="{{ route('admin.reports.direct') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        <div>
            <label style="font-size: 0.8rem; color: var(--text-muted); display: block; margin-bottom: 4px;">User Search</label>
            <input type="text" name="search" value="{{ $search }}" placeholder="Name, email, code..." style="padding: 0.55rem 0.9rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 8px; color: #fff; outline: none;">
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
            <a href="{{ route('admin.reports.direct') }}" class="btn-gold" style="background: rgba(255,255,255,0.1); color: #fff; padding: 0.6rem 1rem;">Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Commission ID</th>
                    <th>Beneficiary User</th>
                    <th>Referral Code</th>
                    <th>Commission Earned</th>
                    <th>Triggered From Member</th>
                    <th>Transaction Remark / Description</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incomes as $inc)
                    <tr>
                        <td><strong style="color: var(--gold-light);">#DIR-{{ $inc->id }}</strong></td>
                        <td>
                            @if($inc->user)
                                <strong>{{ $inc->user->name }}</strong>
                                <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $inc->user->email }}</div>
                            @else
                                <span style="color: var(--text-muted);">User Account</span>
                            @endif
                        </td>
                        <td><strong style="color: var(--gold-light);">{{ $inc->user->referral_code ?? 'N/A' }}</strong></td>
                        <td style="color: #34d399; font-weight: 800; font-size: 0.95rem;">
                            +{{ number_format($inc->amount_coins, 2) }} Coins
                        </td>
                        <td>
                            @if($inc->fromUser)
                                {{ $inc->fromUser->name }} ({{ $inc->fromUser->referral_code }})
                            @else
                                <span style="color: var(--text-muted);">Direct Downline</span>
                            @endif
                        </td>
                        <td>
                            <span style="font-size: 0.85rem; color: #f8fafc;"><i class="fa-solid fa-circle-info" style="color: var(--gold-light);"></i> {{ $inc->description }}</span>
                        </td>
                        <td>{{ $inc->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 2rem;">No direct referral commission logs found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $incomes->links() }}
</div>
@endsection
