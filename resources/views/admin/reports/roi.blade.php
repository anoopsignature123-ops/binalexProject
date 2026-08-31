@extends('admin.layouts.app')

@section('title', 'ROI Daily Staking Yield Report')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-coins"></i> Admin Daily ROI Yield Report</h2>
        <p style="color: var(--text-muted);">Audit all system-wide daily staking ROI yields credited to user ROI Wallets.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <div style="background: rgba(245, 184, 46, 0.15); border: 1px solid var(--border-gold); padding: 0.6rem 1.2rem; border-radius: 10px; color: var(--gold-light); font-weight: 700; font-size: 0.9rem;">
            Total ROI Distributed: {{ number_format($totalRoiDistributed, 2) }} Coins
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="card" style="padding: 1rem 1.2rem;">
    <form action="{{ route('admin.reports.roi') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
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
            <a href="{{ route('admin.reports.roi') }}" class="btn-gold" style="background: rgba(255,255,255,0.1); color: #fff; padding: 0.6rem 1rem;">Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Payout ID</th>
                    <th>Recipient User</th>
                    <th>Referral Code</th>
                    <th>Target Wallet</th>
                    <th>ROI Coins Distributed</th>
                    <th>Package Source</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incomes as $inc)
                    <tr>
                        <td><strong style="color: var(--gold-light);">#ROI-{{ $inc->id }}</strong></td>
                        <td>
                            @if($inc->user)
                                <strong>{{ $inc->user->name }}</strong>
                                <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $inc->user->email }}</div>
                            @else
                                <span style="color: var(--text-muted);">Member Account</span>
                            @endif
                        </td>
                        <td><strong style="color: var(--gold-light);">{{ $inc->user->referral_code ?? 'N/A' }}</strong></td>
                        <td>
                            <span class="badge-status badge-active" style="background: rgba(245, 184, 46, 0.2); color: var(--gold-light);">
                                ROI WALLET
                            </span>
                        </td>
                        <td style="color: #34d399; font-weight: 800; font-size: 0.95rem;">
                            +{{ number_format($inc->amount_coins, 2) }} Coins
                        </td>
                        <td>
                            @if($inc->stake)
                                ${{ number_format($inc->stake->amount_usd, 2) }} ({{ $inc->stake->package_name }})
                            @else
                                Staking Package
                            @endif
                        </td>
                        <td>{{ $inc->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 2rem;">No daily ROI payout logs found matching the filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $incomes->links() }}
</div>
@endsection
