@extends('user.layouts.app')

@section('title', 'Withdraw Coins')

@section('content')
<h2 style="color: var(--gold-light); margin-bottom: 0.5rem;"><i class="fa-solid fa-hand-holding-dollar"></i> Withdraw Coins (USDT BEP20)</h2>
<p style="color: var(--text-muted); margin-bottom: 2rem;">Convert your earned Binalex Coins to USDT BEP20.</p>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
    <!-- Withdrawal Rules & Converter -->
    <div class="card">
        <h3 style="color: var(--gold-light); margin-bottom: 1rem;"><i class="fa-solid fa-calculator"></i> Terms & Withdrawal Rates</h3>

        <div style="background: rgba(8, 10, 15, 0.7); border: 1px solid var(--border-gold); padding: 1.2rem; border-radius: 14px; margin-bottom: 1.2rem;">
            <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                <span>Available Coin Balance:</span> <strong style="color: var(--gold-light);">{{ number_format($user->coin_balance, 2) }} Coins</strong>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                <span>Conversion Rate:</span> <strong>$0.10 per coin</strong>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                <span>Minimum Withdrawal:</span> <strong style="color: var(--gold-light);">5 Coins</strong>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 6px 0;">
                <span>Withdrawal Fee:</span> <strong style="color: #ef4444;">10% Deduction</strong>
            </div>
        </div>

        <ul style="color: var(--text-muted); font-size: 0.85rem; padding-left: 1.2rem; line-height: 1.6;">
            <li>Example: Withdrawing <strong>10 Coins</strong> @ $0.10 = $1.00 Gross.</li>
            <li>10% Deduction ($0.10) = <strong>$0.90 USDT Net Payout</strong>.</li>
            <li>Payouts are processed directly to your USDT BEP20 wallet.</li>
        </ul>
    </div>

    <!-- Request Form -->
    <div class="card">
        <h3 style="color: var(--gold-light); margin-bottom: 1rem;"><i class="fa-solid fa-paper-plane"></i> Request Withdrawal</h3>

        <form action="{{ route('user.withdrawal.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--text-muted);">Amount of Coins to Withdraw (Min 5)</label>
                <input type="number" step="0.1" id="coinsInput" name="coins_amount" style="width: 100%; padding: 0.8rem 1rem; background: rgba(8, 10, 15, 0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: var(--text-main); font-size: 1rem;" placeholder="e.g. 10" min="5" max="{{ $user->coin_balance }}" oninput="calculatePayout()" required>
            </div>

            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--text-muted);">USDT BEP20 Wallet Address</label>
                <input type="text" name="wallet_address" style="width: 100%; padding: 0.8rem 1rem; background: rgba(8, 10, 15, 0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: var(--text-main); font-size: 1rem;" placeholder="0x..." value="{{ old('wallet_address', $user->usdt_wallet_address) }}" required>
            </div>

            <div style="background: rgba(245, 184, 46, 0.08); border: 1px solid var(--border-gold); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.85rem;">
                <div style="display: flex; justify-content: space-between;"><span>Gross USD ($0.10/coin):</span> <strong id="grossText">$0.00</strong></div>
                <div style="display: flex; justify-content: space-between; margin: 4px 0;"><span>10% Fee Deduction:</span> <strong id="feeText" style="color: #ef4444;">-$0.00</strong></div>
                <div style="display: flex; justify-content: space-between; font-size: 1rem; margin-top: 6px; border-top: 1px solid var(--border-gold); padding-top: 6px;">
                    <span>Net USDT You Receive:</span> <strong id="netText" style="color: var(--gold-light);">$0.00 USDT</strong>
                </div>
            </div>

            <button type="submit" class="btn-gold" style="width: 100%; justify-content: center;"><i class="fa-solid fa-money-bill-transfer"></i> SUBMIT WITHDRAWAL REQUEST</button>
        </form>
    </div>
</div>

<!-- History -->
<div class="card">
    <h3 style="color: var(--gold-light); margin-bottom: 1rem;"><i class="fa-solid fa-clock-rotate-left"></i> Withdrawal History</h3>
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Coins</th>
                    <th>Rate</th>
                    <th>Gross USD</th>
                    <th>10% Deduction</th>
                    <th>Net USDT</th>
                    <th>Wallet Address</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($withdrawals as $w)
                    <tr>
                        <td>{{ $w->created_at->format('M d, Y H:i') }}</td>
                        <td>{{ number_format($w->coins_amount, 2) }} Coins</td>
                        <td>${{ number_format($w->conversion_rate, 2) }}</td>
                        <td>${{ number_format($w->gross_usd, 2) }}</td>
                        <td style="color: #ef4444;">-${{ number_format($w->deduction_usd, 2) }}</td>
                        <td style="color: var(--gold-light); font-weight: 800;">${{ number_format($w->net_usd, 2) }} USDT</td>
                        <td style="font-family: monospace; font-size: 0.85rem;">{{ Str::limit($w->wallet_address, 14) }}</td>
                        <td>
                            @if($w->status === 'approved')
                                <span class="badge-status badge-approved">APPROVED</span>
                            @elseif($w->status === 'pending')
                                <span class="badge-status badge-pending">PENDING</span>
                            @else
                                <span class="badge-status badge-rejected">REJECTED</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-muted);">No withdrawal history found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1rem;">
        {{ $withdrawals->links() }}
    </div>
</div>

<script>
    function calculatePayout() {
        const coins = parseFloat(document.getElementById('coinsInput').value) || 0;
        const rate = 0.10;
        const gross = coins * rate;
        const fee = gross * 0.10;
        const net = gross - fee;

        document.getElementById('grossText').innerText = '$' + gross.toFixed(2);
        document.getElementById('feeText').innerText = '-$' + fee.toFixed(2);
        document.getElementById('netText').innerText = '$' + net.toFixed(2) + ' USDT';
    }
</script>
@endsection
