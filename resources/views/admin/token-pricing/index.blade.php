@extends('admin.layouts.master')

@section('title', 'Token Pricing Management')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6">
    <div>
        <p class="text-sm font-semibold text-emerald-500 uppercase tracking-wider">Ecosystem Setup</p>
        <h1 class="text-2xl lg:text-3xl font-bold text-text mt-2">Token Pricing & Pool Rate</h1>
        <p class="text-muted mt-1">Manage total pool valuation and dynamic coin conversion rates.</p>
    </div>
</div>

@if (session('success'))
    <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-sm text-emerald-400 mb-6">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Card -->
    <div class="lg:col-span-2 rounded-2xl bg-panel border border-border p-6">
        <h3 class="text-lg font-semibold text-text mb-4">Configure Pool & Valuation</h3>
        
        <form action="{{ route('admin.token-pricing.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-text mb-2">Total Pool USD ($)</label>
                    <input type="number" step="0.01" name="total_pool_usd" value="{{ old('total_pool_usd', $pricing->total_pool_usd) }}" required
                        class="w-full px-4 py-3 rounded-xl bg-bg border border-border text-text focus:outline-none focus:border-accent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-text mb-2">Total Pool Coins / Tokens</label>
                    <input type="number" step="0.01" name="total_pool_coins" value="{{ old('total_pool_coins', $pricing->total_pool_coins) }}" required
                        class="w-full px-4 py-3 rounded-xl bg-bg border border-border text-text focus:outline-none focus:border-accent">
                </div>

                <div class="pt-4">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold shadow-lg shadow-emerald-500/20 hover:-translate-y-0.5 transition">
                        <i data-lucide="save" class="w-4 h-4"></i> Update Rates
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Live Stats Card -->
    <div class="rounded-2xl bg-panel border border-border p-6 flex flex-col justify-between">
        <div>
            <h3 class="text-lg font-semibold text-text mb-4">Current Rate Status</h3>
            <div class="space-y-4">
                <div class="p-4 rounded-xl bg-bg border border-border">
                    <p class="text-xs text-muted">Calculated Coin Rate</p>
                    <p class="text-xl font-bold text-emerald-400 mt-1">${{ number_format($pricing->coin_rate_usd, 6) }} <span class="text-xs text-muted font-normal">/ Coin</span></p>
                </div>
                <div class="p-4 rounded-xl bg-bg border border-border">
                    <p class="text-xs text-muted">1 USD ($1) Value</p>
                    <p class="text-xl font-bold text-text mt-1">{{ number_format($pricing->coin_rate_usd > 0 ? 1 / $pricing->coin_rate_usd : 0, 2) }} <span class="text-xs text-muted font-normal">Coins</span></p>
                </div>
            </div>
        </div>
        <div class="mt-6 text-xs text-muted border-t border-border pt-4">
            <i data-lucide="info" class="w-4 h-4 inline mr-1"></i> This rate automatically applies when users invest or buy packages.
        </div>
    </div>
</div>
@endsection