@extends('user.layouts.app')

@section('title', 'Staking Packages')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-layer-group"></i> Self Stake Coin Packages</h2>
        <p style="color: var(--text-muted);">Enter any custom USD amount or choose a preset package to receive real-time BINALEX Coins instantly.</p>
    </div>

    @php
        $pricing = \App\Models\TokenPricing::first();
        $rate = $pricing ? (float) $pricing->coin_rate_usd : 0.0200;
    @endphp

    <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
        <!-- Available Wallet Balance Badges -->
        <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid #10b981; padding: 0.65rem 1.1rem; border-radius: 14px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-wallet" style="color: #34d399;"></i>
            <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">USDT DEPOSIT:</span>
            <strong style="color: #34d399; font-size: 1.1rem;">${{ number_format($user->usdt_balance, 2) }} USDT</strong>
        </div>

        <div style="background: rgba(245,184,46,0.12); border: 1px solid var(--border-gold); padding: 0.65rem 1.1rem; border-radius: 14px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-coins" style="color: var(--gold-light);"></i>
            <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">COIN BALANCE:</span>
            <strong style="color: var(--gold-light); font-size: 1.1rem;">{{ number_format($user->coin_balance, 2) }} Coins</strong>
        </div>

        <div style="background: rgba(14,18,28,0.9); border: 1px solid var(--gold-primary); padding: 0.65rem 1.1rem; border-radius: 14px; box-shadow: var(--gold-glow); display: flex; align-items: center; gap: 8px;">
            <span class="live-pulse-badge"><span class="live-pulse-dot"></span> LIVE RATE</span>
            <strong style="color: #34d399; font-size: 1.1rem;">${{ number_format($rate, 4) }} USD / Coin</strong>
        </div>
    </div>
</div>

<!-- Real-Time Custom Staking Box -->
<div class="card" style="border: 2px solid var(--gold-primary); margin-bottom: 2.5rem; box-shadow: var(--gold-glow); background: rgba(14, 18, 28, 0.95);">
    <h3 style="color: var(--gold-light); margin-bottom: 0.5rem;"><i class="fa-solid fa-calculator"></i> Real-Time Custom USD Investment Staking</h3>
    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Enter any USD amount ($2, $5, $10, $30, $50...) to immediately calculate real-time BINALEX coins and stake instantly!</p>

    <form id="customStakingForm" action="{{ route('user.staking.store') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.2rem; align-items: end;">
            <div>
                <label style="display: block; font-weight: 700; color: var(--gold-light); margin-bottom: 6px;">INVESTMENT AMOUNT ($ USD) *</label>
                <input type="number" id="userCustomUsd" name="amount_usd" class="form-control" value="2.00" min="0.1" step="0.1" required oninput="calculateUserLiveStaking()">
            </div>

            <div>
                <label style="display: block; font-weight: 700; color: var(--text-muted); margin-bottom: 6px;">REAL-TIME COINS CREDIT</label>
                <div style="background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); padding: 0.8rem 1rem; border-radius: 12px; font-weight: 900; color: var(--gold-light); font-size: 1.25rem;" id="userLiveCoinsOutput">100.00 COINS</div>
            </div>

            <div>
                <label style="display: block; font-weight: 700; color: var(--text-muted); margin-bottom: 6px;">DAILY ROI YIELD (1%)</label>
                <div style="background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); padding: 0.8rem 1rem; border-radius: 12px; font-weight: 800; color: #34d399; font-size: 1.15rem;" id="userLiveDailyOutput">1.00 Coins/Day</div>
            </div>

            <div>
                <button type="button" onclick="openStakingModal('custom')" class="btn-gold" style="width: 100%; justify-content: center; height: 48px; font-size: 1rem;"><i class="fa-solid fa-bolt"></i> STAKE NOW</button>
            </div>
        </div>
    </form>
</div>

