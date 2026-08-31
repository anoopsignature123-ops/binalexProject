@extends('user.layouts.app')

@section('title', 'Dashboard Overview')

@section('content')
<style>
    /* --- Premium Reference Replica Gold Theme --- */
    .welcome-header-banner {
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
    .welcome-title { font-size: 1.6rem; font-weight: 800; color: #fff; margin-bottom: 0.3rem; }
    .welcome-sub { color: var(--text-muted); font-size: 0.9rem; }

    .referral-box-card {
        background: rgba(18, 24, 38, 0.9);
        border: 1px solid var(--border-gold);
        border-radius: 16px;
        padding: 1.2rem 1.6rem;
        margin-bottom: 1.5rem;
    }

    .wallets-8-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.2rem;
        margin-bottom: 1.5rem;
    }
    .wallet-mini-card {
        background: rgba(18, 24, 38, 0.85);
        border: 1px solid var(--border-gold);
        border-radius: 16px;
        padding: 1.2rem;
        position: relative;
        overflow: hidden;
        transition: transform 0.25s ease, border-color 0.25s ease;
    }
    .wallet-mini-card:hover {
        transform: translateY(-3px);
        border-color: var(--gold-light);
        box-shadow: var(--gold-glow);
    }
    .wallet-icon-box {
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
    .wallet-val { font-size: 1.4rem; font-weight: 800; color: #fff; margin: 0.2rem 0; }
    .wallet-lbl { font-size: 0.76rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; }
    .wallet-accent-line { height: 3px; background: var(--gold-gradient); border-radius: 2px; margin-top: 0.8rem; }

    .middle-4-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.2rem;
        margin-bottom: 1.5rem;
    }

    .three-col-layout {
        display: grid;
        grid-template-columns: 1.2fr 1.2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .quick-action-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.9rem 1.2rem;
        background: rgba(8, 10, 15, 0.6);
        border: 1px solid var(--border-gold);
        border-radius: 12px;
        color: #fff;
        text-decoration: none;
        margin-bottom: 0.8rem;
        transition: 0.25s ease;
    }
    .quick-action-link:hover {
        background: rgba(245, 184, 46, 0.15);
        border-color: var(--gold-light);
        transform: translateX(4px);
    }

    .bottom-utility-bar {
        background: rgba(14, 18, 28, 0.95);
        border: 1px solid var(--border-gold);
        border-radius: 14px;
        padding: 0.9rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        font-size: 0.82rem;
        color: var(--text-muted);
    }

    @media (max-width: 1400px) {
        .wallets-8-grid { grid-template-columns: repeat(2, 1fr); }
        .middle-4-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 1200px) {
        .three-col-layout { grid-template-columns: 1fr; }
    }

    @media (max-width: 650px) {
        .wallets-8-grid { grid-template-columns: 1fr; }
        .middle-4-grid { grid-template-columns: 1fr; }
    }
</style>

<!-- Welcome Header Banner -->
<div class="welcome-header-banner">
    <div>
        <div style="font-size: 0.75rem; font-weight: 800; color: var(--gold-light); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 4px;">WELCOME BACK</div>
        <h2 class="welcome-title">Welcome back, {{ strtoupper($user->name) }} (User Id: {{ $user->referral_code }}) 👋</h2>
        <p class="welcome-sub">Track your funds, investments, earnings, and referral progress from one place.</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <button class="btn-gold" style="background: rgba(245,184,46,0.15); border: 1px solid var(--border-gold); color: var(--gold-light);" onclick="window.open('/', '_blank')">
            <i class="fa-solid fa-globe"></i> Community
        </button>
        <a href="{{ route('user.staking.index') }}" class="btn-gold"><i class="fa-solid fa-rocket"></i> Stake Now</a>
        <div style="background: rgba(8,10,15,0.7); border: 1px solid var(--border-gold); padding: 0.6rem 1rem; border-radius: 10px; color: var(--gold-light); font-size: 0.82rem; font-weight: 700;">
            <i class="fa-solid fa-calendar-day"></i> {{ now()->format('l, d M Y') }}
        </div>
    </div>
</div>

<!-- Referral Links Section -->
<div class="referral-box-card">
    <div style="font-size: 0.78rem; color: var(--gold-light); text-transform: uppercase; font-weight: 800; letter-spacing: 1px; margin-bottom: 0.6rem;"><i class="fa-solid fa-share-nodes"></i> YOUR REFERRAL LINK</div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <input type="text" id="refLinkInput" value="{{ route('user.register', ['ref' => $user->referral_code]) }}" readonly style="flex-grow: 1; padding: 0.75rem 1rem; background: rgba(8,10,15,0.8); border: 1px solid var(--border-gold); border-radius: 10px; color: var(--gold-light); font-weight: 700; outline: none;">
        <button class="btn-gold" onclick="copyReferralLink()"><i class="fa-solid fa-copy"></i> Copy Referral Link</button>
    </div>
</div>

<!-- 8 Wallets & Portfolio Cards Grid -->
<div class="wallets-8-grid">
    <!-- Card 1: Withdraw Wallet -->
    <div class="wallet-mini-card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="wallet-icon-box"><i class="fa-solid fa-wallet"></i></div>
            <span class="badge-status badge-active" style="font-size: 0.65rem;">AVAILABLE</span>
        </div>
        <div class="wallet-lbl">Withdraw Wallet (Earning)</div>
        <div class="wallet-val" style="color: #34d399;">${{ number_format($user->working_coin_balance * 0.10, 2) }} <span style="font-size: 0.85rem; color: var(--text-muted);">USD</span></div>
        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ number_format($user->working_coin_balance, 2) }} Coins</div>
        <div class="wallet-accent-line"></div>
    </div>

    <!-- Card 2: Deposit Wallet -->
    <div class="wallet-mini-card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="wallet-icon-box"><i class="fa-solid fa-vault"></i></div>
            <span class="badge-status badge-pending" style="font-size: 0.65rem;">USDT</span>
        </div>
        <div class="wallet-lbl">Deposit Wallet</div>
        <div class="wallet-val" style="color: var(--gold-light);">${{ number_format($user->usdt_balance, 2) }} <span style="font-size: 0.85rem; color: var(--text-muted);">USDT</span></div>
        <div style="font-size: 0.75rem; color: var(--text-muted);"><a href="{{ route('user.deposit.index') }}" style="color: var(--gold-light); text-decoration: underline;">+ Add USDT Deposit</a></div>
        <div class="wallet-accent-line"></div>
    </div>

    <!-- Card 3: ROI Yield Wallet -->
    <div class="wallet-mini-card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="wallet-icon-box"><i class="fa-solid fa-coins"></i></div>
            <span class="badge-status badge-active" style="background: rgba(245, 184, 46, 0.2); color: var(--gold-light); font-size: 0.65rem;">ROI YIELD</span>
        </div>
        <div class="wallet-lbl">ROI Yield Wallet</div>
        <div class="wallet-val" style="color: var(--gold-light);">{{ number_format($user->coin_balance, 2) }} <span style="font-size: 0.85rem; color: var(--text-muted);">Coins</span></div>
        <div style="font-size: 0.75rem; color: var(--text-muted);"><a href="{{ route('user.p2p.index') }}" style="color: var(--gold-light); text-decoration: underline;">P2P Transferable</a></div>
        <div class="wallet-accent-line"></div>
    </div>

    <!-- Card 4: Active Package -->
    <div class="wallet-mini-card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="wallet-icon-box"><i class="fa-solid fa-box-archive"></i></div>
            <span class="badge-status badge-active" style="font-size: 0.65rem;">${{ number_format($user->total_staked_usd, 0) }}</span>
        </div>
        <div class="wallet-lbl">Active Staking Package</div>
        <div class="wallet-val" style="font-size: 1.2rem; margin: 0.4rem 0;">{{ $activeStakeModel->package_name ?? 'No Package' }}</div>
        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $activeStakesCount }} Active Plan(s)</div>
        <div class="wallet-accent-line"></div>
    </div>

    <!-- Card 5: P2P Transfer Balance -->
    <div class="wallet-mini-card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="wallet-icon-box"><i class="fa-solid fa-right-left"></i></div>
            <span class="badge-status badge-active" style="font-size: 0.65rem;">P2P</span>
        </div>
        <div class="wallet-lbl">P2P Transferable Coins</div>
        <div class="wallet-val">{{ number_format($user->coin_balance + $user->working_coin_balance, 2) }} <span style="font-size: 0.85rem; color: var(--text-muted);">Coins</span></div>
        <div style="font-size: 0.75rem; color: var(--text-muted);"><a href="{{ route('user.p2p.index') }}" style="color: var(--gold-light); text-decoration: underline;">Send to Member &rarr;</a></div>
        <div class="wallet-accent-line"></div>
    </div>

    <!-- Card 6: Reward Income -->
    <div class="wallet-mini-card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="wallet-icon-box"><i class="fa-solid fa-trophy"></i></div>
            <span class="badge-status badge-approved" style="font-size: 0.65rem;">REWARDS</span>
        </div>
        <div class="wallet-lbl">Reward Income</div>
        <div class="wallet-val" style="color: #34d399;">{{ number_format($totalRewardIncome, 2) }} <span style="font-size: 0.85rem; color: var(--text-muted);">Coins</span></div>
        <div style="font-size: 0.75rem; color: var(--text-muted);"><a href="{{ route('user.reports.rewards') }}" style="color: var(--gold-light); text-decoration: underline;">View Achievements</a></div>
        <div class="wallet-accent-line"></div>
    </div>

    <!-- Card 7: Token Portfolio -->
    <div class="wallet-mini-card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="wallet-icon-box"><i class="fa-solid fa-chart-pie"></i></div>
            <span class="live-pulse-badge"><span class="live-pulse-dot"></span> LIVE: ${{ number_format($pricing->coin_rate_usd ?? 0.0200, 4) }}</span>
        </div>
        <div class="wallet-lbl">BINALEX Token Portfolio</div>
        <div class="wallet-val">{{ number_format($user->coin_balance + $user->working_coin_balance, 2) }} <span style="font-size: 0.85rem; color: var(--text-muted);">Coins</span></div>
        <div style="font-size: 0.75rem; color: #34d399; font-weight: 700;">Valuation: ${{ number_format(($user->coin_balance + $user->working_coin_balance) * ($pricing->coin_rate_usd ?? 0.0200), 2) }} USD</div>
        <div class="wallet-accent-line"></div>
    </div>

    <!-- Card 8: Direct Members -->
    <div class="wallet-mini-card">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="wallet-icon-box"><i class="fa-solid fa-users"></i></div>
            <span class="badge-status badge-active" style="font-size: 0.65rem;">TEAM</span>
        </div>
        <div class="wallet-lbl">Direct Referrals</div>
        <div class="wallet-val">{{ $user->directs->count() }} <span style="font-size: 0.85rem; color: var(--text-muted);">Members</span></div>
        <div style="font-size: 0.75rem; color: #34d399; font-weight: 700;">{{ $user->activeDirectsCount() }} Active Staked Directs</div>
        <div class="wallet-accent-line"></div>
    </div>
