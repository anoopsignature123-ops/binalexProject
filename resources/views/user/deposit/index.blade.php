@extends('user.layouts.app')

@section('title', 'Deposit USDT')

@section('content')
<h2 style="color: var(--gold-light); margin-bottom: 0.5rem;"><i class="fa-solid fa-wallet"></i> Deposit USDT (BEP20 / TRC20)</h2>
<p style="color: var(--text-muted); margin-bottom: 2rem;">Deposit USDT into your Deposit Wallet balance to purchase Self Stake Packages.</p>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
    <!-- Deposit Instructions & Address -->
    <div class="card">
        <h3 style="color: var(--gold-light); margin-bottom: 1rem;"><i class="fa-solid fa-qrcode"></i> Official USDT Payment Details</h3>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.2rem;">Send USDT over Binance Smart Chain (BEP20) network to the official wallet address below:</p>

        <div style="background: rgba(8, 10, 15, 0.7); border: 1px solid var(--border-gold); padding: 1.2rem; border-radius: 14px; text-align: center; margin-bottom: 1.2rem;">
            <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Official USDT (BEP20) Wallet Address</div>
            <div id="depositAddress" style="font-size: 1rem; font-weight: 700; color: var(--gold-light); word-break: break-all; margin: 0.5rem 0;">{{ $adminBep20Address }}</div>
            <button class="btn-gold" onclick="copyToClipboard('{{ $adminBep20Address }}', 'USDT Wallet Address')" style="font-size: 0.8rem; padding: 6px 16px;"><i class="fa-solid fa-copy"></i> Copy Address</button>
        </div>

        <ul style="color: var(--text-muted); font-size: 0.85rem; padding-left: 1.2rem; line-height: 1.6;">
            <li>Only send USDT via the <strong>BEP20 (Binance Smart Chain)</strong> network.</li>
            <li>After sending, copy the Transaction Hash (TxHash) and submit the form.</li>
            <li>Admin verification typically completes within a few minutes.</li>
        </ul>
    </div>

    <!-- Deposit Submit Form -->
    <div class="card">
        <h3 style="color: var(--gold-light); margin-bottom: 1rem;"><i class="fa-solid fa-paper-plane"></i> Submit Deposit Transaction Details</h3>

        <form action="{{ route('user.deposit.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--gold-light); font-weight: 700;">Amount Deposited ($ USDT) *</label>
                <input type="number" step="0.01" min="1" name="amount_usd" style="width: 100%; padding: 0.8rem 1rem; background: rgba(8, 10, 15, 0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: var(--text-main); font-size: 1rem;" placeholder="e.g. 30.00" required>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--gold-light); font-weight: 700;">Transaction Hash (TxHash) *</label>
                <input type="text" name="transaction_hash" style="width: 100%; padding: 0.8rem 1rem; background: rgba(8, 10, 15, 0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: var(--text-main); font-size: 1rem;" placeholder="e.g. 0x7a8b9c..." required>
            </div>

            <button type="submit" class="btn-gold" style="width: 100%; justify-content: center;"><i class="fa-solid fa-check"></i> SUBMIT DEPOSIT REQUEST</button>
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
