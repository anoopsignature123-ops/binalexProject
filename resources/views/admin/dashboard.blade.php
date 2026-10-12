@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <style>
        .admin-welcome-banner {
            background: linear-gradient(135deg, rgba(245, 184, 46, 0.15) 0%, rgba(12, 16, 26, 0.95) 100%);
            border: 1px solid var(--border-gold);
            border-radius: 20px;
            padding: 1.5rem 2rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        .admin-8-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.2rem;
            margin-bottom: 1.5rem;
        }

        .admin-mini-card {
            background: rgba(18, 24, 38, 0.85);
            border: 1px solid var(--border-gold);
            border-radius: 16px;
            padding: 1.2rem;
            transition: transform 0.25s ease, border-color 0.25s ease;
        }

        .admin-mini-card:hover {
            transform: translateY(-3px);
            border-color: var(--gold-light);
            box-shadow: var(--gold-glow);
        }

        .admin-card-icon {
            width: 42px;
            height: 42px;
            background: rgba(245, 184, 46, 0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-light);
            font-size: 1.2rem;
            margin-bottom: 0.8rem;
        }

        .admin-val {
            font-size: 1.45rem;
            font-weight: 800;
            color: #fff;
            margin: 0.2rem 0;
        }

        .admin-lbl {
            font-size: 0.76rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 700;
        }

        .admin-accent-line {
            height: 3px;
            background: var(--gold-gradient);
            border-radius: 2px;
            margin-top: 0.8rem;
        }

        .token-pool-banner {
            background: rgba(14, 18, 28, 0.95);
            border: 1px solid var(--gold-primary);
            border-radius: 18px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            box-shadow: var(--gold-glow);
        }

        .tables-2-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .user-profile-avatar {
            width: 36px;
            height: 36px;
            background: var(--gold-gradient);
            border: 1px solid var(--gold-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-weight: 800;
            font-size: 0.95rem;
            flex-shrink: 0;
            box-shadow: 0 0 10px rgba(245, 184, 46, 0.3);
        }

        @media (max-width: 1400px) {
            .admin-8-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .tables-2-col {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 650px) {
            .admin-8-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <!-- Welcome Header Banner -->
    <div class="admin-welcome-banner">
        <div>
            <div
                style="font-size: 0.75rem; font-weight: 800; color: var(--gold-light); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 4px;">
                ADMINISTRATOR CONTROL PANEL</div>
            <h2 style="color: #fff; font-size: 1.6rem; font-weight: 800; margin-bottom: 0.3rem;"><i
                    class="fa-solid fa-gauge" style="color: var(--gold-light);"></i> Platform Overview & System Metrics</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Real-time management summary for BINALEX.</p>
        </div>
        {{-- <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            <form action="{{ route('admin.process-roi') }}" method="POST"
                onsubmit="return confirm('Are you sure you want to process daily ROI payout for 1 day now?');"
                style="display: inline;">
                @csrf
                <button type="submit" class="btn-gold"
                    style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; color: #fff; font-weight: 700; box-shadow: 0 0 15px rgba(16, 185, 129, 0.4); font-size: 0.78rem; padding: 0.55rem 0.9rem; border-radius: 8px; cursor: pointer;">
                    <i class="fa-solid fa-bolt"></i> Daily ROI (1 Day)
                </button>
            </form>
            <form action="{{ route('admin.process-roi') }}" method="POST"
                onsubmit="return confirm('Process backdated ROI payouts for the specified number of days?');"
                style="display: inline-flex; align-items: center; gap: 4px; background: rgba(255,255,255,0.06); padding: 4px 8px; border-radius: 8px; border: 1px solid var(--border-gold);">
                @csrf
                <span style="font-size: 0.72rem; color: var(--text-muted); font-weight: 700;">DAYS:</span>
                <input type="number" name="days" value="2" min="1" max="300"
                    style="width: 52px; background: rgba(0,0,0,0.6); border: 1px solid var(--border-gold); color: var(--gold-light); font-weight: 800; border-radius: 4px; padding: 3px 5px; font-size: 0.78rem; text-align: center;"
                    title="Enter number of backdate days to credit">
                <button type="submit" class="btn-gold"
                    style="background: rgba(245,184,46,0.25); border: 1px solid var(--gold-primary); color: #fff; font-weight: 700; font-size: 0.75rem; padding: 0.4rem 0.7rem; border-radius: 6px; cursor: pointer;">
                    <i class="fa-solid fa-calendar-days"></i> Backdate ROI
                </button>
            </form>
            <form action="{{ route('admin.process-awards') }}" method="POST"
                onsubmit="return confirm('Scan and award rank rewards for all eligible users now?');"
                style="display: inline;">
                @csrf
                <button type="submit" class="btn-gold"
                    style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border: none; color: #fff; font-weight: 700; box-shadow: 0 0 15px rgba(59, 130, 246, 0.4); font-size: 0.78rem; padding: 0.55rem 0.9rem; border-radius: 8px; cursor: pointer;">
                    <i class="fa-solid fa-trophy"></i> Rank Awards
                </button>
            </form>
            <form action="{{ route('admin.process-all-crons') }}" method="POST"
                onsubmit="return confirm('Execute ALL daily background cron tasks (ROI + Rank Awards) right now?');"
                style="display: inline;">
                @csrf
                <button type="submit" class="btn-gold"
                    style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none; color: #000; font-weight: 800; box-shadow: 0 0 15px rgba(245, 158, 11, 0.4); font-size: 0.78rem; padding: 0.55rem 0.9rem; border-radius: 8px; cursor: pointer;">
                    <i class="fa-solid fa-rotate"></i> Run All Crons
                </button>
            </form>
        </div> --}}
    </div>

    <!-- 8 System Metric Cards Grid -->
    <div class="admin-8-grid">
        <!-- Card 1: Registered Users -->
        <div class="admin-mini-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div class="admin-card-icon"><i class="fa-solid fa-users"></i></div>
                <span class="badge-status badge-active" style="font-size: 0.65rem;">MEMBERS</span>
            </div>
            <div class="admin-lbl">Total Registered Users</div>
            <div class="admin-val">{{ number_format($totalUsers) }}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted);"><a href="{{ route('admin.users.index') }}"
                    style="color: var(--gold-light); text-decoration: underline;">{{ $activeUsersCount }} Active /
                    {{ $inactiveUsersCount }} Inactive</a></div>
            <div class="admin-accent-line"></div>
        </div>

        <!-- Card 2: Total Staked USD -->
        <div class="admin-mini-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div class="admin-card-icon"><i class="fa-solid fa-layer-group"></i></div>
                <span class="badge-status badge-approved" style="font-size: 0.65rem;">STAKED</span>
            </div>
            <div class="admin-lbl">Total System Staked Capital</div>
            <div class="admin-val" style="color: #34d399;">${{ number_format($totalStakedUsd, 2) }}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted);">{{ number_format($totalStakedCoins, 2) }} Coins</div>
            <div class="admin-accent-line"></div>
        </div>

        <!-- Card 3: Pending Deposits -->
        <div class="admin-mini-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div class="admin-card-icon"><i class="fa-solid fa-wallet"></i></div>
                @if($pendingDeposits > 0)
                    <span class="badge-status badge-pending" style="font-size: 0.65rem;">{{ $pendingDeposits }} PENDING</span>
                @else
                    <span class="badge-status badge-active" style="font-size: 0.65rem;">ALL CLEAR</span>
                @endif
            </div>
            <div class="admin-lbl">USDT Deposits Received</div>
            <div class="admin-val" style="color: var(--gold-light);">${{ number_format($totalDepositsUsd, 2) }}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted);"><a href="{{ route('admin.deposits.index') }}"
                    style="color: var(--gold-light); text-decoration: underline;">Review Deposits &rarr;</a></div>
            <div class="admin-accent-line"></div>
        </div>

        <!-- Card 4: Pending Withdrawals -->
        <div class="admin-mini-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div class="admin-card-icon"><i class="fa-solid fa-hand-holding-dollar"></i></div>
                @if($pendingWithdrawals > 0)
                    <span class="badge-status badge-rejected" style="font-size: 0.65rem;">{{ $pendingWithdrawals }}
                        PENDING</span>
                @else
                    <span class="badge-status badge-active" style="font-size: 0.65rem;">ALL CLEAR</span>
                @endif
            </div>
            <div class="admin-lbl">Withdrawals Approved</div>
            <div class="admin-val" style="color: #38bdf8;">${{ number_format($totalPaidOutUsd, 2) }}</div>
            <div style="font-size: 0.75rem; color: var(--text-muted);"><a href="{{ route('admin.withdrawals.index') }}"
                    style="color: var(--gold-light); text-decoration: underline;">Review Withdrawals &rarr;</a></div>
            <div class="admin-accent-line"></div>
        </div>

        <!-- Card 5: Daily ROI Distributed -->
        <div class="admin-mini-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div class="admin-card-icon"><i class="fa-solid fa-coins"></i></div>
                <span class="badge-status badge-active" style="font-size: 0.65rem;">ROI</span>
            </div>
            <div class="admin-lbl">Daily ROI Distributed</div>
            <div class="admin-val">{{ number_format($totalRoiDistributed, 2) }} <span
                    style="font-size: 0.85rem; color: var(--text-muted);">Coins</span></div>
            <div style="font-size: 0.75rem; color: var(--text-muted);"><a href="{{ route('admin.reports.roi') }}"
                    style="color: var(--gold-light); text-decoration: underline;">ROI Report &rarr;</a></div>
            <div class="admin-accent-line"></div>
        </div>

        <!-- Card 6: Direct Income Distributed -->
        <div class="admin-mini-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div class="admin-card-icon"><i class="fa-solid fa-bolt"></i></div>
                <span class="badge-status badge-active" style="font-size: 0.65rem;">DIRECT</span>
            </div>
            <div class="admin-lbl">Direct Income Paid</div>
            <div class="admin-val">{{ number_format($totalDirectDistributed, 2) }} <span
                    style="font-size: 0.85rem; color: var(--text-muted);">Coins</span></div>
            <div style="font-size: 0.75rem; color: var(--text-muted);"><a href="{{ route('admin.reports.direct') }}"
                    style="color: var(--gold-light); text-decoration: underline;">Direct Report &rarr;</a></div>
            <div class="admin-accent-line"></div>
        </div>

        <!-- Card 7: Level Income Distributed -->
        <div class="admin-mini-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div class="admin-card-icon"><i class="fa-solid fa-network-wired"></i></div>
                <span class="badge-status badge-active" style="font-size: 0.65rem;">LEVELS</span>
            </div>
            <div class="admin-lbl">Level Income Paid (1-10)</div>
            <div class="admin-val">{{ number_format($totalLevelDistributed, 2) }} <span
                    style="font-size: 0.85rem; color: var(--text-muted);">Coins</span></div>
            <div style="font-size: 0.75rem; color: var(--text-muted);"><a href="{{ route('admin.reports.level') }}"
                    style="color: var(--gold-light); text-decoration: underline;">Level Report &rarr;</a></div>
            <div class="admin-accent-line"></div>
        </div>

        <!-- Card 8: Reward Income Distributed -->
        <div class="admin-mini-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div class="admin-card-icon"><i class="fa-solid fa-trophy"></i></div>
                <span class="badge-status badge-approved" style="font-size: 0.65rem;">REWARDS</span>
            </div>
            <div class="admin-lbl">Reward Income Paid</div>
            <div class="admin-val" style="color: #34d399;">{{ number_format($totalRewardDistributed, 2) }} <span
                    style="font-size: 0.85rem; color: var(--text-muted);">Coins</span></div>
            <div style="font-size: 0.75rem; color: var(--text-muted);"><a href="{{ route('admin.reports.rewards') }}"
                    style="color: var(--gold-light); text-decoration: underline;">Rewards Report &rarr;</a></div>
            <div class="admin-accent-line"></div>
        </div>
    </div>

    <!-- Token Pricing & Supply Pool Banner -->
    <div class="token-pool-banner">
        <div>
            <div
                style="font-size: 0.78rem; font-weight: 800; color: var(--gold-light); text-transform: uppercase; letter-spacing: 1px;">
                TOKEN PRICING & SUPPLY POOL METRICS</div>
            <div style="display: flex; gap: 2rem; margin-top: 0.6rem; flex-wrap: wrap;">
                <div>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Live BINALEX Rate:</span>
                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 2px;">
                        <strong
                            style="font-size: 1.2rem; color: #34d399;">${{ number_format($pricing->coin_rate_usd ?? 0.0200, 4) }}
                            USD</strong>
                        <span class="live-pulse-badge"><span class="live-pulse-dot"></span> LIVE</span>
                    </div>
                </div>
                <div>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Remaining Supply Pool:</span>
                    <strong
                        style="font-size: 1.2rem; color: var(--gold-light); display: block;">{{ number_format($pricing->total_pool_coins, 2) }}
                        Coins</strong>
                </div>
                <div>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Total Capital Raised:</span>
                    <strong
                        style="font-size: 1.2rem; color: #38bdf8; display: block;">${{ number_format($pricing->total_pool_usd, 2) }}
                        USD</strong>
                </div>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.settings.index') }}" class="btn-gold"><i class="fa-solid fa-sliders"></i> Manage Pool &
                Price</a>
        </div>
    </div>

    <!-- 10 Latest Registered Members Table Card -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div
            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem;">
            <div>
                <h3 style="color: var(--gold-light); font-size: 1.15rem;"><i class="fa-solid fa-users"></i> 10 Latest
                    Registered Members</h3>
                <p style="color: var(--text-muted); font-size: 0.82rem;">Recently registered users in BINALEX system.</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn-gold" style="padding: 0.5rem 1rem; font-size: 0.8rem;"><i
                    class="fa-solid fa-list"></i> View All Users ({{ $totalUsers }})</a>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Self ID</th>
                        <th>User Details</th>
                        <th>Sponsor Code</th>
                        <th>Status</th>
                        <th>Staked USD</th>
                        <th>Registration Date & Time</th>
                        <th>Activation Date & Time</th>
                        <th>Quick Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers as $u)
                        @php
                            $isUserActive = $u->stakes()->where('status', 'active')->exists();
                            $activationDate = $u->first_activation_date;
                        @endphp
                        <tr>
                            <td>
                                <strong style="color: var(--gold-light); font-size: 0.95rem;">{{ $u->referral_code }}</strong>
                                <i class="fa-solid fa-copy copy-icon-btn" title="Copy Self ID"
                                    onclick="copyToClipboard('{{ $u->referral_code }}', 'Self ID')"></i>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div class="user-profile-avatar">
                                        {{ strtoupper(substr($u->name ?? $u->email, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong style="color: #fff;">{{ $u->name }}</strong>
                                        <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $u->email }} |
                                            {{ $u->mobile }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($u->sponsor)
                                    <strong style="color: var(--gold-light);">{{ $u->sponsor->referral_code }}</strong>
                                    <i class="fa-solid fa-copy copy-icon-btn" title="Copy Sponsor Code"
                                        onclick="copyToClipboard('{{ $u->sponsor->referral_code }}', 'Sponsor Code')"></i>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $u->sponsor->name }}</div>
                                @else
                                    <span style="color: var(--text-muted);">Top Level</span>
                                @endif
                            </td>
                            <td>
                                @if($isUserActive)
                                    <span class="badge-status badge-active"><i class="fa-solid fa-circle-check"></i> ACTIVE</span>
                                @else
                                    <span class="badge-status badge-rejected"><i class="fa-solid fa-clock"></i> INACTIVE</span>
                                @endif
                            </td>
                            <td style="font-weight: 800; color: #34d399;">
                                ${{ number_format($u->total_staked_usd, 2) }}
                            </td>
                            <td>
                                <div style="font-size: 0.85rem; font-weight: 600; color: #fff;">
                                    {{ $u->created_at->format('M d, Y') }}</div>
                                <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $u->created_at->format('h:i A') }}
                                </div>
                            </td>
                            <td>
                                @if($activationDate)
                                    <div style="font-size: 0.85rem; font-weight: 700; color: #34d399;">
                                        {{ $activationDate->format('M d, Y') }}</div>
                                    <div style="font-size: 0.78rem; color: var(--text-muted);">
                                        {{ $activationDate->format('h:i A') }}</div>
                                @else
                                    <span class="badge-status badge-rejected" style="font-size: 0.7rem; padding: 2px 8px;">NOT
                                        ACTIVATED</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 6px;">
                                    <form action="{{ route('admin.users.impersonate', $u->id) }}" method="POST" target="_blank"
                                        style="margin:0;">
                                        @csrf
                                        <button type="submit" class="btn-gold" style="padding: 4px 10px; font-size: 0.75rem;"
                                            title="Login to User Portal in New Tab">
                                            <i class="fa-solid fa-right-to-bracket"></i> Login
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.users.show', $u->id) }}" class="btn-gold"
                                        style="padding: 4px 10px; font-size: 0.75rem; background: rgba(56,189,248,0.2); color: #38bdf8; border-color: rgba(56,189,248,0.4);"
                                        title="View Full Details Profile">
                                        <i class="fa-solid fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.users.tree', $u->id) }}" class="btn-gold"
                                        style="padding: 4px 10px; font-size: 0.75rem; background: rgba(255,255,255,0.1); color: #fff;"
                                        title="View Tree">
                                        <i class="fa-solid fa-sitemap"></i> Tree
                                    </a>
                                    <a href="{{ route('admin.users.edit', $u->id) }}" class="btn-gold"
                                        style="padding: 4px 10px; font-size: 0.75rem; background: rgba(245,184,46,0.15); color: var(--gold-light);"
                                        title="Edit User">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 2rem;">No registered
                                users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Transactions Tables Grid -->
    <div class="tables-2-col">
        <!-- Recent Deposits -->
        <div class="card" style="margin-bottom: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="color: var(--gold-light); font-size: 1.1rem;"><i class="fa-solid fa-wallet"></i> Recent USDT
                    Deposit Requests</h3>
                <a href="{{ route('admin.deposits.index') }}"
                    style="color: var(--gold-light); text-decoration: none; font-size: 0.82rem; font-weight: 700;">View All
                    &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Amount USD</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentDeposits as $dep)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div class="user-profile-avatar" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                            {{ strtoupper(substr($dep->user->name ?? $dep->user->email, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $dep->user->name ?? 'User' }}</strong>
                                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                                {{ $dep->user->referral_code ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="color: var(--gold-light); font-weight: 800;">
                                    ${{ number_format($dep->amount_usd, 2) }}</td>
                                <td><span class="badge-status badge-{{ $dep->status }}">{{ strtoupper($dep->status) }}</span>
                                </td>
                                <td><a href="{{ route('admin.deposits.index') }}" class="btn-gold"
                                        style="padding: 4px 10px; font-size: 0.75rem;">Manage</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">No
                                    deposit requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Withdrawals -->
        <div class="card" style="margin-bottom: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="color: var(--gold-light); font-size: 1.1rem;"><i class="fa-solid fa-hand-holding-dollar"></i>
                    Recent Coin Withdrawal Requests</h3>
                <a href="{{ route('admin.withdrawals.index') }}"
                    style="color: var(--gold-light); text-decoration: none; font-size: 0.82rem; font-weight: 700;">View All
                    &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Coins Requested</th>
                            <th>Net USDT</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentWithdrawals as $w)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div class="user-profile-avatar" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                            {{ strtoupper(substr($w->user->name ?? $w->user->email, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $w->user->name ?? 'User' }}</strong>
                                            <div style="font-size: 0.75rem; color: var(--text-muted);">
                                                {{ $w->user->referral_code ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($w->coins_amount, 2) }} Coins</td>
                                <td style="color: #34d399; font-weight: 800;">${{ number_format($w->net_usd, 2) }}</td>
                                <td><span class="badge-status badge-{{ $w->status }}">{{ strtoupper($w->status) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-muted); padding: 1.5rem;">No
                                    withdrawal requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
