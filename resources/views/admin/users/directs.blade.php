@extends('admin.layouts.app')

@section('title', 'Direct Referrals of ' . $user->name)

@section('content')
<style>
    .action-icon-btn {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: 1px solid var(--border-gold);
        background: rgba(8, 10, 15, 0.8);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--gold-light);
        cursor: pointer;
        text-decoration: none;
        transition: all 0.25s ease;
        font-size: 0.85rem;
    }
    .action-icon-btn:hover {
        background: rgba(245, 184, 46, 0.2);
        border-color: var(--gold-primary);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(245, 184, 46, 0.25);
    }
    .action-icon-btn.cyan-btn { color: #38bdf8; border-color: rgba(56, 189, 248, 0.3); }
    .action-icon-btn.door-btn { color: #f59e0b; border-color: rgba(245, 158, 11, 0.3); }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-users"></i> Direct Downline Referrals</h2>
        <p style="color: var(--text-muted);">Inspect all direct referrals sponsored by <strong>{{ $user->name }}</strong> ({{ $user->referral_code }}).</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.users.index') }}" class="btn-gold" style="background: rgba(255,255,255,0.1); color: #fff;"><i class="fa-solid fa-arrow-left"></i> All Users</a>
        <a href="{{ route('admin.users.tree', $user->id) }}" class="btn-gold"><i class="fa-solid fa-sitemap"></i> View Tree</a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Direct User Profile</th>
                    <th>Referral Code</th>
                    <th>USDT Balance</th>
                    <th>Coin Balance</th>
                    <th>Staked USD</th>
                    <th>Sub-Directs</th>
                    <th>Status</th>
                    <th>Joined Date</th>
                    <th>Direct Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($directs as $d)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="user-profile-avatar">
                                    {{ strtoupper(substr($d->name ?? $d->email, 0, 1)) }}
                                </div>
                                <div>
                                    <strong style="color: var(--text-main); font-size: 0.95rem;">{{ $d->name }}</strong>
                                    <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $d->email }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);"><i class="fa-solid fa-phone"></i> {{ $d->mobile }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <strong style="color: var(--gold-light); font-size: 0.95rem;">{{ $d->referral_code }}</strong>
                            <i class="fa-solid fa-copy copy-icon-btn" title="Copy Referral Code" onclick="copyToClipboard('{{ $d->referral_code }}', 'User Referral Code')"></i>
                        </td>
                        <td style="color: var(--gold-light); font-weight: 700;">${{ number_format($d->usdt_balance, 2) }}</td>
                        <td style="color: var(--gold-light); font-weight: 700;">{{ number_format($d->coin_balance, 2) }} Coins</td>
                        <td>${{ number_format($d->total_staked_usd, 2) }}</td>
                        <td>{{ $d->directs_count }} Directs</td>
                        <td><span class="badge-status badge-{{ $d->status }}">{{ strtoupper($d->status) }}</span></td>
                        <td>{{ $d->created_at->format('M d, Y') }}</td>
                        <td>
                            <div style="display: flex; gap: 6px; align-items: center;">
                                <a href="{{ route('admin.users.edit', $d->id) }}" class="action-icon-btn" title="View Details"><i class="fa-solid fa-eye"></i></a>
                                <a href="{{ route('admin.users.tree', $d->id) }}" class="action-icon-btn" title="View Tree"><i class="fa-solid fa-sitemap"></i></a>
                                <a href="{{ route('admin.users.directs', $d->id) }}" class="action-icon-btn cyan-btn" title="View Sub-Directs"><i class="fa-solid fa-user-group"></i></a>
                                <a href="{{ route('admin.users.edit', $d->id) }}" class="action-icon-btn" title="Edit User"><i class="fa-solid fa-pen-to-square"></i></a>
                                <form action="{{ route('admin.users.impersonate', $d->id) }}" method="POST" style="margin: 0;" target="_blank" data-confirm="Log in to {{ $d->name }}'s User Portal in a NEW TAB?">
                                    @csrf
                                    <button type="submit" class="action-icon-btn door-btn" title="Login into User Portal in New Tab"><i class="fa-solid fa-door-open"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; color: var(--text-muted);">User {{ $user->name }} has no direct downline referrals yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $directs->links() }}
</div>
@endsection
