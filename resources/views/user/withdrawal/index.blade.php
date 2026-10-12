@extends('user.layouts.app')

@section('title', 'Withdraw Coins')

@section('content')
<style>
    .funearn-summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.2rem;
        margin-bottom: 1.8rem;
    }
    .funearn-card {
        background: linear-gradient(145deg, rgba(14, 18, 28, 0.95), rgba(22, 28, 44, 0.98));
        border: 1px solid var(--border-gold);
        border-radius: 16px;
        padding: 1.3rem;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .funearn-card:hover {
        transform: translateY(-2px);
        border-color: var(--gold-light);
    }
    .funearn-lbl {
        font-size: 0.76rem;
        color: var(--text-muted);
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .funearn-val {
        font-size: 1.6rem;
        font-weight: 800;
        color: #fff;
        margin: 0.3rem 0;
    }
    .funearn-sub {
        font-size: 0.8rem;
        color: var(--gold-light);
        font-weight: 600;
    }
    .contract-banner-funearn {
        background: linear-gradient(135deg, rgba(245, 184, 46, 0.14) 0%, rgba(10, 14, 22, 0.98) 100%);
        border: 1px solid var(--border-gold);
        border-radius: 16px;
        padding: 1.2rem 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    }
    .form-control-funearn {
        width: 100%;
        padding: 0.8rem 1rem;
        background: rgba(8, 10, 15, 0.85);
        border: 1px solid var(--border-gold);
        border-radius: 10px;
        color: #fff;
        font-size: 0.95rem;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-control-funearn:focus {
        border-color: var(--gold-primary);
        box-shadow: var(--gold-glow);
    }
</style>

<!-- Header Title -->
<div style="margin-bottom: 1.5rem;">
    <span style="color: var(--gold-light); font-size: 0.8rem; font-weight: 800; letter-spacing: 1px; text-transform: uppercase;">FUNEARN DAPP PAYOUT ENGINE</span>
    <h2 style="color: #fff; margin: 0.2rem 0 0.4rem 0;"><i class="fa-solid fa-hand-holding-dollar" style="color: var(--gold-light);"></i> Withdraw Funds (USDT BEP20)</h2>
    <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">Convert your earned Binalex Coins into USDT BEP20 crypto payout directly to your wallet.</p>
</div>

<!-- 3 Wallet Balances Cards -->
<div class="funearn-summary-grid">
    <!-- Card 1: Working Income Wallet -->
    <div class="funearn-card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="funearn-lbl">Working Income Wallet</div>
            <i class="fa-solid fa-briefcase" style="color: #38bdf8; font-size: 1.1rem;"></i>
        </div>
        <div class="funearn-val" style="color: #38bdf8;">{{ number_format($user->working_coin_balance, 2) }} <span style="font-size: 0.9rem;">Coins</span></div>
        <div class="funearn-sub">≈ ${{ number_format($user->working_coin_balance * $rate, 2) }} USD</div>
    </div>

    <!-- Card 2: ROI Staking Yield Wallet -->
    <div class="funearn-card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="funearn-lbl">ROI Yield Wallet</div>
            <i class="fa-solid fa-chart-line" style="color: #34d399; font-size: 1.1rem;"></i>
        </div>
        <div class="funearn-val" style="color: #34d399;">{{ number_format($user->coin_balance, 2) }} <span style="font-size: 0.9rem;">Coins</span></div>
        <div class="funearn-sub">≈ ${{ number_format($user->coin_balance * $rate, 2) }} USD</div>
    </div>

    <!-- Card 3: Total Withdrawable Balance -->
    <div class="funearn-card" style="border-color: var(--gold-primary);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="funearn-lbl" style="color: var(--gold-light);">Total Withdrawable</div>
            <i class="fa-solid fa-wallet" style="color: var(--gold-light); font-size: 1.1rem;"></i>
        </div>
        <div class="funearn-val" style="color: var(--gold-light);">{{ number_format($user->working_coin_balance + $user->coin_balance, 2) }} <span style="font-size: 0.9rem;">Coins</span></div>
        <div class="funearn-sub">≈ ${{ number_format(($user->working_coin_balance + $user->coin_balance) * $rate, 2) }} USD</div>
    </div>
</div>

<!-- Smart Contract Info Banner -->
<div class="contract-banner-funearn">
    <div>
        <div style="font-size: 0.75rem; font-weight: 800; color: var(--gold-light); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">
            <i class="fa-solid fa-file-contract"></i> OFFICIAL SMART CONTRACT ADDRESS
        </div>
        <div style="font-size: 0.95rem; color: #fff; font-weight: 700;">
            Contract Address: 
            <code id="contractAddressText" style="color: var(--gold-light); background: rgba(0,0,0,0.6); padding: 4px 10px; border-radius: 6px; font-family: monospace; word-break: break-all;">{{ $pricing->contract_address ?? $pricing->withdrawal_contract_address ?? '0xd69F8458CeE7381b2D9272a5dB27fa39699bB138' }}</code>
            <button type="button" onclick="copyContractAddress()" style="background: rgba(245,184,46,0.2); border: 1px solid var(--border-gold); color: var(--gold-light); padding: 3px 10px; border-radius: 6px; cursor: pointer; font-size: 0.8rem; margin-left: 6px; font-weight: 700;">
                <i class="fa-solid fa-copy"></i> Copy
            </button>
        </div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">
            Network: <strong>Binance Smart Chain (BEP20)</strong> | Payout Currency: <strong>USDT (BEP20)</strong>
        </div>
    </div>

    <div>
        <a href="https://bscscan.com/address/{{ $pricing->contract_address ?? $pricing->withdrawal_contract_address ?? '0xd69F8458CeE7381b2D9272a5dB27fa39699bB138' }}" target="_blank" class="btn-gold" style="font-size: 0.82rem; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid #38bdf8;">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> View on BSCScan
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; margin-bottom: 2rem; align-items: start;">
    
    <!-- Left Column: Request Form -->
    <div class="funearn-card">
        <h3 style="color: var(--gold-light); margin-bottom: 1.2rem; font-size: 1.15rem;">
            <i class="fa-solid fa-paper-plane"></i> Request Withdrawal
        </h3>

        <form action="{{ route('user.withdrawal.store') }}" method="POST">
            @csrf
            
            <!-- Wallet Selection -->
            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--text-muted); font-weight: 700;">Select Wallet Source</label>
                <select name="wallet_type" id="walletTypeSelect" class="form-control-funearn" onchange="updateMaxBalance()">
                    <option value="all" selected>All Available Coins ({{ number_format($user->working_coin_balance + $user->coin_balance, 2) }} Coins)</option>
                    <option value="working">Working Income Wallet ({{ number_format($user->working_coin_balance, 2) }} Coins)</option>
                    <option value="roi">ROI Yield Wallet ({{ number_format($user->coin_balance, 2) }} Coins)</option>
                </select>
            </div>

            <!-- Coins Input -->
            <div style="margin-bottom: 1.2rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.4rem;">
                    <label style="font-size: 0.85rem; color: var(--text-muted); font-weight: 700;">Coins Amount (Min 5)</label>
                    <span id="maxBalanceText" style="font-size: 0.8rem; color: var(--gold-light); font-weight: 700; cursor: pointer;" onclick="setMaxCoins()">Max: {{ number_format($user->working_coin_balance + $user->coin_balance, 2) }}</span>
                </div>
                <input type="number" step="0.1" id="coinsInput" name="coins_amount" class="form-control-funearn" placeholder="Enter coins (e.g. 10)" min="5" max="{{ $user->working_coin_balance + $user->coin_balance }}" oninput="calculatePayout()" required>
            </div>

            <!-- Wallet Address -->
            <div style="margin-bottom: 1.2rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                    <label style="font-size: 0.85rem; color: var(--text-muted); font-weight: 700;">USDT BEP20 Wallet Address</label>
                    <div style="display: flex; gap: 4px;">
                        <button type="button" onclick="autoFillSavedWallet()" style="background: rgba(245, 184, 46, 0.15); border: 1px solid var(--border-gold); color: var(--gold-light); font-size: 0.72rem; font-weight: 700; padding: 2px 7px; border-radius: 6px; cursor: pointer;">
                            <i class="fa-solid fa-history"></i> Saved Address
                        </button>
                        <button type="button" onclick="connectUserMetaMask()" style="background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; color: #10b981; font-size: 0.72rem; font-weight: 700; padding: 2px 7px; border-radius: 6px; cursor: pointer;">
                            <i class="fa-solid fa-wallet"></i> MetaMask
                        </button>
                    </div>
                </div>
                <input type="text" id="userWalletInput" name="wallet_address" class="form-control-funearn" style="font-family: monospace; font-size: 0.9rem;" placeholder="0x..." value="{{ old('wallet_address', $user->usdt_wallet_address) }}" required>
            </div>

            <!-- Calculation Box -->
            <div style="background: rgba(8, 10, 15, 0.75); border: 1px solid var(--border-gold); padding: 1.1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.85rem;">
                <div style="display: flex; justify-content: space-between; padding-bottom: 6px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span style="color: var(--text-muted);">Conversion Rate:</span> <strong>$0.10 per coin</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span style="color: var(--text-muted);">Gross USD Amount:</span> <strong id="grossText">$0.00 USD</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span style="color: var(--text-muted);">10% Fee Deduction:</span> <strong id="feeText" style="color: #ef4444;">-$0.00 USD</strong>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 1rem; padding-top: 8px; font-weight: 800;">
                    <span style="color: #fff;">Net USDT You Receive:</span> <strong id="netText" style="color: var(--gold-light);">$0.00 USDT</strong>
                </div>
            </div>

            <button type="submit" class="btn-gold" style="width: 100%; justify-content: center; padding: 0.85rem; font-weight: 800;"><i class="fa-solid fa-money-bill-transfer"></i> SUBMIT WITHDRAWAL REQUEST</button>
        </form>
    </div>

    <!-- Right Column: Terms & Rules -->
    <div class="funearn-card">
        <h3 style="color: var(--gold-light); margin-bottom: 1.2rem; font-size: 1.15rem;">
            <i class="fa-solid fa-circle-info"></i> Terms & Withdrawal Rules
        </h3>

        <div style="background: rgba(8, 10, 15, 0.75); border: 1px solid var(--border-gold); padding: 1.2rem; border-radius: 14px; margin-bottom: 1.2rem;">
            <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                <span style="color: var(--text-muted);">Minimum Withdrawal:</span> <strong style="color: var(--gold-light);">5 Coins ($0.50 USD)</strong>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                <span style="color: var(--text-muted);">Conversion Rate:</span> <strong>1 Coin = $0.10 USDT</strong>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                <span style="color: var(--text-muted);">Service / Gas Fee:</span> <strong style="color: #ef4444;">10% Deduction</strong>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 6px 0;">
                <span style="color: var(--text-muted);">Blockchain Network:</span> <strong style="color: #38bdf8;">BEP20 (Binance Smart Chain)</strong>
            </div>
        </div>

        <h4 style="color: #fff; font-size: 0.95rem; margin-top: 1rem; margin-bottom: 0.6rem;"><i class="fa-solid fa-list-check" style="color: var(--gold-light);"></i> Example Calculation:</h4>
        <ul style="color: var(--text-muted); font-size: 0.86rem; padding-left: 1.2rem; line-height: 1.7; margin: 0;">
            <li>Withdrawing <strong>10 Coins</strong> @ $0.10 = $1.00 Gross Value.</li>
            <li>10% Fee Deduction ($0.10) = <strong>$0.90 USDT Net Payout</strong>.</li>
            <li>Payouts are processed directly via Web3 Smart Contract to your BEP20 address.</li>
            <li>Make sure your BEP20 wallet address is correct. Crypto payouts cannot be reversed once broadcasted.</li>
        </ul>
    </div>

</div>

<!-- History Table -->
<div class="funearn-card">
    <h3 style="color: var(--gold-light); margin-bottom: 1.2rem; font-size: 1.15rem;"><i class="fa-solid fa-clock-rotate-left"></i> Withdrawal History</h3>
    
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Wallet Source</th>
                    <th>Coins Requested</th>
                    <th>Rate</th>
                    <th>Gross USD</th>
                    <th>10% Fee</th>
                    <th>Net USDT Payout</th>
                    <th>Wallet Address</th>
                    <th>Blockchain TX</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($withdrawals as $w)
                    <tr>
                        <td>
                            <div style="font-size: 0.85rem; font-weight: 600; color: #fff;">{{ $w->created_at->format('M d, Y') }}</div>
                            <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $w->created_at->format('h:i A') }}</div>
                        </td>
                        <td>
                            <span class="badge-status badge-active" style="font-size: 0.7rem; background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid #38bdf8;">
                                {{ str_replace('Wallet Source: ', '', $w->admin_note ?? 'ALL') }}
                            </span>
                        </td>
                        <td style="color: var(--gold-light); font-weight: 800; font-size: 0.95rem;">
                            {{ number_format($w->coins_amount, 2) }} Coins
                        </td>
                        <td style="font-size: 0.82rem; color: var(--text-muted);">${{ number_format($w->conversion_rate, 2) }}</td>
                        <td style="font-size: 0.85rem; color: #fff;">${{ number_format($w->gross_usd, 2) }}</td>
                        <td style="color: #ef4444; font-size: 0.85rem;">-${{ number_format($w->deduction_usd, 2) }}</td>
                        <td style="color: var(--gold-light); font-weight: 800; font-size: 0.95rem;">
                            ${{ number_format($w->net_usd, 2) }} USDT
                        </td>
                        <td style="font-family: monospace; font-size: 0.82rem; color: var(--text-muted);">
                            {{ Str::limit($w->wallet_address, 14) }}
                        </td>
                        <td>
                            @if($w->tx_hash)
                                <a href="https://bscscan.com/tx/{{ $w->tx_hash }}" target="_blank" style="color: #38bdf8; font-family: monospace; font-size: 0.78rem; text-decoration: underline;" title="{{ $w->tx_hash }}">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i> {{ Str::limit($w->tx_hash, 10) }}
                                </a>
                            @else
                                <span style="font-size: 0.78rem; color: var(--text-muted);">-</span>
                            @endif
                        </td>
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
                        <td colspan="10" style="text-align: center; color: var(--text-muted); padding: 2rem;">No withdrawal history found.</td>
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
    const workingBal = {{ (float) $user->working_coin_balance }};
    const roiBal = {{ (float) $user->coin_balance }};
    const totalBal = {{ (float) ($user->working_coin_balance + $user->coin_balance) }};
    const savedWallet = "{{ $user->usdt_wallet_address ?? '' }}";

    function updateMaxBalance() {
        const type = document.getElementById('walletTypeSelect').value;
        let maxVal = totalBal;
        if (type === 'working') maxVal = workingBal;
        if (type === 'roi') maxVal = roiBal;

        document.getElementById('coinsInput').max = maxVal;
        document.getElementById('maxBalanceText').innerText = 'Max: ' + maxVal.toFixed(2);
        calculatePayout();
    }

    function setMaxCoins() {
        const type = document.getElementById('walletTypeSelect').value;
        let maxVal = totalBal;
        if (type === 'working') maxVal = workingBal;
        if (type === 'roi') maxVal = roiBal;

        document.getElementById('coinsInput').value = maxVal.toFixed(2);
        calculatePayout();
    }

    function calculatePayout() {
        const coins = parseFloat(document.getElementById('coinsInput').value) || 0;
        const rate = 0.10;
        const gross = coins * rate;
        const fee = gross * 0.10;
        const net = gross - fee;

        document.getElementById('grossText').innerText = '$' + gross.toFixed(2) + ' USD';
        document.getElementById('feeText').innerText = '-$' + fee.toFixed(2) + ' USD';
        document.getElementById('netText').innerText = '$' + net.toFixed(2) + ' USDT';
    }

    function copyContractAddress() {
        const addr = document.getElementById('contractAddressText').innerText.trim();
        navigator.clipboard.writeText(addr).then(() => {
            alert('Official Smart Contract Address copied to clipboard!');
        }).catch(() => {
            alert('Contract Address: ' + addr);
        });
    }

    function autoFillSavedWallet() {
        if (!savedWallet) {
            alert('No saved wallet address found in your profile yet. Enter your BEP20 address below or connect MetaMask.');
            return;
        }
        document.getElementById('userWalletInput').value = savedWallet;
    }

    async function connectUserMetaMask() {
        if (typeof window.ethereum === 'undefined') {
            alert('MetaMask or Web3 wallet is not installed in your browser. Please install MetaMask to connect your wallet.');
            return;
        }

        try {
            const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
            if (accounts && accounts[0]) {
                document.getElementById('userWalletInput').value = accounts[0];
                alert('Wallet address connected: ' + accounts[0]);
            }
        } catch (err) {
            console.error("User wallet connection error:", err);
            alert("Could not connect wallet: " + err.message);
        }
    }
</script>
@endsection