</div>

<!-- Middle 4 Cards Section -->
<div class="middle-4-grid">
    <!-- Card 1: Business Plan PDF -->
    <div class="card" style="margin-bottom: 0; position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 0.75rem; color: var(--gold-light); font-weight: 800; text-transform: uppercase;">BUSINESS PLAN</div>
                <h4 style="color: #fff; font-size: 1.1rem; margin: 4px 0;">Official PDF Deck</h4>
                <p style="color: var(--text-muted); font-size: 0.8rem;">Complete Guide & Tokenomics PDF</p>
            </div>
            <i class="fa-solid fa-file-pdf" style="font-size: 2.2rem; color: var(--gold-light);"></i>
        </div>
        <div style="margin-top: 1rem;">
            <button class="btn-gold" style="padding: 0.5rem 1rem; font-size: 0.82rem;" onclick="openBusinessPlanPdf()"><i class="fa-solid fa-file-arrow-down"></i> View Plan PDF</button>
        </div>
    </div>

    <!-- Card 2: Career Rank Status -->
    <div class="card" style="margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 0.75rem; color: var(--gold-light); font-weight: 800; text-transform: uppercase;">CAREER RANK</div>
                <h4 style="color: #fff; font-size: 1.1rem; margin: 4px 0;">Milestones Status</h4>
                <p style="color: var(--text-muted); font-size: 0.8rem;">60% Power / 40% Other Legs</p>
            </div>
            <i class="fa-solid fa-award" style="font-size: 2.2rem; color: #34d399;"></i>
        </div>
        <div style="margin-top: 1rem;">
            <a href="{{ route('user.reports.rewards') }}" class="btn-gold" style="padding: 0.5rem 1rem; font-size: 0.82rem;"><i class="fa-solid fa-trophy"></i> View Rankings</a>
        </div>
    </div>

    <!-- Card 3: Staking Rewards -->
    <div class="card" style="margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 0.75rem; color: var(--gold-light); font-weight: 800; text-transform: uppercase;">STAKING YIELD</div>
                <h4 style="color: #fff; font-size: 1.1rem; margin: 4px 0;">Daily ROI Yield</h4>
                <p style="color: var(--text-muted); font-size: 0.8rem;">300 Days 2x - 3x Growth</p>
            </div>
            <i class="fa-solid fa-rocket" style="font-size: 2.2rem; color: var(--gold-light);"></i>
        </div>
        <div style="margin-top: 1rem;">
            <a href="{{ route('user.staking.index') }}" class="btn-gold" style="padding: 0.5rem 1rem; font-size: 0.82rem;"><i class="fa-solid fa-plus"></i> Stake Package</a>
        </div>
    </div>

    <!-- Card 4: Network Structure -->
    <div class="card" style="margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 0.75rem; color: var(--gold-light); font-weight: 800; text-transform: uppercase;">GENEALOGY</div>
                <h4 style="color: #fff; font-size: 1.1rem; margin: 4px 0;">Team Tree Leg</h4>
                <p style="color: var(--text-muted); font-size: 0.8rem;">Visual Column Hierarchy</p>
            </div>
            <i class="fa-solid fa-sitemap" style="font-size: 2.2rem; color: #38bdf8;"></i>
        </div>
        <div style="margin-top: 1rem;">
            <a href="{{ route('user.network.tree') }}" class="btn-gold" style="padding: 0.5rem 1rem; font-size: 0.82rem;"><i class="fa-solid fa-diagram-project"></i> Open Tree</a>
        </div>
    </div>
