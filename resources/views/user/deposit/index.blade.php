@extends('user.layouts.app')

@section('title', 'Deposit USDT')

@section('content')
<h2 style="color: var(--gold-light); margin-bottom: 0.5rem;"><i class="fa-solid fa-wallet"></i> Deposit USDT (BEP20 / TRC20)</h2>
<p style="color: var(--text-muted); margin-bottom: 2rem;">Deposit USDT into your Deposit Wallet balance to purchase Self Stake Packages.</p>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
    <!-- Automatic Gateway Payment Request Card -->
    <div class="card" style="border: 1px solid var(--border-gold); background: linear-gradient(145deg, rgba(8,10,15,0.95), rgba(18,24,38,0.98));">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
            <h3 style="color: var(--gold-light); margin: 0;"><i class="fa-solid fa-bolt"></i> Fund Your Deposit Wallet</h3>
            <span style="background: rgba(245, 184, 46, 0.15); color: var(--gold-light); border: 1px solid var(--border-gold); padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">USDT (BEP20) GATEWAY</span>
        </div>
        <p style="color: var(--text-muted); font-size: 0.88rem; margin-bottom: 1.5rem;">Enter the amount in USDT (BEP20) to proceed securely through the payment gateway checkout.</p>

        <form action="{{ route('user.deposit.oxapay') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1.4rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--gold-light); font-weight: 700;">Amount ($ USDT - BEP20) *</label>
                <input type="number" step="0.01" min="1" max="50000" name="amount_usd" style="width: 100%; padding: 0.85rem 1rem; background: rgba(8, 10, 15, 0.8); border: 1px solid var(--border-gold); border-radius: 10px; color: var(--text-main); font-size: 1rem;" placeholder="Enter amount (e.g. 50.00)" required>
                <small style="color: var(--text-muted); display: block; margin-top: 0.4rem; font-size: 0.8rem;">Network is fixed to <b>USDT - BEP20 (Binance Smart Chain)</b>.</small>
            </div>

            <button type="submit" class="btn-gold" style="width: 100%; justify-content: center; padding: 0.9rem 1rem; font-size: 1rem; font-weight: 700;"><i class="fa-solid fa-shield-lock"></i> PROCEED TO PAY VIA GATEWAY</button>
        </form>
    </div>
</div>

<!-- Deposit History -->
<div class="card">
    <h3 style="color: var(--gold-light); margin-bottom: 1rem;"><i class="fa-solid fa-list"></i> My Deposit History</h3>
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Amount USD</th>
                    <th>Payment Method</th>
                    <th>Tx Hash / Reference</th>
                    <th>Admin Note</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deposits as $dep)
                    <tr>
                        <td>
                            <div style="font-size: 0.85rem; font-weight: 600; color: #fff;">{{ $dep->created_at->format('M d, Y') }}</div>
                            <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $dep->created_at->format('h:i A') }}</div>
                        </td>
                        <td style="color: var(--gold-light); font-weight: 800; font-size: 0.95rem;">
                            ${{ number_format(abs($dep->amount_usd), 2) }} USDT
                            @if($dep->amount_usd < 0)
                                <span class="badge-status badge-rejected" style="font-size: 0.65rem; padding: 2px 6px;">DEBIT</span>
                            @endif
                        </td>
                        <td><span style="font-weight: 700; color: #38bdf8;">{{ $dep->payment_method ?? 'USDT BEP20' }}</span></td>
                        <td style="font-family: monospace; font-size: 0.85rem;">
                            {{ $dep->txn_hash ?? $dep->transaction_hash ?? 'N/A' }}
                            @if($dep->txn_hash || $dep->transaction_hash)
                                <i class="fa-solid fa-copy copy-icon-btn" onclick="copyToClipboard('{{ $dep->txn_hash ?? $dep->transaction_hash }}', 'TxHash')"></i>
                            @endif
                        </td>
                        <td style="font-size: 0.8rem; color: var(--text-muted);">{{ $dep->admin_note ?? '-' }}</td>
                        <td>
                            @if($dep->status === 'approved')
                                <span class="badge-status badge-approved"><i class="fa-solid fa-circle-check"></i> APPROVED</span>
                            @elseif($dep->status === 'pending')
                                <span class="badge-status badge-pending"><i class="fa-solid fa-clock"></i> PENDING</span>
                            @else
                                <span class="badge-status badge-rejected"><i class="fa-solid fa-circle-xmark"></i> REJECTED</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 2rem;">No deposit history found. Submit your first deposit above!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1rem;">
        {{ $deposits->links() }}
    </div>
</div>
@endsection
