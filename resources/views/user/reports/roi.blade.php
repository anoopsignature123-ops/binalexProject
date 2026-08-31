@extends('user.layouts.app')

@section('title', 'ROI Daily Staking Yield Report')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-coins"></i> Daily Staking ROI Yield Report</h2>
        <p style="color: var(--text-muted);">Inspect daily staking yield payouts credited to your ROI Wallet.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <div style="background: rgba(245, 184, 46, 0.15); border: 1px solid var(--border-gold); padding: 0.6rem 1.2rem; border-radius: 10px; color: var(--gold-light); font-weight: 700; font-size: 0.9rem;">
            ROI Wallet Balance: {{ number_format($user->coin_balance, 2) }} Coins
        </div>
    </div>
</div>

<!-- Filter Bar -->
<div class="card" style="padding: 1rem 1.2rem;">
    <form action="{{ route('user.reports.roi') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
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
            <a href="{{ route('user.reports.roi') }}" class="btn-gold" style="background: rgba(255,255,255,0.1); color: #fff; padding: 0.6rem 1rem;">Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Payout ID</th>
                    <th>Destination Wallet</th>
                    <th>Package Source</th>
                    <th>ROI Coins Earned</th>
                    <th>Description</th>
                    <th>Credited Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($incomes as $inc)
                    <tr>
                        <td><strong style="color: var(--gold-light);">#ROI-{{ $inc->id }}</strong></td>
                        <td>
                            <span class="badge-status badge-active" style="background: rgba(245, 184, 46, 0.2); color: var(--gold-light);">
                                <i class="fa-solid fa-wallet"></i> ROI WALLET
                            </span>
                        </td>
                        <td>
                            @if($inc->stake)
                                ${{ number_format($inc->stake->amount_usd, 2) }} ({{ $inc->stake->package_name }})
                            @else
                                Active Package
                            @endif
                        </td>
                        <td style="color: #34d399; font-weight: 800; font-size: 0.95rem;">
                            +{{ number_format($inc->amount_coins, 2) }} Coins
                        </td>
                        <td>{{ $inc->description }}</td>
                        <td>{{ $inc->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 2rem;">No daily staking ROI yield payouts recorded yet. Activate a staking package to start earning daily ROI!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $incomes->links() }}
</div>
@endsection