</div>

<!-- Three Column Bottom Grid (Financial Overview, Network Overview, Quick Actions) -->
<div class="three-col-layout">
    <!-- Financial Overview (Earnings Summary Table) -->
    <div class="card" style="margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <div>
                <div style="font-size: 0.75rem; color: var(--gold-light); font-weight: 800; text-transform: uppercase;">FINANCIAL OVERVIEW</div>
                <h3 style="color: #fff; font-size: 1.2rem;">Earnings Summary</h3>
            </div>
            <a href="{{ route('user.reports.incomes') }}" style="color: var(--gold-light); text-decoration: none; font-size: 0.82rem; font-weight: 700;">View History &rarr;</a>
        </div>

        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>INCOME TYPE</th>
                        <th>TODAY</th>
                        <th>TOTAL EARNED</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Daily Staking ROI</strong></td>
                        <td style="color: #34d399; font-weight: 700;">+{{ number_format($todayDailyIncome, 2) }}</td>
                        <td style="color: var(--gold-light); font-weight: 800;">+{{ number_format($totalDailyIncome, 2) }} Coins</td>
                    </tr>
                    <tr>
                        <td><strong>Direct Income</strong></td>
                        <td style="color: #34d399; font-weight: 700;">+{{ number_format($todayDirectIncome, 2) }}</td>
                        <td style="color: var(--gold-light); font-weight: 800;">+{{ number_format($totalDirectIncome, 2) }} Coins</td>
                    </tr>
                    <tr>
                        <td><strong>Level Income</strong></td>
                        <td style="color: #34d399; font-weight: 700;">+{{ number_format($todayLevelIncome, 2) }}</td>
                        <td style="color: var(--gold-light); font-weight: 800;">+{{ number_format($totalLevelIncome, 2) }} Coins</td>
                    </tr>
                    <tr>
                        <td><strong>Award & Reward</strong></td>
                        <td style="color: #34d399; font-weight: 700;">+{{ number_format($todayRewardIncome, 2) }}</td>
                        <td style="color: var(--gold-light); font-weight: 800;">+{{ number_format($totalRewardIncome, 2) }} Coins</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Network Overview (Clean Business Performance & World Map Graphic) -->
    <div class="card" style="margin-bottom: 0;">
        <div style="font-size: 0.75rem; color: var(--gold-light); font-weight: 800; text-transform: uppercase; margin-bottom: 0.3rem;">NETWORK OVERVIEW</div>
        <h3 style="color: #fff; font-size: 1.2rem; margin-bottom: 1rem;">Team Business Performance</h3>

        <div style="background: rgba(8, 10, 15, 0.6); padding: 1rem; border-radius: 12px; border: 1px solid var(--border-gold); margin-bottom: 1rem;">
            <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 6px;">
                <span style="color: var(--text-muted);">Direct Referrals Business</span>
                <strong style="color: var(--gold-light);">${{ number_format($powerLegVolume, 2) }} USD</strong>
            </div>
            <div style="height: 6px; background: rgba(255,255,255,0.08); border-radius: 10px; overflow: hidden;">
                <div style="width: {{ $totalTeamVolume > 0 ? min(100, ($powerLegVolume / $totalTeamVolume) * 100) : 0 }}%; height: 100%; background: var(--gold-gradient);"></div>
            </div>
        </div>

        <div style="background: rgba(8, 10, 15, 0.6); padding: 1rem; border-radius: 12px; border: 1px solid var(--border-gold); margin-bottom: 1rem;">
            <div style="display: flex; justify-content: space-between; font-size: 0.85rem; margin-bottom: 6px;">
                <span style="color: var(--text-muted);">Total Downline Team Business</span>
                <strong style="color: #38bdf8;">${{ number_format($totalTeamVolume, 2) }} USD</strong>
            </div>
            <div style="height: 6px; background: rgba(255,255,255,0.08); border-radius: 10px; overflow: hidden;">
                <div style="width: 100%; height: 100%; background: #38bdf8;"></div>
            </div>
        </div>

        <!-- World Network Graphic Banner -->
        <div style="border-radius: 12px; overflow: hidden; position: relative; height: 100px; background: radial-gradient(circle, rgba(245,184,46,0.2) 0%, rgba(8,10,15,0.9) 100%); display: flex; align-items: center; justify-content: center; text-align: center;">
            <div style="color: var(--gold-light); font-weight: 800; font-size: 0.9rem; letter-spacing: 1px;">
                <i class="fa-solid fa-earth-americas" style="font-size: 1.8rem; margin-bottom: 4px; display: block;"></i>
                DECENTRALIZED BORDERLESS ECOSYSTEM
            </div>
        </div>
    </div>

    <!-- Quick Actions Panel -->
    <div class="card" style="margin-bottom: 0;">
        <div style="font-size: 0.75rem; color: var(--gold-light); font-weight: 800; text-transform: uppercase; margin-bottom: 0.3rem;">QUICK ACTIONS</div>
        <h3 style="color: #fff; font-size: 1.2rem; margin-bottom: 1rem;">Instant Shortcuts</h3>

        <a href="{{ route('user.deposit.index') }}" class="quick-action-link">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-wallet" style="color: var(--gold-light);"></i>
                <div>
                    <strong style="display: block; font-size: 0.9rem;">Deposit USDT</strong>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">Add funds to deposit wallet</span>
                </div>
            </div>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.8rem; color: var(--gold-light);"></i>
        </a>

        <a href="{{ route('user.staking.index') }}" class="quick-action-link">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-box-archive" style="color: #34d399;"></i>
                <div>
                    <strong style="display: block; font-size: 0.9rem;">Stake Package</strong>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">Activate staking growth plan</span>
                </div>
            </div>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.8rem; color: var(--gold-light);"></i>
        </a>

        <a href="{{ route('user.withdrawal.index') }}" class="quick-action-link">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-hand-holding-dollar" style="color: #f87171;"></i>
                <div>
                    <strong style="display: block; font-size: 0.9rem;">Withdraw Earnings</strong>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">Withdraw from Earning Wallet</span>
                </div>
            </div>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.8rem; color: var(--gold-light);"></i>
        </a>

        <a href="{{ route('user.p2p.index') }}" class="quick-action-link">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-right-left" style="color: #38bdf8;"></i>
                <div>
                    <strong style="display: block; font-size: 0.9rem;">P2P Coin Transfer</strong>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">Send ROI/Earning coins to member</span>
                </div>
            </div>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.8rem; color: var(--gold-light);"></i>
        </a>

        <a href="{{ route('user.network.tree') }}" class="quick-action-link">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-sitemap" style="color: var(--gold-light);"></i>
                <div>
                    <strong style="display: block; font-size: 0.9rem;">View Genealogy Tree</strong>
                    <span style="font-size: 0.75rem; color: var(--text-muted);">Check your downline network</span>
                </div>
            </div>
            <i class="fa-solid fa-chevron-right" style="font-size: 0.8rem; color: var(--gold-light);"></i>
        </a>
    </div>