<!-- Preset Packages Grid -->
<h3 style="color: var(--gold-light); margin-bottom: 1rem;"><i class="fa-solid fa-cubes"></i> Quick Preset Plans ($2, $4, $6)</h3>
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
    @foreach($packages as $pkg)
        <div class="card" style="border-width: 2px; text-align: center; position: relative;">
            <h3 style="color: var(--gold-light); font-size: 1.5rem;">{{ $pkg['name'] }}</h3>
            @php
                $planUsdTitle = $pkg['code'] === 'A' ? '$2' : ($pkg['code'] === 'B' ? '$4' : '$6');
            @endphp
            <div style="font-size: 2.5rem; font-weight: 800; color: var(--text-main); margin: 0.5rem 0;">{{ $planUsdTitle }} <span style="font-size: 1rem; color: var(--gold-light);">= {{ number_format($pkg['coins_staked'], 0) }} COINS</span></div>

            <div style="background: rgba(245,184,46,0.12); border: 1px solid var(--border-gold); padding: 0.5rem; border-radius: 12px; margin-bottom: 1.2rem; text-align: center;">
                <span style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 800; display: block; letter-spacing: 1px;">USD COST AT LIVE RATE (${{ number_format($rate, 4) }})</span>
                <strong style="color: var(--gold-light); font-size: 1.25rem; font-weight: 900;">${{ number_format($pkg['price_usd'], 2) }} USDT</strong>
            </div>

            <div style="background: rgba(8, 10, 15, 0.6); padding: 1rem; border-radius: 12px; margin-bottom: 1.2rem; text-align: left; font-size: 0.9rem;">
                <div style="display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span>Coins Staked:</span> <strong>{{ number_format($pkg['coins_staked'], 2) }} Coins</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span>Multiplier:</span> <strong style="color: var(--gold-light);">{{ $pkg['multiplier'] }}x Total Return</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span>Total Return:</span> <strong style="color: var(--gold-light);">{{ number_format($pkg['total_yield_coins'], 2) }} Coins</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 5px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <span>Daily Return:</span> <strong style="color: #34d399;">{{ number_format($pkg['daily_payout_coins'], 2) }} Coins/day</strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 5px 0;">
                    <span>Direct Referral Bonus:</span> <strong style="color: var(--gold-light);">{{ $pkg['direct_percent'] }}% ({{ number_format($pkg['direct_coin_reward'], 2) }} Coins)</strong>
                </div>
            </div>

            <form id="presetForm_{{ $pkg['code'] }}" action="{{ route('user.staking.store') }}" method="POST">
                @csrf
                <input type="hidden" name="package_code" value="{{ $pkg['code'] }}">
                <button type="button" onclick="openStakingModal('preset', '{{ $pkg['code'] }}', '{{ $pkg['name'] }}', {{ $pkg['price_usd'] }}, {{ $pkg['coins_staked'] }})" class="btn-gold" style="width: 100%; justify-content: center;"><i class="fa-solid fa-bolt"></i> STAKE {{ strtoupper($pkg['name']) }}</button>
            </form>
        </div>
    @endforeach
</div>

<!-- Active/History Stakes -->
<div class="card">
    <h3 style="color: var(--gold-light); margin-bottom: 1rem;"><i class="fa-solid fa-clock-rotate-left"></i> My Staking History</h3>
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Package</th>
                    <th>Staked Coins</th>
                    <th>Amount USD</th>
                    <th>Daily Payout</th>
                    <th>Progress (Days)</th>
                    <th>Earned Coins</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stakes as $stk)
                    <tr>
                        <td><strong style="color: var(--gold-light);">{{ $stk->package_name }}</strong></td>
                        <td><strong>{{ number_format($stk->coins_staked, 2) }} Coins</strong></td>
                        <td style="color: #34d399; font-weight: 800;">${{ number_format($stk->amount_usd, 2) }} USD</td>
                        <td style="color: #34d399;">{{ number_format($stk->daily_payout_coins, 2) }} Coins/day</td>
                        <td>{{ $stk->days_completed }} / {{ $stk->duration_days }} Days</td>
                        <td style="color: var(--gold-light); font-weight: 800;">{{ number_format($stk->earned_coins, 2) }} Coins</td>
                        <td>
                            @if($stk->status === 'active')
                                <span class="badge-status badge-active">ACTIVE</span>
                            @else
                                <span class="badge-status badge-completed">COMPLETED</span>
                            @endif
                        </td>
                        <td>{{ $stk->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-muted);">You have no active stakes yet. Activate a package above!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- High-End Web3 Glassmorphic Staking Confirmation Modal -->
<div id="stakingConfirmModal" style="display: none; position: fixed; top:0; left:0; right:0; bottom:0; background: rgba(7,9,14,0.85); backdrop-filter: blur(15px); z-index: 99999; align-items: center; justify-content: center; padding: 1rem;">
    <div class="card" style="max-width: 480px; width: 100%; border: 2px solid var(--gold-primary); box-shadow: var(--gold-glow); animation: popIn 0.3s ease; position: relative;">
        <div style="text-align: center; margin-bottom: 1.2rem;">
            <div style="width: 60px; height: 60px; background: var(--gold-gradient); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: #000; font-size: 1.8rem; margin-bottom: 0.8rem; box-shadow: var(--gold-glow);">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h3 style="color: #fff; font-size: 1.4rem; font-weight: 800;" id="modalTitle">CONFIRM STAKING TRANSACTION</h3>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 4px;">Review your transaction details before proceeding</p>
        </div>

        <div style="background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 16px; padding: 1.2rem; margin-bottom: 1.2rem; font-size: 0.92rem;">
            <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.06);">
                <span style="color: var(--text-muted);">Investment Plan:</span>
                <strong style="color: var(--gold-light);" id="modalPlanName">Custom Stake</strong>
            </div>

            <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.06);">
                <span style="color: var(--text-muted);">Required USDT Amount:</span>
                <strong style="color: #34d399;" id="modalRequiredUsd">$2.00 USDT</strong>
            </div>

            <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.06);">
                <span style="color: var(--text-muted);">Your Available USDT Balance:</span>
                <strong style="color: #fff;" id="modalUserBalance">${{ number_format($user->usdt_balance, 2) }} USDT</strong>
            </div>

            <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.06);">
                <span style="color: var(--text-muted);">BINALEX Coins Credited:</span>
                <strong style="color: var(--gold-light);" id="modalCoinsCredit">100.00 COINS</strong>
            </div>

            <div style="display: flex; justify-content: space-between; padding: 6px 0;">
                <span style="color: var(--text-muted);">Live Exchange Rate:</span>
                <strong style="color: #34d399;">${{ number_format($rate, 4) }} USD / Coin</strong>
            </div>
        </div>

        <!-- Balance Status Badge -->
        <div id="modalBalanceStatus" style="padding: 0.8rem; border-radius: 12px; text-align: center; font-size: 0.85rem; font-weight: 700; margin-bottom: 1.5rem;"></div>

        <div style="display: flex; gap: 12px;">
            <button type="button" onclick="closeStakingModal()" class="btn-outline" style="flex: 1; justify-content: center;"><i class="fa-solid fa-xmark"></i> CANCEL</button>
            <button type="button" onclick="submitStakingForm()" id="modalConfirmBtn" class="btn-gold" style="flex: 1; justify-content: center;"><i class="fa-solid fa-check-double"></i> CONFIRM & STAKE</button>
        </div>
    </div>
