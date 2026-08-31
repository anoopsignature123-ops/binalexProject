@extends('admin.layouts.app')

@section('title', 'Edit User #' . $user->id)

@section('content')
<h2 style="color: var(--gold-light); margin-bottom: 0.5rem;"><i class="fa-solid fa-user-pen"></i> Edit User: {{ $user->name }}</h2>
<p style="color: var(--text-muted); margin-bottom: 2rem;">Manage member profile details, mobile number, security password, and view downline team performance metrics.</p>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
    <!-- Edit Profile Form -->
    <div class="card">
        <h3 style="color: var(--gold-light); margin-bottom: 1.2rem;"><i class="fa-solid fa-user-gear"></i> Account & Profile Information</h3>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--gold-light); font-weight: 700;">FULL NAME *</label>
                <input type="text" name="name" style="width: 100%; padding: 0.75rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: #fff; font-size: 0.9rem;" value="{{ old('name', $user->name) }}" required>
            </div>

            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--gold-light); font-weight: 700;">MOBILE NUMBER *</label>
                <input type="text" name="mobile" style="width: 100%; padding: 0.75rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: #fff; font-size: 0.9rem;" value="{{ old('mobile', $user->mobile) }}" required>
            </div>

            <div style="margin-bottom: 1.2rem;">
                <label style="display: block; margin-bottom: 0.4rem; font-size: 0.85rem; color: var(--gold-light); font-weight: 700;">RESET PASSWORD (Optional)</label>
                <input type="password" name="password" style="width: 100%; padding: 0.75rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 10px; color: #fff; font-size: 0.9rem;" placeholder="Leave empty to keep current password">
            </div>

            <!-- Read-Only Account Status Display (Activated ONLY on package purchase!) -->
            <div style="margin-bottom: 1.5rem; background: rgba(8,10,15,0.8); border: 1px solid var(--border-gold); padding: 1rem; border-radius: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.85rem; color: var(--gold-light); font-weight: 700;">ACCOUNT ACTIVATION STATUS</span>
                    <span class="badge-status badge-{{ $user->status }}">{{ strtoupper($user->status) }}</span>
                </div>
                <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 6px;">
                    <i class="fa-solid fa-shield-halved"></i> Account status activates automatically when the user purchases Staking Package A, B, or C.
                </div>
            </div>

            <button type="submit" class="btn-gold" style="width: 100%; justify-content: center;"><i class="fa-solid fa-floppy-disk"></i> UPDATE USER PROFILE</button>
        </form>
    </div>

    <!-- Wallet Balances & Downline Summary -->
    <div class="card">
        <h3 style="color: var(--gold-light); margin-bottom: 1.2rem;"><i class="fa-solid fa-wallet"></i> Wallet Balances & Quick Actions</h3>

        <!-- Deposit & Earning Wallet Info -->
        <div style="background: rgba(8,10,15,0.8); border: 1px solid var(--border-gold); padding: 1.2rem; border-radius: 14px; margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 10px; border-bottom: 1px solid rgba(255,255,255,0.08);">
                <span style="font-size: 0.88rem; color: var(--text-muted);">Deposit Wallet Balance:</span>
                <strong style="color: #34d399; font-size: 1.1rem;">${{ number_format($user->usdt_balance, 2) }} USDT</strong>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 10px;">
                <span style="font-size: 0.88rem; color: var(--text-muted);">Earning & ROI Wallets:</span>
                <strong style="color: var(--gold-light); font-size: 1.1rem;">{{ number_format($user->coin_balance + $user->working_coin_balance, 2) }} Coins</strong>
            </div>

            <div style="margin-top: 1.2rem; text-align: center;">
                <a href="{{ route('admin.deposits.index') }}" class="btn-gold" style="width: 100%; justify-content: center; font-size: 0.85rem;"><i class="fa-solid fa-plus-circle"></i> Add / Credit USDT Funds to User</a>
            </div>
        </div>

        <h3 style="color: var(--gold-light); margin-bottom: 1.2rem; font-size: 1.1rem;"><i class="fa-solid fa-chart-pie"></i> Downline & Leg Volumes</h3>

        @php $userRefLink = route('user.register', ['ref' => $user->referral_code]); @endphp
        <div style="background: rgba(245, 184, 46, 0.08); border: 1px solid var(--border-gold); padding: 1rem; border-radius: 12px; margin-bottom: 1.2rem;">
            <div style="font-size: 0.8rem; color: var(--text-muted);">Shareable User Registration Link</div>
            <div style="display: flex; gap: 8px; align-items: center; margin-top: 6px;">
                <input type="text" value="{{ $userRefLink }}" readonly style="width: 100%; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); padding: 6px 10px; border-radius: 8px; color: var(--gold-light); font-size: 0.85rem;">
                <button class="btn-gold" onclick="copyToClipboard('{{ $userRefLink }}', 'Shareable Referral Link')" style="padding: 6px 14px; font-size: 0.8rem;"><i class="fa-solid fa-copy"></i> Copy</button>
            </div>
        </div>

        <div style="background: rgba(8,10,15,0.7); padding: 1rem; border-radius: 12px; margin-bottom: 1rem; font-size: 0.9rem;">
            <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                <span>Referral Code:</span> <strong style="color: var(--gold-light);">{{ $user->referral_code }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                <span>Sponsor:</span> <strong>{{ $user->sponsor ? ($user->sponsor->name ?? $user->sponsor->email) : 'None' }}</strong>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                <span>Power Leg Volume:</span> <strong style="color: var(--gold-light);">${{ number_format($volumes['power_leg'], 2) }} USD</strong>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 6px 0;">
                <span>Other Legs Volume:</span> <strong style="color: var(--gold-light);">${{ number_format($volumes['other_legs'], 2) }} USD</strong>
            </div>
        </div>

        <h4 style="color: var(--gold-light); margin-bottom: 0.5rem; font-size: 0.95rem;">Active Stakes ({{ $user->stakes->count() }})</h4>
        <ul style="list-style: none; padding: 0; font-size: 0.85rem; color: var(--text-muted);">
            @forelse($user->stakes as $s)
                <li style="padding: 6px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <strong>{{ $s->package_name }}</strong>: ${{ number_format($s->amount_usd, 2) }} ({{ $s->earned_coins }}/{{ $s->total_yield_coins }} Coins) - <span class="badge-status badge-{{ $s->status }}">{{ strtoupper($s->status) }}</span>
                </li>
            @empty
                <li>No stakes found for this user.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
