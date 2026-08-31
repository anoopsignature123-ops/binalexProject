@extends('admin.layouts.app')

@section('title', 'Token Price & Supply Settings')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-sliders"></i> Token Pricing & Supply Settings</h2>
        <p style="color: var(--text-muted);">Manage real-time BINALEX Coin price rate, total supply pool ($300,000 USD Valuation in 1.5 Crore Coins).</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
    <div class="card" style="margin-bottom: 0; background: linear-gradient(145deg, rgba(245, 184, 46, 0.15) 0%, rgba(16, 22, 34, 0.95) 100%);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Live BINALEX Rate</div>
            <span class="live-pulse-badge"><span class="live-pulse-dot"></span> LIVE TICKER</span>
        </div>
        <div style="font-size: 2rem; font-weight: 800; color: var(--gold-light); margin: 0.4rem 0;">
            ${{ number_format($pricing->coin_rate_usd, 4) }} <span style="font-size: 1rem;">USD</span>
        </div>
        <div style="font-size: 0.8rem; color: var(--text-muted);">Current live exchange rate per BINALEX Coin.</div>
    </div>

    <div class="card" style="margin-bottom: 0;">
        <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Total Token Supply Pool</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: #fff; margin: 0.4rem 0;">
            {{ number_format($pricing->total_pool_coins, 0) }} <span style="font-size: 0.9rem; color: var(--gold-light);">BINALEX (1.5 Crore)</span>
        </div>
        <div style="font-size: 0.8rem; color: var(--text-muted);">System total backing coin allocation pool.</div>
    </div>

    <div class="card" style="margin-bottom: 0;">
        <div style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Total Valuation Pool (USD)</div>
        <div style="font-size: 1.8rem; font-weight: 800; color: #34d399; margin: 0.4rem 0;">
            ${{ number_format($pricing->total_pool_usd, 2) }} <span style="font-size: 1rem;">USD</span>
        </div>
        <div style="font-size: 0.8rem; color: var(--text-muted);">Backing valuation pool ($300,000 USD).</div>
    </div>
</div>

<div class="card" style="max-width: 650px;">
    <h3 style="color: var(--gold-light); margin-bottom: 1rem; font-size: 1.2rem;"><i class="fa-solid fa-gear"></i> Update Token Pricing & Valuation Pool</h3>

    <form action="{{ route('admin.settings.update-pricing') }}" method="POST" data-confirm="Update system BINALEX coin exchange rate & token pool values?">
        @csrf
        <div style="margin-bottom: 1.2rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 6px;">Total Capital Pool / Raised ($ USD)</label>
            <input type="number" step="0.01" id="inputPoolUsd" name="total_pool_usd" value="{{ old('total_pool_usd', $pricing->total_pool_usd) }}" required style="width: 100%; padding: 0.75rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: #fff; outline: none;" oninput="autoCalculateRate()">
            <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 4px;">Default Backing Valuation: $300,000 USD</div>
        </div>

        <div style="margin-bottom: 1.2rem;">
            <label style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 6px;">Total Supply Pool (Coins)</label>
            <input type="number" step="1" id="inputPoolCoins" name="total_pool_coins" value="{{ old('total_pool_coins', $pricing->total_pool_coins) }}" required style="width: 100%; padding: 0.75rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: #fff; outline: none;" oninput="autoCalculateRate()">
            <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 4px;">Default Pool Supply: 1.5 Crore Coins (15,000,000 Coins)</div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label style="font-size: 0.85rem; color: var(--gold-light); font-weight: 700; display: block; margin-bottom: 6px;">BINALEX Coin Rate in USD ($) (Auto-Calculated Rate)</label>
            <input type="number" step="0.0001" id="inputCoinRateUsd" name="coin_rate_usd" value="{{ old('coin_rate_usd', $pricing->coin_rate_usd) }}" required style="width: 100%; padding: 0.75rem 1rem; background: rgba(8,10,15,0.9); border: 1px solid var(--gold-primary); border-radius: 10px; color: var(--gold-light); font-weight: 800; outline: none;">
            <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 4px;">Calculated Rate: $300,000 USD ÷ 15,000,000 Coins = <strong>$0.0200 USD per Coin</strong></div>
        </div>

        <button type="submit" class="btn-gold" style="width: 100%; justify-content: center; padding: 0.85rem;"><i class="fa-solid fa-floppy-disk"></i> Save Settings & Broadcast Live Rate</button>
    </form>
</div>

<script>
    function autoCalculateRate() {
        const usd = parseFloat(document.getElementById('inputPoolUsd').value) || 0;
        const coins = parseFloat(document.getElementById('inputPoolCoins').value) || 0;
        if (usd > 0 && coins > 0) {
            const rate = usd / coins;
            document.getElementById('inputCoinRateUsd').value = rate.toFixed(4);
        }
    }
</script>
@endsection
