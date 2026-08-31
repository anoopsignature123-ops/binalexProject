@extends('admin.layouts.master')

@section('title', 'Token Pricing')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
    <div>
        <p class="text-sm font-semibold text-emerald-500 uppercase tracking-wider">Platform Settings</p>
        <h1 class="text-2xl lg:text-3xl font-bold text-text mt-2">Token Pricing</h1>
        <p class="text-muted mt-1">Set the pool value and token allocation used by the platform rate engine.</p>
    </div>
    <span class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-emerald-500/10 text-emerald-400 text-xs font-semibold"><i data-lucide="zap" class="w-4 h-4"></i>Live calculation</span>
</div>

@if (session('success'))
    <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-sm text-emerald-400">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5 lg:gap-6">
    <section class="xl:col-span-2 rounded-2xl bg-panel border border-border p-5 sm:p-8">
        @if ($errors->any())
            <div class="mb-5 rounded-xl border border-red-500/30 bg-red-500/10 p-4 text-sm text-red-400">@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
        @endif
        <form action="{{ route('admin.token-pricing.update') }}" method="post" class="space-y-6">
            @csrf
            @method('put')
            <div>
                <label for="total_pool_usd" class="block text-sm font-medium text-text mb-2">Total pool value (USD)</label>
                <div class="relative"><span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted">$</span><input id="total_pool_usd" name="total_pool_usd" type="number" min="0.00000001" step="0.00000001" value="{{ old('total_pool_usd', $settings->total_pool_usd) }}" required class="w-full pl-9 pr-4 py-3 rounded-xl bg-bg border border-border text-text placeholder:text-muted focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20" placeholder="1000000"></div>
                <p class="text-xs text-muted mt-2">Example: $1,000,000 total platform valuation.</p>
            </div>
            <div>
                <label for="total_allocated_tokens" class="block text-sm font-medium text-text mb-2">Total allocated tokens</label>
                <div class="relative"><i data-lucide="coins" class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-muted"></i><input id="total_allocated_tokens" name="total_allocated_tokens" type="number" min="0.00000001" step="0.00000001" value="{{ old('total_allocated_tokens', $settings->total_allocated_tokens) }}" required class="w-full pl-11 pr-4 py-3 rounded-xl bg-bg border border-border text-text placeholder:text-muted focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20" placeholder="1000000000"></div>
                <p class="text-xs text-muted mt-2">Example: 1,000,000,000 tokens allocated in the pool.</p>
            </div>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/20 transition hover:-translate-y-0.5"><i data-lucide="save" class="h-4 w-4"></i>Save pricing</button>
        </form>
    </section>

    <aside class="rounded-2xl bg-panel border border-border p-5 sm:p-6">
        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-400">Current token rate</p>
        <p class="text-sm text-muted mt-5">1 USD equals</p>
        <p id="tokensPerUsd" class="text-3xl font-bold text-text mt-1">{{ number_format((float) $settings->tokens_per_usd, 8) }}</p>
        <p class="text-sm text-emerald-400 mt-1">Binalex tokens</p>
        <div class="h-px bg-border my-6"></div>
        <p class="text-sm text-muted">1 token equals</p>
        <p id="usdPerToken" class="text-xl font-bold text-text mt-1">${{ number_format((float) $settings->usd_per_token, 12) }}</p>
        <p class="text-xs text-muted mt-5">Rate formula: allocated tokens / total pool value.</p>
    </aside>
</div>

<script>
    const poolInput = document.getElementById('total_pool_usd');
    const tokensInput = document.getElementById('total_allocated_tokens');
    const tokensPerUsd = document.getElementById('tokensPerUsd');
    const usdPerToken = document.getElementById('usdPerToken');
    const updateRate = () => {
        const pool = Number(poolInput.value);
        const tokens = Number(tokensInput.value);
        const rate = pool > 0 ? tokens / pool : 0;
        const inverse = tokens > 0 ? pool / tokens : 0;
        tokensPerUsd.textContent = rate.toLocaleString(undefined, { maximumFractionDigits: 8 });
        usdPerToken.textContent = `$${inverse.toLocaleString(undefined, { maximumFractionDigits: 12 })}`;
    };
    poolInput.addEventListener('input', updateRate);
    tokensInput.addEventListener('input', updateRate);
</script>
@endsection