</div>

<script>
    const USER_LIVE_RATE = {{ $rate }};
    const USER_USDT_BALANCE = {{ (float)$user->usdt_balance }};
    let activeFormId = '';

    function calculateUserLiveStaking() {
        const usdInput = parseFloat(document.getElementById('userCustomUsd').value) || 0;
        const coins = usdInput / USER_LIVE_RATE;
        const dailyRoi = coins * 0.01;

        document.getElementById('userLiveCoinsOutput').innerText = coins.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' COINS';
        document.getElementById('userLiveDailyOutput').innerText = dailyRoi.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' Coins/Day';
    }

    function openStakingModal(type, code = '', name = '', price = 0, coins = 0) {
        let reqUsd = 0;
        let coinsCredited = 0;
        let planTitle = '';

        if (type === 'custom') {
            reqUsd = parseFloat(document.getElementById('userCustomUsd').value) || 0;
            if (reqUsd <= 0) {
                alert('Please enter a valid investment amount in USD.');
                return;
            }
            coinsCredited = reqUsd / USER_LIVE_RATE;
            planTitle = 'Custom Stake ($' + reqUsd.toFixed(2) + ')';
            activeFormId = 'customStakingForm';
        } else {
            reqUsd = price;
            coinsCredited = coins;
            planTitle = name;
            activeFormId = 'presetForm_' + code;
        }

        document.getElementById('modalPlanName').innerText = planTitle;
        document.getElementById('modalRequiredUsd').innerText = '$' + reqUsd.toFixed(2) + ' USDT';
        document.getElementById('modalCoinsCredit').innerText = coinsCredited.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' COINS';

        const statusBox = document.getElementById('modalBalanceStatus');
        const confirmBtn = document.getElementById('modalConfirmBtn');

        if (USER_USDT_BALANCE >= reqUsd) {
            statusBox.style.background = 'rgba(16, 185, 129, 0.15)';
            statusBox.style.border = '1px solid #10b981';
            statusBox.style.color = '#34d399';
            statusBox.innerHTML = '<i class="fa-solid fa-circle-check"></i> Sufficient USDT Balance Available! Transaction Ready.';
            confirmBtn.disabled = false;
            confirmBtn.style.opacity = '1';
        } else {
            statusBox.style.background = 'rgba(239, 68, 68, 0.15)';
            statusBox.style.border = '1px solid #ef4444';
            statusBox.style.color = '#f87171';
            statusBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Insufficient USDT Deposit Balance! Required: $' + reqUsd.toFixed(2) + ' USDT (Available: $' + USER_USDT_BALANCE.toFixed(2) + ' USDT).';
            confirmBtn.disabled = true;
            confirmBtn.style.opacity = '0.5';
        }

        const modal = document.getElementById('stakingConfirmModal');
        modal.style.display = 'flex';
    }

    function closeStakingModal() {
        const modal = document.getElementById('stakingConfirmModal');
        modal.style.display = 'none';
    }

    function submitStakingForm() {
        if (activeFormId) {
            document.getElementById(activeFormId).submit();
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        calculateUserLiveStaking();
    });
</script>
@endsection