</div>

<!-- Bottom Utility Footer Bar -->
<div class="bottom-utility-bar">
    <div style="display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-clock" style="color: var(--gold-light);"></i>
        <span>Server Time: <strong>{{ now()->format('h:i A') }} (UTC +05:30)</strong></span>
    </div>
    <div style="display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-cloud-sun" style="color: var(--gold-light);"></i>
        <span>System Weather: <strong>30°C Mostly Clear</strong></span>
    </div>
    <div style="display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-bullhorn" style="color: var(--gold-light);"></i>
        <span>Latest Update: <strong>BINALEX Staking Pool Active!</strong></span>
    </div>
    <div style="display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-headset" style="color: var(--gold-light);"></i>
        <span>Need Help? <a href="javascript:void(0)" onclick="showToast('Support system active. Contact system admin for assistance.', 'success')" style="color: var(--gold-light); font-weight: 700; text-decoration: underline;">Contact Support</a></span>
    </div>
</div>

<script>
    function copyReferralLink() {
        const input = document.getElementById('refLinkInput');
        input.select();
        document.execCommand('copy');
        showToast('Referral link copied to clipboard successfully!', 'success');
    }

    function openBusinessPlanPdf() {
        showToast('Opening BINALEX Business Plan Deck...', 'success');
        window.open('{{ asset("pdf/binalex-plan.pdf") }}', '_blank');
    }
</script>
@endsection
