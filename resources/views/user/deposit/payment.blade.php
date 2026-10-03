@extends('user.layouts.app')

@section('title', 'Complete Payment - Deposit #' . $deposit->id)

@section('content')
<div style="max-width: 950px; margin: 0 auto;">
    <!-- Page Header -->
    <div style="margin-bottom: 2rem;">
        <span style="color: var(--gold-light); font-size: 0.8rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">INSTANT CHECKOUT</span>
        <h2 style="color: #fff; margin: 0.2rem 0 0.5rem 0;"><i class="fa-solid fa-shield-halved" style="color: var(--gold-light);"></i> Complete Your USDT Payment</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">Transfer the exact amount to complete your deposit. Your wallet balance will update automatically upon verification.</p>
    </div>

    <!-- Main Payment Card -->
    <div class="card" style="background: linear-gradient(145deg, rgba(8, 10, 15, 0.95), rgba(18, 22, 32, 0.98)); border: 1px solid var(--border-gold); padding: 2rem;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2.5rem; align-items: start;">

            <!-- Left Column: QR Code & Wallet Address -->
            <div style="text-align: center; border-right: 1px solid rgba(212, 175, 55, 0.15); padding-right: 1.5rem;">

                <!-- Network Alert -->
                <div style="background: rgba(234, 179, 8, 0.1); border: 1px solid rgba(234, 179, 8, 0.3); color: #facc15; padding: 0.8rem 1rem; border-radius: 10px; font-size: 0.82rem; text-align: left; margin-bottom: 1.5rem;">
                    <i class="fa-solid fa-triangle-exclamation"></i> <strong>Important:</strong> Send <strong>USDT</strong> via <strong>BEP20 (Binance Smart Chain)</strong> network only.
                </div>

                <!-- Deposit Amount -->
                <div style="margin-bottom: 1.2rem;">
                    <span style="font-size: 0.85rem; color: var(--text-muted);">Exact Amount to Send:</span>
                    <h1 style="color: var(--gold-light); font-size: 2.2rem; font-weight: 800; margin: 0.2rem 0;">{{ number_format($deposit->amount_usd, 2) }} <span style="font-size: 1.2rem;">USDT</span></h1>
                    <span style="background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid #38bdf8; padding: 3px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 700; display: inline-block;">Network: BEP20 (BSC)</span>
                </div>

                <!-- QR Code Display -->
                <div style="background: #fff; padding: 12px; border-radius: 16px; display: inline-block; margin-bottom: 1.2rem; box-shadow: 0 8px 24px rgba(0,0,0,0.4);">
                    @php
                        $qrAddress = $deposit->wallet_address ?? $deposit->gateway_url ?? 'USDT_PAYMENT';
                        $qrUrl = "https://quickchart.io/qr?text=" . urlencode($qrAddress) . "&size=200&margin=1";
                    @endphp
                    <img src="{{ $qrUrl }}" alt="USDT Deposit QR Code" style="width: 180px; height: 180px; display: block; border-radius: 8px;">
                </div>

                <!-- Wallet Address Box -->
                <div style="text-align: left; margin-bottom: 1.5rem;">
                    <label style="font-size: 0.8rem; color: var(--text-muted); font-weight: 700; display: block; margin-bottom: 0.4rem;">OFFICIAL DEPOSIT WALLET ADDRESS:</label>
                    <div style="display: flex; align-items: center; gap: 8px; background: rgba(8, 10, 15, 0.8); border: 1px solid var(--border-gold); padding: 0.7rem 1rem; border-radius: 10px;">
                        <code id="depositWalletAddress" style="color: var(--gold-light); font-size: 0.88rem; font-weight: 700; word-break: break-all; flex-grow: 1; font-family: monospace;">{{ $deposit->wallet_address ?? 'Generating payment address...' }}</code>
                        <button class="btn-gold" onclick="copyDepositAddress()" style="padding: 6px 14px; font-size: 0.8rem; text-transform: none; white-space: nowrap;"><i class="fa-solid fa-copy"></i> Copy</button>
                    </div>
                </div>

                <!-- Timer & Live Verification Status -->
                <div style="display: flex; align-items: center; justify-content: space-between; background: rgba(0,0,0,0.4); padding: 0.8rem 1rem; border-radius: 10px; border: 1px solid rgba(255,255,255,0.05);">
                    <div id="paymentTimer" style="color: #ef4444; font-size: 0.85rem; font-weight: 700;">
                        <i class="fa-solid fa-clock"></i> Expires: <span id="countdown">29:59</span>
                    </div>
                    <div id="statusSection" style="color: #f59e0b; font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-spinner fa-spin"></i> Checking Payment...
                    </div>
                </div>

            </div>

            <!-- Right Column: How to Pay Instructions & External Link -->
            <div style="padding-left: 0.5rem;">
                <div style="background: rgba(15, 20, 30, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 14px; padding: 1.5rem; margin-bottom: 1.5rem;">
                    <h3 style="color: #fff; font-size: 1.1rem; margin-top: 0; margin-bottom: 1.2rem; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 0.6rem;">
                        <i class="fa-solid fa-circle-info" style="color: var(--gold-light);"></i> How to Complete Payment
                    </h3>

                    <ul style="color: var(--text-muted); font-size: 0.88rem; padding-left: 1.2rem; margin: 0; line-height: 1.8;">
                        <li style="margin-bottom: 0.8rem;">
                            <strong>Step 1:</strong> Scan the QR code or copy the <strong>BEP20 Wallet Address</strong> shown on the left.
                        </li>
                        <li style="margin-bottom: 0.8rem;">
                            <strong>Step 2:</strong> Open your crypto app (Trust Wallet, Binance, OKX, MetaMask, etc.) and choose <strong>Withdraw / Send USDT</strong>.
                        </li>
                        <li style="margin-bottom: 0.8rem;">
                            <strong>Step 3:</strong> Select <strong>BNB Smart Chain (BEP20)</strong> network and enter the exact amount: <strong style="color: var(--gold-light);">${{ number_format($deposit->amount_usd, 2) }} USDT</strong>.
                        </li>
                        <li>
                            <strong>Step 4:</strong> Confirm the transfer. This page will automatically update once the blockchain confirms your payment.
                        </li>
                    </ul>
                </div>

                @if($deposit->gateway_url)
                <div style="background: rgba(56, 189, 248, 0.08); border: 1px solid rgba(56, 189, 248, 0.3); border-radius: 14px; padding: 1.2rem; margin-bottom: 1.5rem; text-align: center;">
                    <p style="color: #38bdf8; font-size: 0.85rem; margin-top: 0; margin-bottom: 0.8rem;">Prefer paying directly on the official Gateway page?</p>
                    <a href="{{ $deposit->gateway_url }}" target="_blank" class="btn-gold" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; width: 100%; background: linear-gradient(135deg, #0284c7, #38bdf8); color: #fff; border: none; text-decoration: none;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Open External Gateway Page
                    </a>
                </div>
                @endif

                <a href="{{ route('user.deposit.index') }}" class="btn-gold" style="width: 100%; justify-content: center; background: transparent; border: 1px solid var(--border-gold); color: var(--gold-light); text-decoration: none;">
                    <i class="fa-solid fa-arrow-left"></i> Back to Deposit History
                </a>
            </div>

        </div>
    </div>
</div>

<script>
    function copyDepositAddress() {
        const address = document.getElementById('depositWalletAddress').innerText.trim();
        if (!address || address.includes('Generating')) return;

        navigator.clipboard.writeText(address).then(() => {
            alert('USDT Wallet Address copied to clipboard!');
        }).catch(() => {
            const input = document.createElement('input');
            input.value = address;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            alert('USDT Wallet Address copied!');
        });
    }

    // 30-Minute Countdown Timer
    let timeRemaining = 30 * 60;
    const countdownEl = document.getElementById('countdown');

    const timerInterval = setInterval(() => {
        let minutes = Math.floor(timeRemaining / 60);
        let seconds = timeRemaining % 60;
        countdownEl.innerText = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

        if (timeRemaining <= 0) {
            clearInterval(timerInterval);
            document.getElementById('paymentTimer').innerHTML = '<span style="color: #ef4444;">Session Expired</span>';
        }
        timeRemaining--;
    }, 1000);

    // Live AJAX Status Check (Polls backend every 5 seconds)
    const checkStatusUrl = "{{ route('user.deposit.check-status', $deposit->id) }}";

    const statusInterval = setInterval(() => {
        fetch(checkStatusUrl)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'approved' || data.status === 'success') {
                    clearInterval(statusInterval);
                    clearInterval(timerInterval);

                    document.getElementById('statusSection').innerHTML = `
                        <span style="color: #22c55e;"><i class="fa-solid fa-circle-check"></i> PAYMENT APPROVED!</span>
                    `;

                    setTimeout(() => {
                        window.location.href = "{{ route('user.deposit.index') }}";
                    }, 2000);
                }
            })
            .catch(err => console.error('Error polling payment status:', err));
    }, 5000);
</script>
@endsection
