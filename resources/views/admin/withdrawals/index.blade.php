@extends('admin.layouts.app')

@section('title', 'Manage Coin Withdrawals')

@section('content')
<style>
    .form-control-custom {
        padding: 0.55rem 0.9rem;
        background: rgba(8, 10, 15, 0.8);
        border: 1px solid var(--border-gold);
        border-radius: 10px;
        color: #fff;
        font-size: 0.88rem;
        outline: none;
    }
    .form-control-custom:focus {
        border-color: var(--gold-primary);
        box-shadow: var(--gold-glow);
    }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-money-bill-transfer"></i> Coin Withdrawal Payouts & History</h2>
        <p style="color: var(--text-muted);">Review user coin withdrawal requests, verify crypto wallet addresses, and approve USDT payouts.</p>
    </div>
</div>

<!-- Search & Status Filter Tabs Bar -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div style="display: flex; gap: 0.8rem; flex-wrap: wrap;">
        <a href="{{ route('admin.withdrawals.index', ['status' => 'all']) }}" class="btn-gold" style="font-size: 0.8rem; {{ $status === 'all' ? '' : 'background: rgba(255,255,255,0.1); color: var(--text-main);' }}">All Withdrawals</a>
        <a href="{{ route('admin.withdrawals.index', ['status' => 'pending']) }}" class="btn-gold" style="font-size: 0.8rem; {{ $status === 'pending' ? '' : 'background: rgba(255,255,255,0.1); color: var(--text-main);' }}"><i class="fa-solid fa-clock"></i> Pending Review</a>
        <a href="{{ route('admin.withdrawals.index', ['status' => 'approved']) }}" class="btn-gold" style="font-size: 0.8rem; {{ $status === 'approved' ? '' : 'background: rgba(255,255,255,0.1); color: var(--text-main);' }}"><i class="fa-solid fa-circle-check"></i> Approved Payouts</a>
        <a href="{{ route('admin.withdrawals.index', ['status' => 'rejected']) }}" class="btn-gold" style="font-size: 0.8rem; {{ $status === 'rejected' ? '' : 'background: rgba(255,255,255,0.1); color: var(--text-main);' }}"><i class="fa-solid fa-circle-xmark"></i> Rejected Requests</a>
    </div>

    <form action="{{ route('admin.withdrawals.index') }}" method="GET" style="display: flex; gap: 8px;">
        <input type="hidden" name="status" value="{{ $status }}">
        <input type="text" name="search" class="form-control-custom" style="width: 240px;" placeholder="Search user, code, wallet..." value="{{ request('search') }}">
        <button type="submit" class="btn-gold" style="padding: 0.5rem 1rem;"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
</div>

<!-- Withdrawals Table -->
<div class="card">
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>User Profile</th>
                    <th>Coins Requested</th>
                    <th>Rate</th>
                    <th>Gross / Fee</th>
                    <th>Net USDT Payout</th>
                    <th>Crypto Wallet Address</th>
                    <th>Date & Time</th>
                    <th>Admin Note</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($withdrawals as $w)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="user-profile-avatar">
                                    {{ strtoupper(substr($w->user->name ?? $w->user->email ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <strong style="color: #fff;">{{ $w->user->name ?? 'User' }}</strong>
                                    <div style="font-size: 0.78rem; color: var(--gold-light);">
                                        {{ $w->user->referral_code ?? '' }}
                                        @if($w->user)
                                            <i class="fa-solid fa-copy copy-icon-btn" onclick="copyToClipboard('{{ $w->user->referral_code }}', 'Self ID')"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="color: var(--gold-light); font-weight: 800; font-size: 0.95rem;">
                            {{ number_format($w->coins_amount, 2) }} Coins
                        </td>
                        <td style="font-size: 0.82rem; color: var(--text-muted);">${{ number_format($w->conversion_rate, 4) }}</td>
                        <td>
                            <div style="font-size: 0.85rem; color: #fff;">${{ number_format($w->gross_usd, 2) }} USD</div>
                            <div style="font-size: 0.75rem; color: #ef4444;">Fee: -${{ number_format($w->deduction_usd, 2) }}</div>
                        </td>
                        <td style="color: #34d399; font-weight: 800; font-size: 1rem;">
                            ${{ number_format($w->net_usd, 2) }} USDT
                        </td>
                        <td>
                            <div style="font-family: monospace; font-size: 0.82rem; color: var(--text-muted); max-width: 180px; overflow: hidden; text-overflow: ellipsis;">
                                {{ $w->wallet_address }}
                            </div>
                            <i class="fa-solid fa-copy copy-icon-btn" onclick="copyToClipboard('{{ $w->wallet_address }}', 'Crypto Wallet Address')"></i>
                        </td>
                        <td>
                            <div style="font-size: 0.85rem; font-weight: 600; color: #fff;">{{ $w->created_at->format('M d, Y') }}</div>
                            <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $w->created_at->format('h:i A') }}</div>
                        </td>
                        <td style="font-size: 0.8rem; color: var(--text-muted); max-width: 150px; white-space: normal;">
                            {{ $w->admin_note ?? '-' }}
                        </td>
                        <td><span class="badge-status badge-{{ $w->status }}">{{ strtoupper($w->status) }}</span></td>
                        <td>
                            @if($w->status === 'pending')
                                <div style="display: flex; gap: 6px;">
                                    <form action="{{ route('admin.withdrawals.update', $w->id) }}" method="POST" data-confirm="Are you sure you want to APPROVE this USDT payout of ${{ number_format($w->net_usd, 2) }} for {{ $w->user->name ?? 'User' }}?">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn-gold" style="padding: 4px 10px; font-size: 0.75rem;"><i class="fa-solid fa-check"></i> Approve</button>
                                    </form>

                                    <form action="{{ route('admin.withdrawals.update', $w->id) }}" method="POST" data-confirm="Are you sure you want to REJECT this withdrawal request and refund {{ number_format($w->coins_amount, 2) }} coins back to the user's Earning Wallet?">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn-gold" style="background: rgba(239,68,68,0.2); color: #f87171; border-color: rgba(239,68,68,0.4); padding: 4px 10px; font-size: 0.75rem;"><i class="fa-solid fa-xmark"></i> Reject</button>
                                    </form>
                                </div>
                            @else
                                <span style="font-size: 0.78rem; color: var(--text-muted);"><i class="fa-solid fa-check-double"></i> Processed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align: center; color: var(--text-muted); padding: 2rem;">No withdrawal records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1rem;">
        {{ $withdrawals->links() }}
    </div>
</div>
@endsection
