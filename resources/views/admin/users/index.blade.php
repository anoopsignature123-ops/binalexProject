@extends('admin.layouts.app')

@section('title', 'Manage Users')

@section('content')
<style>
    .action-icon-btn {
        width: 32px;
        height: 32px;
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
        font-size: 0.82rem;
    }
    .action-icon-btn:hover {
        background: rgba(245, 184, 46, 0.2);
        border-color: var(--gold-primary);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(245, 184, 46, 0.25);
    }
    .action-icon-btn.cyan-btn { color: #38bdf8; border-color: rgba(56, 189, 248, 0.3); }
    .action-icon-btn.cyan-btn:hover { background: rgba(56, 189, 248, 0.2); border-color: #38bdf8; color: #fff; }
    .action-icon-btn.door-btn { color: #f59e0b; border-color: rgba(245, 158, 11, 0.3); }
    .action-icon-btn.door-btn:hover { background: rgba(245, 158, 11, 0.2); border-color: #f59e0b; color: #fff; }
</style>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-users-gear"></i> User Management Module</h2>
        <p style="color: var(--text-muted);">Inspect registered accounts, genealogy trees, direct downlines, and user actions.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <a href="{{ route('admin.users.tree') }}" class="btn-gold" style="background: rgba(245, 184, 46, 0.15); color: var(--gold-light);"><i class="fa-solid fa-sitemap"></i> Genealogy Tree</a>
        <a href="{{ route('admin.users.create') }}" class="btn-gold"><i class="fa-solid fa-user-plus"></i> Add New User</a>
        <form action="{{ route('admin.users.index') }}" method="GET" style="display: flex; gap: 8px;">
            @if($status)<input type="hidden" name="status" value="{{ $status }}">@endif
            <input type="text" name="search" style="padding: 0.6rem 1rem; background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); border-radius: 8px; color: #fff;" placeholder="Search name, email, code..." value="{{ $search }}">
            <button type="submit" class="btn-gold" style="padding: 0.6rem 1rem;"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
        </form>
    </div>
</div>

<!-- Status Filter Tabs -->
<div style="display: flex; gap: 0.8rem; margin-bottom: 1.5rem;">
    <a href="{{ route('admin.users.index') }}" class="btn-gold" style="font-size: 0.8rem; {{ empty($status) ? '' : 'background: rgba(255,255,255,0.1); color: var(--text-main);' }}">All Users</a>
    <a href="{{ route('admin.users.index', ['status' => 'active']) }}" class="btn-gold" style="font-size: 0.8rem; {{ $status === 'active' ? '' : 'background: rgba(255,255,255,0.1); color: var(--text-main);' }}"><i class="fa-solid fa-user-check"></i> Active Users</a>
    <a href="{{ route('admin.users.index', ['status' => 'inactive']) }}" class="btn-gold" style="font-size: 0.8rem; {{ $status === 'inactive' ? '' : 'background: rgba(255,255,255,0.1); color: var(--text-main);' }}"><i class="fa-solid fa-user-clock"></i> Inactive Users</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>User Profile</th>
                    <th>Referral Code & Link</th>
                    <th>Sponsor Info</th>
                    <th>USDT Balance</th>
                    <th>Coin Balance</th>
                    <th>Staked USD</th>
                    <th>Registration Date & Time</th>
                    <th>Activation Date & Time</th>
                    <th>Status</th>
                    <th>Direct Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    @php
                        $refLink = route('user.register', ['ref' => $u->referral_code]);
                        $activationDate = $u->first_activation_date;
                    @endphp
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="user-profile-avatar">
                                    {{ strtoupper(substr($u->name ?? $u->email, 0, 1)) }}
                                </div>
                                <div>
                                    <strong style="color: var(--text-main); font-size: 0.95rem;">{{ $u->name }}</strong>
                                    <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $u->email }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);"><i class="fa-solid fa-phone"></i> {{ $u->mobile }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <strong style="color: var(--gold-light); font-size: 0.95rem;">{{ $u->referral_code }}</strong>
                            <i class="fa-solid fa-copy copy-icon-btn" title="Copy Referral Code" onclick="copyToClipboard('{{ $u->referral_code }}', 'User Referral Code')"></i>
                            <div style="margin-top: 4px;">
                                <span style="font-size: 0.75rem; color: var(--text-muted);"><i class="fa-solid fa-share-nodes"></i> Link: </span>
                                <i class="fa-solid fa-link copy-icon-btn" title="Copy Shareable Registration Link" onclick="copyToClipboard('{{ $refLink }}', 'Shareable Referral Link')"></i>
                            </div>
                        </td>
                        <td>
                            @if($u->sponsor)
                                <div><strong>{{ $u->sponsor->name ?? 'Sponsor' }}</strong></div>
                                <span style="font-size: 0.8rem; color: var(--gold-light);">{{ $u->sponsor->referral_code }}</span>
                                <i class="fa-solid fa-copy copy-icon-btn" title="Copy Sponsor Code" onclick="copyToClipboard('{{ $u->sponsor->referral_code }}', 'Sponsor Code')"></i>
                            @else
                                <span style="color: var(--text-muted);">No Sponsor</span>
                            @endif
                        </td>
                        <td style="color: var(--gold-light); font-weight: 700;">${{ number_format($u->usdt_balance, 2) }}</td>
                        <td style="color: var(--gold-light); font-weight: 700;">{{ number_format($u->coin_balance, 2) }} Coins</td>
                        <td>${{ number_format($u->total_staked_usd, 2) }}</td>
                        <td>
                            <div style="font-size: 0.82rem; font-weight: 600; color: #fff;">{{ $u->created_at->format('M d, Y') }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $u->created_at->format('h:i A') }}</div>
                        </td>
                        <td>
                            @if($activationDate)
                                <div style="font-size: 0.82rem; font-weight: 700; color: #34d399;">{{ $activationDate->format('M d, Y') }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $activationDate->format('h:i A') }}</div>
                            @else
                                <span class="badge-status badge-rejected" style="font-size: 0.68rem; padding: 2px 8px;">NOT ACTIVATED</span>
                            @endif
                        </td>
                        <td><span class="badge-status badge-{{ $u->status }}">{{ strtoupper($u->status) }}</span></td>
                        <td>
                            <!-- 5 Action Icon Buttons -->
                            <div style="display: flex; gap: 4px; align-items: center;">
                                {{-- 1. Eye Icon: View Details --}}
                                <a href="{{ route('admin.users.show', $u->id) }}" class="action-icon-btn" title="View Full User Details Profile">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                {{-- 2. Tree Icon: View Binary / Referral Tree Structure --}}
                                <a href="{{ route('admin.users.tree', $u->id) }}" class="action-icon-btn" title="View Referral Tree">
                                    <i class="fa-solid fa-sitemap"></i>
                                </a>

                                {{-- 3. Users Icon: View Direct Downline List --}}
                                <a href="{{ route('admin.users.directs', $u->id) }}" class="action-icon-btn cyan-btn" title="View Direct Downlines">
                                    <i class="fa-solid fa-user-group"></i>
                                </a>

                                {{-- 4. Edit Icon: Edit Account --}}
                                <a href="{{ route('admin.users.edit', $u->id) }}" class="action-icon-btn" title="Edit User Account">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                {{-- 5. Door Icon: Login / Impersonate User in NEW TAB --}}
                                <form action="{{ route('admin.users.impersonate', $u->id) }}" method="POST" style="margin: 0;" target="_blank" data-confirm="Log in to {{ $u->name }}'s User Portal in a NEW TAB?">
                                    @csrf
                                    <button type="submit" class="action-icon-btn door-btn" title="Login into User Portal in New Tab">
                                        <i class="fa-solid fa-door-open"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align: center; color: var(--text-muted);">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Custom Pagination Component Render -->
    {{ $users->links() }}
</div>
@endsection
