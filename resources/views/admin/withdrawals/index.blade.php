@extends('admin.layouts.app')

@section('title', 'Manage Coin Withdrawals & Contract Payouts')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        /* =========================================================
           BINALEX LUXURY CRYPTO ADMIN DESIGN SYSTEM
           ========================================================= */
        .binalex-header-bar {
            background: linear-gradient(135deg, rgba(245, 184, 46, 0.12) 0%, rgba(14, 18, 28, 0.95) 100%);
            border: 1px solid var(--border-gold);
            border-radius: 18px;
            padding: 1.4rem 1.8rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        .binalex-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 1024px) {
            .binalex-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .binalex-stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .binalex-stat-card {
            background: rgba(14, 18, 28, 0.85);
            border: 1px solid var(--border-gold);
            border-radius: 16px;
            padding: 1.2rem 1.4rem;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            transition: all 0.25s ease;
        }

        .binalex-stat-card:hover {
            transform: translateY(-3px);
            border-color: var(--gold-primary);
            box-shadow: var(--gold-glow);
        }

        .binalex-stat-icon {
            width: 48px;
            height: 48px;
            flex: 0 0 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            background: rgba(245, 184, 46, 0.1);
            border: 1px solid rgba(245, 184, 46, 0.25);
            color: var(--gold-light);
        }

        .binalex-main-card {
            background: rgba(14, 18, 28, 0.88);
            border: 1px solid var(--border-gold);
            border-radius: 18px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .form-control-custom {
            padding: 0.55rem 0.9rem;
            background: rgba(8, 10, 15, 0.8);
            border: 1px solid var(--border-gold);
            border-radius: 10px;
            color: #fff;
            font-size: 0.88rem;
            outline: none;
            transition: all 0.25s ease;
        }

        .form-control-custom:focus {
            border-color: var(--gold-primary);
            box-shadow: var(--gold-glow);
        }

        /* Floating Bulk Action Bar */
        .binalex-bulk-bar {
            background: linear-gradient(135deg, rgba(245, 184, 46, 0.18) 0%, rgba(16, 22, 34, 0.98) 100%);
            border: 1px solid var(--gold-primary);
            border-radius: 14px;
            padding: 0.9rem 1.4rem;
            display: none;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            box-shadow: var(--gold-glow);
        }

        /* Table Styling */
        .binalex-table-wrap {
            overflow-x: auto;
            width: 100%;
        }

        .binalex-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.88rem;
        }

        .binalex-table th {
            padding: 13px 16px;
            color: var(--gold-light);
            background: rgba(7, 9, 14, 0.6);
            border-bottom: 1px solid var(--border-gold);
            font-size: 0.76rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 800;
        }

        .binalex-table td {
            padding: 14px 16px;
            color: var(--text-main);
            border-bottom: 1px solid rgba(245, 184, 46, 0.12);
            vertical-align: middle;
        }

        .binalex-table tbody tr {
            transition: background 0.2s ease;
        }

        .binalex-table tbody tr:hover {
            background: rgba(245, 184, 46, 0.045);
        }

        /* Action Buttons */
        .action-circle-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.04);
            color: #ccc;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .action-circle-btn.view:hover {
            background: rgba(56, 189, 248, 0.2);
            color: #38bdf8;
            border-color: #38bdf8;
            box-shadow: 0 0 10px rgba(56, 189, 248, 0.3);
        }

        .action-circle-btn.approve:hover {
            background: rgba(16, 185, 129, 0.25) !important;
            color: #10b981 !important;
            border-color: #10b981 !important;
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.3);
        }

        .action-circle-btn.reject:hover {
            background: rgba(239, 68, 68, 0.25) !important;
            color: #ef4444 !important;
            border-color: #ef4444 !important;
            box-shadow: 0 0 10px rgba(239, 68, 68, 0.3);
        }

        /* Glassmorphic Modals */
        .binalex-modal {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 10000;
            display: none;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            overflow-y: auto;
            outline: 0;
            background: rgba(4, 6, 10, 0.85);
            backdrop-filter: blur(10px);
        }

        .binalex-modal.show {
            display: block;
        }

        .binalex-modal-dialog {
            position: relative;
            width: auto;
            margin: 2rem auto;
            max-width: 520px;
            pointer-events: none;
        }

        .binalex-modal-dialog.lg {
            max-width: 820px;
        }

        .binalex-modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            pointer-events: auto;
            background: rgba(14, 18, 28, 0.96);
            border: 1px solid var(--border-gold);
            border-radius: 18px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7);
            overflow: hidden;
        }

        .binalex-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.1rem 1.4rem;
            border-bottom: 1px solid var(--border-gold);
            background: rgba(245, 184, 46, 0.08);
        }

        .binalex-modal-body {
            padding: 1.4rem;
        }

        .binalex-modal-footer {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            padding: 1.1rem 1.4rem;
            border-top: 1px solid var(--border-gold);
            background: rgba(7, 9, 14, 0.5);
        }

        .btn-modal-close {
            background: transparent;
            border: 0;
            color: #aaa;
            font-size: 1.3rem;
            cursor: pointer;
            transition: color 0.2s;
        }
        .btn-modal-close:hover {
            color: #fff;
        }

        /* Status Pills */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.74rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-pill.pending { background: rgba(245, 184, 46, 0.15); color: #ffd700; border: 1px solid rgba(245, 184, 46, 0.4); }
        .status-pill.approved { background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.4); }
        .status-pill.completed, .status-pill.paid { background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.4); }
        .status-pill.rejected { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.4); }

        @keyframes goldSpinner {
            to { transform: rotate(360deg); }
        }
    </style>
@endpush

@section('content')
    <!-- Top Header Banner with Smart Contract Details -->
    <div class="binalex-header-bar">
        <div>
            <div style="font-size: 0.75rem; font-weight: 800; color: var(--gold-light); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 4px;">
                <i class="fa-solid fa-file-contract"></i> WEB3 SMART CONTRACT PAYOUT ENGINE
            </div>
            <h2 style="font-size: 1.5rem; color: #fff; font-weight: 800; margin-bottom: 4px;">
                Withdrawal Requests & On-Chain Disbursals
            </h2>
            <div style="font-size: 0.85rem; color: var(--text-muted);">
                Contract Address: 
                <code style="color: var(--gold-light); background: rgba(0,0,0,0.6); padding: 3px 8px; border-radius: 6px; font-family: monospace;">{{ $smartContractAddress ?? '0xd69F8458CeE7381b2D9272a5dB27fa39699bB138' }}</code>
                <a href="https://bscscan.com/address/{{ $smartContractAddress ?? '0xd69F8458CeE7381b2D9272a5dB27fa39699bB138' }}" target="_blank" style="color: #38bdf8; text-decoration: underline; margin-left: 6px; font-size: 0.8rem;">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> BSCScan Explorer
                </a>
            </div>
        </div>

        <div style="text-align: right;">
            <span style="font-size: 0.78rem; color: var(--gold-light); font-weight: 700; display: block; margin-bottom: 4px;">
                <i class="fa-solid fa-circle-check" style="color: #10b981;"></i> BSC MAINNET ENGINE READY
            </span>
            <span style="font-size: 0.82rem; color: #fff;">
                Pending Queue: <strong style="color: var(--gold-primary);">{{ $tabCounts['pending'] ?? 0 }} Requests</strong>
            </span>
        </div>
    </div>

    <!-- 4 Stats Cards Grid -->
    <div class="binalex-stats-grid">
        <div class="binalex-stat-card">
            <div class="binalex-stat-icon" style="color: #10b981; border-color: rgba(16, 185, 129, 0.3); background: rgba(16, 185, 129, 0.1);">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <div>
                <span style="font-size: 0.72rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700; display: block;">Today Completed</span>
                <strong style="font-size: 1.25rem; color: #fff;">${{ number_format($completedStats['today'] ?? 0, 2) }}</strong>
            </div>
        </div>

        <div class="binalex-stat-card">
            <div class="binalex-stat-icon" style="color: #38bdf8; border-color: rgba(56, 189, 248, 0.3); background: rgba(56, 189, 248, 0.1);">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
            <div>
                <span style="font-size: 0.72rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700; display: block;">Yesterday Completed</span>
                <strong style="font-size: 1.25rem; color: #fff;">${{ number_format($completedStats['yesterday'] ?? 0, 2) }}</strong>
            </div>
        </div>

        <div class="binalex-stat-card">
            <div class="binalex-stat-icon" style="color: var(--gold-light); border-color: var(--border-gold); background: rgba(245, 184, 46, 0.1);">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div>
                <span style="font-size: 0.72rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700; display: block;">This Month Completed</span>
                <strong style="font-size: 1.25rem; color: var(--gold-light);">${{ number_format($completedStats['this_month'] ?? 0, 2) }}</strong>
            </div>
        </div>

        <div class="binalex-stat-card">
            <div class="binalex-stat-icon" style="color: #a855f7; border-color: rgba(168, 85, 247, 0.3); background: rgba(168, 85, 247, 0.1);">
                <i class="fa-solid fa-vault"></i>
            </div>
            <div>
                <span style="font-size: 0.72rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700; display: block;">All-Time Completed</span>
                <strong style="font-size: 1.25rem; color: #fff;">${{ number_format($completedStats['all_time'] ?? 0, 2) }}</strong>
            </div>
        </div>
    </div>

    <!-- Main Table Card with Filters, Tabs & Action Bar -->
    <div class="binalex-main-card">
        <!-- Filter Toolbar -->
        <div style="padding: 1.2rem 1.6rem; border-bottom: 1px solid rgba(245, 184, 46, 0.15);">
            <form method="GET" action="{{ route('admin.withdrawals.index') }}" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                <input type="hidden" name="tab" value="{{ $activeTab }}">

                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <div style="position: relative; width: 280px;">
                        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.85rem;"></i>
                        <input type="search" name="search" value="{{ request('search') }}" class="form-control-custom" style="padding-left: 36px; width: 100%;" placeholder="Search address, name, ref...">
                    </div>

                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="number" step="0.01" name="amount_from" value="{{ request('amount_from') }}" class="form-control-custom" style="width: 110px;" placeholder="Min $">
                        <input type="number" step="0.01" name="amount_to" value="{{ request('amount_to') }}" class="form-control-custom" style="width: 110px;" placeholder="Max $">
                    </div>

                    <div style="display: flex; align-items: center; gap: 6px;">
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control-custom" style="width: 135px;">
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control-custom" style="width: 135px;">
                    </div>
                </div>

                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn-gold" style="padding: 0.55rem 1.2rem; font-size: 0.85rem;"><i class="fa-solid fa-filter"></i> Filter</button>
                    <a href="{{ route('admin.withdrawals.index', ['tab' => $activeTab]) }}" class="btn-gold" style="padding: 0.55rem 1rem; font-size: 0.85rem; background: rgba(255,255,255,0.08); color: var(--text-muted);"><i class="fa-solid fa-rotate-left"></i> Reset</a>
                </div>
            </form>
        </div>

        <!-- Status Filter Tabs & Bulk Actions Bar -->
        <div style="padding: 1rem 1.6rem; background: rgba(8, 10, 16, 0.6); border-bottom: 1px solid rgba(245, 184, 46, 0.15); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                <a href="{{ route('admin.withdrawals.index', ['tab' => 'pending']) }}"
                    style="text-decoration: none; padding: 6px 14px; border-radius: 20px; font-size: 0.82rem; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; {{ $activeTab === 'pending' ? 'background: var(--gold-gradient); color: #000; box-shadow: var(--gold-glow);' : 'background: rgba(255,255,255,0.05); color: var(--text-muted); border: 1px solid rgba(255,255,255,0.1);' }}">
                    <i class="fa-solid fa-clock"></i> Pending
                    <span style="font-size: 0.72rem; padding: 2px 7px; border-radius: 10px; {{ $activeTab === 'pending' ? 'background: rgba(0,0,0,0.8); color: #fff;' : 'background: rgba(255,255,255,0.1); color: #fff;' }}">{{ $tabCounts['pending'] ?? 0 }}</span>
                </a>

                <a href="{{ route('admin.withdrawals.index', ['tab' => 'approved']) }}"
                    style="text-decoration: none; padding: 6px 14px; border-radius: 20px; font-size: 0.82rem; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; {{ $activeTab === 'approved' ? 'background: linear-gradient(135deg, #38bdf8 0%, #0284c7 100%); color: #fff; box-shadow: 0 0 15px rgba(56, 189, 248, 0.4);' : 'background: rgba(255,255,255,0.05); color: var(--text-muted); border: 1px solid rgba(255,255,255,0.1);' }}">
                    <i class="fa-solid fa-circle-check"></i> Approved Payouts
                    <span style="font-size: 0.72rem; padding: 2px 7px; border-radius: 10px; {{ $activeTab === 'approved' ? 'background: rgba(0,0,0,0.8); color: #fff;' : 'background: rgba(255,255,255,0.1); color: #fff;' }}">{{ $tabCounts['approved'] ?? 0 }}</span>
                </a>

                <a href="{{ route('admin.withdrawals.index', ['tab' => 'completed']) }}"
                    style="text-decoration: none; padding: 6px 14px; border-radius: 20px; font-size: 0.82rem; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; {{ $activeTab === 'completed' ? 'background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff; box-shadow: 0 0 15px rgba(16, 185, 129, 0.4);' : 'background: rgba(255,255,255,0.05); color: var(--text-muted); border: 1px solid rgba(255,255,255,0.1);' }}">
                    <i class="fa-solid fa-shield-check"></i> Completed
                    <span style="font-size: 0.72rem; padding: 2px 7px; border-radius: 10px; {{ $activeTab === 'completed' ? 'background: rgba(0,0,0,0.8); color: #fff;' : 'background: rgba(255,255,255,0.1); color: #fff;' }}">{{ $tabCounts['completed'] ?? 0 }}</span>
                </a>

                <a href="{{ route('admin.withdrawals.index', ['tab' => 'rejected']) }}"
                    style="text-decoration: none; padding: 6px 14px; border-radius: 20px; font-size: 0.82rem; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; {{ $activeTab === 'rejected' ? 'background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #fff; box-shadow: 0 0 15px rgba(239, 68, 68, 0.4);' : 'background: rgba(255,255,255,0.05); color: var(--text-muted); border: 1px solid rgba(255,255,255,0.1);' }}">
                    <i class="fa-solid fa-circle-xmark"></i> Rejected
                    <span style="font-size: 0.72rem; padding: 2px 7px; border-radius: 10px; {{ $activeTab === 'rejected' ? 'background: rgba(0,0,0,0.8); color: #fff;' : 'background: rgba(255,255,255,0.1); color: #fff;' }}">{{ $tabCounts['rejected'] ?? 0 }}</span>
                </a>
            </div>

            <!-- Floating Bulk Actions Bar -->
            <div id="bulkActionsBar" class="binalex-bulk-bar">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 0.85rem; font-weight: 800; color: #fff; text-transform: uppercase;">
                        <i class="fa-solid fa-list-check" style="color: var(--gold-light);"></i> <span id="selectedCount" style="color: var(--gold-light); font-size: 1.05rem;">0</span> SELECTED &mdash; TOTAL: <span id="totalAmount" style="color: #10b981; font-size: 1.05rem;">$0.00</span>
                    </span>
                </div>

                <div style="display: flex; align-items: center; gap: 8px;">
                    @if ($activeTab === 'pending')
                        <button type="button" onclick="bulkApprove()" class="btn-gold" style="padding: 0.5rem 1.1rem; font-size: 0.82rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff; box-shadow: 0 0 12px rgba(16, 185, 129, 0.4);">
                            <i class="fa-solid fa-check-double"></i> Bulk Approve
                        </button>
                    @endif

                    @if ($activeTab === 'approved')
                        <button type="button" onclick="showBulkModal()" class="btn-gold" style="padding: 0.5rem 1.2rem; font-size: 0.82rem; background: var(--gold-gradient); color: #000; box-shadow: var(--gold-glow); font-weight: 800;">
                            <i class="fa-solid fa-bolt"></i> Process via Smart Contract
                        </button>
                    @endif

                    <button type="button" onclick="clearSelection()" class="btn-gold" style="padding: 0.5rem 0.9rem; font-size: 0.82rem; background: rgba(255,255,255,0.1); color: #fff;">
                        Clear
                    </button>
                </div>
            </div>
        </div>

        <!-- Withdrawals Table -->
        <div class="binalex-table-wrap">
            <table class="binalex-table" aria-label="Withdrawal requests table">
                <thead>
                    <tr>
                        @if (in_array($activeTab, ['pending', 'approved']))
                            <th width="40" style="text-align: center;">
                                <input type="checkbox" id="selectAll" onchange="toggleAll(this)" style="cursor: pointer; transform: scale(1.2);">
                            </th>
                        @endif
                        <th>#</th>
                        <th>User Account</th>
                        <th>Gross Requested</th>
                        <th>Fee (10%)</th>
                        <th>Net Payout USDT</th>
                        <th>BEP-20 Wallet Address</th>
                        <th>Ref Code</th>
                        @if ($activeTab === 'completed')
                            <th>Transaction Hash</th>
                        @endif
                        <th>Status</th>
                        <th>Requested At</th>
                        <th>Completed At</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $w)
                                            @php
                        $requested = (float) $w->amount;
                        $deduction = round($requested * 0.1, 2);
                        $netPayout = (float) ($w->net_usd > 0 ? $w->net_usd : round($requested - $deduction, 2));
                        $userWallet = $w->bep20_usdt_address ?? $w->wallet_address;
                        $txHash = $w->transaction_hash ?? $w->tx_hash;
                        $refNo = $w->transaction_ref ?? $w->withdraw_no;
                                            @endphp
                                            <tr>
                                                @if (in_array($activeTab, ['pending', 'approved']))
                                                    <td style="text-align: center;">
                                                        <input type="checkbox" class="row-check" value="{{ $w->id }}"
                                                            data-wallet="{{ $userWallet }}" data-amount="{{ $netPayout }}"
                                                            data-user="{{ $w->user->name ?? 'N/A' }}" onchange="updateSelection()"
                                                            style="cursor: pointer; transform: scale(1.2);">
                                                    </td>
                                                @endif
                                                <td style="color: var(--text-muted); font-weight: 700;">
                                                    {{ ($withdrawals->currentPage() - 1) * $withdrawals->perPage() + $loop->iteration }}
                                                </td>
                                                <td>
                                                    <div style="display: flex; flex-direction: column;">
                                                        <strong style="color: #fff; font-weight: 700;">{{ $w->user->name ?? 'N/A' }}</strong>
                                                        <span style="color: var(--text-muted); font-size: 0.76rem;">{{ $w->user->email ?? 'N/A' }} &bull; <span style="color: var(--gold-light);">{{ $w->user->referral_code ?? '' }}</span></span>
                                                    </div>
                                                </td>
                                                <td><strong style="color: #fff;">${{ number_format($requested, 2) }}</strong></td>
                                                <td><span style="color: #ef4444; font-weight: 600;">-${{ number_format($deduction, 2) }}</span></td>
                                                <td><strong style="color: #10b981; font-size: 0.95rem;">${{ number_format($netPayout, 2) }}</strong></td>
                                                <td>
                                                    <code style="font-size: 0.78rem; color: var(--gold-light); background: rgba(0,0,0,0.6); padding: 3px 8px; border-radius: 6px; font-family: monospace;"
                                                        title="{{ $userWallet }}">{{ Str::limit($userWallet, 22) }}</code>
                                                </td>
                                                <td><span style="font-size: 0.78rem; color: #ffd700; font-family: monospace; font-weight: 700;">{{ $refNo }}</span></td>
                                                @if ($activeTab === 'completed')
                                                    <td>
                                                        @if(filled($txHash))
                                                            <a href="https://bscscan.com/tx/{{ $txHash }}" target="_blank"
                                                                style="font-size: 0.78rem; color: #38bdf8; text-decoration: none;"
                                                                title="View on BSCScan">
                                                                <i class="fa-solid fa-arrow-up-right-from-square"></i> {{ Str::limit($txHash, 14) }}
                                                            </a>
                                                        @else
                                                            <span style="color: #666;">&mdash;</span>
                                                        @endif
                                                    </td>
                                                @endif
                                                <td>
                                                    <span class="status-pill {{ $w->status }}">
                                                        {{ ucfirst($w->status) }}
                                                    </span>
                                                </td>
                                                <td><span style="font-size: 0.78rem; color: var(--text-muted); white-space: nowrap;">{{ $w->created_at->format('d M, Y h:i A') }}</span></td>
                                                <td>
                                                    @if(in_array($w->status, ['completed', 'paid']))
                                                        <span style="font-size: 0.78rem; color: var(--text-muted); white-space: nowrap;">{{ $w->updated_at->format('d M, Y h:i A') }}</span>
                                                    @else
                                                        <span style="color: #666;">&mdash;</span>
                                                    @endif
                                                </td>
                                                <td style="text-align: right;">
                                                    <div style="display: flex; justify-content: flex-end; gap: 5px;">
                                                        <a class="action-circle-btn view" href="{{ route('admin.withdrawals.show', $w) }}" title="View Details">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>

                                                        @if ($w->status === 'pending')
                                                            <form action="{{ route('admin.withdrawals.approve', $w) }}" method="POST" style="display: inline; margin: 0;" onsubmit="return confirm('Approve this withdrawal request for on-chain contract processing?')">
                                                                @csrf
                                                                <button type="submit" class="action-circle-btn approve" title="Approve Request">
                                                                    <i class="fa-solid fa-check"></i>
                                                                </button>
                                                                </form>
                                                                <button type="button" class="action-circle-btn reject" title="Reject & Refund" onclick="showRejectModal({{ $w->id }})">
                                                                    <i class="fa-solid fa-xmark"></i>
                                                                </button>
                                                        @endif

                                                                @if ($w->status === 'approved')
                                                                    <button type="button" class="action-circle-btn reject" title="Reject & Refund" onclick="showRejectModal({{ $w->id }})">
                                                                        <i class="fa-solid fa-xmark"></i>
                                                                    </button>
                                                                @endif
                                                                </div>
                                                                </td>
                                                                </tr>
                    @empty
                                                <tr>
                                                    <td colspan="12" style="text-align: center; padding: 3.5rem 1rem; color: var(--text-muted);">
                                                        <i class="fa-solid fa-money-bill-transfer"
                                                            style="font-size: 2.8rem; color: rgba(245, 184, 46, 0.25); display: block; margin-bottom: 10px;"></i>
                                                        <p style="font-size: 0.92rem; margin: 0;">No withdrawal requests found under this status filter.</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                            @if ($withdrawals->count() > 0)
                                                <tfoot
                                                    style="background: rgba(0, 0, 0, 0.45); font-weight: 700; font-size: 0.82rem; border-top: 1px solid var(--border-gold);">
                                                    <tr>
                                                        <td colspan="{{ in_array($activeTab, ['pending', 'approved']) ? 3 : 2 }}"
                                                            style="text-align: right; color: var(--gold-light); text-transform: uppercase;">Subtotal:</td>
                                                        <td style="color: #fff;">${{ number_format($withdrawals->sum(fn($w) => $w->amount), 2) }}</td>
                                                        <td style="color: #ef4444;">-${{ number_format($withdrawals->sum(fn($w) => round($w->amount * 0.1, 2)), 2) }}
                                                        </td>
                                                        <td style="color: #10b981;">
                                                            ${{ number_format($withdrawals->sum(fn($w) => $w->net_usd > 0 ? $w->net_usd : round($w->amount * 0.9, 2)), 2) }}
                                                        </td>
                                                        <td colspan="{{ $activeTab === 'completed' ? 7 : 6 }}"></td>
                                                    </tr>
                                                </tfoot>
                                            @endif
                                            </table>
                                            </div>

                                            <div
                                                style="padding: 1rem 1.6rem; border-top: 1px solid rgba(245, 184, 46, 0.15); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                                                <span style="color: var(--text-muted); font-size: 0.82rem;">Showing {{ $withdrawals->firstItem() ?? 0 }} &ndash;
                                                    {{ $withdrawals->lastItem() ?? 0 }} of {{ $withdrawals->total() }} Records</span>
                                                <div>{{ $withdrawals->links() }}</div>
                                            </div>
                                            </div>

                                            {{-- Reject Modal (Binalex Glassmorphism) --}}
                                            <div class="binalex-modal" id="rejectModal" tabindex="-1">
                                                <div class="binalex-modal-dialog">
                                                    <form id="rejectForm" method="POST" action="" style="width: 100%;">
                                                        @csrf
                                                        <div class="binalex-modal-content">
                                                            <div class="binalex-modal-header" style="background: rgba(239, 68, 68, 0.12);">
                                                                <h6 style="color: #ef4444; font-weight: 800; margin: 0; font-size: 1rem;">
                                                                    <i class="fa-solid fa-triangle-exclamation me-2"></i> Reject Withdrawal & Refund Coins
                                                                </h6>
                                                                <button type="button" class="btn-modal-close" onclick="closeModal('rejectModal')">&times;</button>
                                                            </div>
                                                            <div class="binalex-modal-body">
                                                                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 12px;">
                                                                    Rejecting this request will immediately refund the withdrawn coins back to the user's
                                                                    <strong>Earning Wallet (working_coin_balance)</strong>.
                                                                </p>
                                                                <label
                                                                    style="display: block; font-size: 0.78rem; color: var(--gold-light); font-weight: 700; margin-bottom: 6px; text-transform: uppercase;">
                                                                    Rejection Reason *
                                                                </label>
                                                                <textarea name="rejection_reason" class="form-control-custom" rows="3" required
                                                                    placeholder="Please provide the exact reason for rejection..."
                                                                    style="width: 100%; box-sizing: border-box;"></textarea>
                                                            </div>
                                                            <div class="binalex-modal-footer">
                                                                <button type="button" class="btn-gold"
                                                                    style="background: rgba(255,255,255,0.08); color: #fff; padding: 0.5rem 1rem;"
                                                                    onclick="closeModal('rejectModal')">Cancel</button>
                                                                <button type="submit" class="btn-gold"
                                                                    style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); color: #fff; box-shadow: 0 0 15px rgba(239, 68, 68, 0.4);">
                                                                    <i class="fa-solid fa-rotate-left"></i> Confirm Reject & Refund
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            {{-- Bulk Smart Contract Modal (Binalex Glassmorphism) --}}
                                            <div class="binalex-modal" id="bulkModal" tabindex="-1">
                                                <div class="binalex-modal-dialog lg">
                                                    <div class="binalex-modal-content">
                                                        <div class="binalex-modal-header">
                                                            <h6 style="color: var(--gold-light); font-weight: 800; margin: 0; font-size: 1.05rem;">
                                                                <i class="fa-solid fa-bolt" style="color: var(--gold-primary);"></i> Smart Contract Bulk Payout
                                                                Engine
                                                            </h6>
                                                            <button type="button" class="btn-modal-close" onclick="closeModal('bulkModal')">&times;</button>
                                                        </div>
                                                        <div class="binalex-modal-body">

                                                            {{-- Step 1: Confirm Details --}}
                                                            <div id="stepConfirm">
                                                                <h6 style="color: var(--gold-light); margin-bottom: 12px; font-weight: 800; font-size: 0.95rem;">
                                                                    Review & Verify Payout Recipients
                                                                </h6>
                                                                <div
                                                                    style="overflow-x: auto; margin-bottom: 1.2rem; border: 1px solid var(--border-gold); border-radius: 12px;">
                                                                    <table
                                                                        style="width: 100%; border-collapse: collapse; font-size: 0.82rem; background: rgba(0,0,0,0.35);">
                                                                        <thead>
                                                                            <tr
                                                                                style="border-bottom: 1px solid var(--border-gold); background: rgba(245, 184, 46, 0.08);">
                                                                                <th style="padding: 10px 12px; text-align: left; color: var(--gold-light);">ID</th>
                                                                                <th style="padding: 10px 12px; text-align: left; color: var(--gold-light);">User
                                                                                </th>
                                                                                <th style="padding: 10px 12px; text-align: left; color: var(--gold-light);">BEP-20
                                                                                    Wallet Address</th>
                                                                                <th style="padding: 10px 12px; text-align: right; color: var(--gold-light);">Net
                                                                                    Amount (USDT)</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody id="confirmRows"></tbody>
                                                                        <tfoot>
                                                                            <tr
                                                                                style="border-top: 1px solid var(--border-gold); font-weight: 800; background: rgba(0,0,0,0.5);">
                                                                                <th colspan="3"
                                                                                    style="padding: 10px 12px; text-align: right; color: var(--gold-light); text-transform: uppercase;">
                                                                                    Total Payout:</th>
                                                                                <th id="modalTotal"
                                                                                    style="padding: 10px 12px; text-align: right; color: #10b981; font-size: 0.95rem;">
                                                                                    $0.00</th>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                        <div style="background: rgba(245, 184, 46, 0.08); border: 1px solid var(--border-gold); border-radius: 12px; padding: 12px 16px; font-size: 0.85rem; color: var(--gold-light);">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i> Ensure MetaMask is connected to <strong>BSC Mainnet (Chain ID: 56)</strong> with the <strong>Contract Owner</strong> account and you have sufficient BNB for gas.
                        </div>
                    </div>

                    {{-- Step 2: Processing with Gold Spinner --}}
                    <div id="stepProcessing" style="display:none; text-align: center; padding: 2.5rem 1rem;">
                        <div style="display: inline-block; width: 3.5rem; height: 3.5rem; border: 4px solid rgba(245, 184, 46, 0.2); border-top-color: var(--gold-primary); border-radius: 50%; animation: goldSpinner 0.8s linear infinite; margin-bottom: 1.2rem;"></div>
                        <h4 id="procTitle" style="color: #fff; margin-bottom: 8px; font-weight: 800;">Connecting to MetaMask...</h4>
                        <p style="color: var(--text-muted); font-size: 0.88rem; margin: 0;" id="procSub">Please confirm in your wallet extension</p>
                    </div>

                    {{-- Step 3: Success Step --}}
                    <div id="stepSuccess" style="display:none; text-align: center; padding: 2.5rem 1rem;">
                        <i class="fa-solid fa-circle-check" style="font-size: 3.8rem; color: #10b981; display: block; margin-bottom: 1rem; filter: drop-shadow(0 0 15px rgba(16, 185, 129, 0.5));"></i>
                        <h4 style="color: #10b981; margin-bottom: 6px; font-weight: 800;">Smart Contract Payout Executed!</h4>
                        <p style="color: var(--text-muted); font-size: 0.88rem; margin-bottom: 8px;">BSC Transaction Hash:</p>
                        <code id="txHash" style="display: block; margin-bottom: 1.2rem; color: var(--gold-light); word-break: break-all; font-size: 0.85rem; background: rgba(0,0,0,0.6); padding: 10px 14px; border-radius: 8px; border: 1px solid var(--border-gold);"></code>
                        <a id="bscLink" href="#" target="_blank" class="btn-gold" style="font-size: 0.85rem; padding: 0.6rem 1.4rem;">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View on BSCScan Explorer
                        </a>
                    </div>

                    {{-- Step 4: Error Step --}}
                    <div id="stepError" style="display:none; text-align: center; padding: 2.5rem 1rem;">
                        <i class="fa-solid fa-circle-xmark" style="font-size: 3.8rem; color: #ef4444; display: block; margin-bottom: 1rem; filter: drop-shadow(0 0 15px rgba(239, 68, 68, 0.5));"></i>
                        <h4 style="color: #ef4444; margin-bottom: 6px; font-weight: 800;">Transaction Failed</h4>
                        <p style="color: var(--text-muted); font-size: 0.88rem; margin: 0;" id="errMsg"></p>
                    </div>

                </div>
                <div class="binalex-modal-footer">
                    <button type="button" class="btn-gold" style="background: rgba(255,255,255,0.08); color: #fff; padding: 0.5rem 1rem;" onclick="closeModal('bulkModal')" id="btnClose">Close</button>
                    <button type="button" class="btn-gold" id="btnProcess" onclick="processWithdrawal()" style="padding: 0.55rem 1.4rem; font-weight: 800;">
                        <i class="fa-solid fa-wallet me-1"></i> Connect Wallet & Process
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/web3@1.8.0/dist/web3.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bignumber.js@9.1.1/bignumber.min.js"></script>
    <script>
        // =========================================================================
        // CONTRACT CONFIGURATION (IDENTICAL TO FUNEARN)
        // You can paste your Contract Address and ABI directly here
        // =========================================================================
        const CONTRACT_ADDRESS = "0xd69F8458CeE7381b2D9272a5dB27fa39699bB138";
        
        const CONTRACT_ABI = 
          [{"inputs":[{"internalType":"address","name":"_usdtTokenAddress","type":"address"},{"internalType":"address","name":"_admin","type":"address"},{"internalType":"address","name":"_FeeAddress","type":"address"}],"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"previousAdmin","type":"address"},{"indexed":true,"internalType":"address","name":"newAdmin","type":"address"}],"name":"AdminChanged","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"user","type":"address"},{"indexed":false,"internalType":"uint256","name":"amount","type":"uint256"},{"indexed":false,"internalType":"uint256","name":"timestamp","type":"uint256"}],"name":"Deposit","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"token","type":"address"},{"indexed":false,"internalType":"uint256","name":"amount","type":"uint256"},{"indexed":true,"internalType":"address","name":"to","type":"address"}],"name":"OwnerWithdrawal","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"previousOwner","type":"address"},{"indexed":true,"internalType":"address","name":"newOwner","type":"address"}],"name":"OwnershipTransferred","type":"event"},{"anonymous":false,"inputs":[{"indexed":false,"internalType":"address","name":"account","type":"address"}],"name":"Paused","type":"event"},{"anonymous":false,"inputs":[{"indexed":false,"internalType":"address[]","name":"recipients","type":"address[]"},{"indexed":false,"internalType":"uint256[]","name":"amounts","type":"uint256[]"}],"name":"TokensWithdrawn","type":"event"},{"anonymous":false,"inputs":[{"indexed":false,"internalType":"address","name":"account","type":"address"}],"name":"Unpaused","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"user","type":"address"},{"indexed":false,"internalType":"uint256","name":"amount","type":"uint256"},{"indexed":false,"internalType":"uint256","name":"timestamp","type":"uint256"}],"name":"Withdrawal","type":"event"},{"inputs":[],"name":"FeeAddress","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"_token","type":"address"},{"internalType":"uint256","name":"_amount","type":"uint256"},{"internalType":"address","name":"_to","type":"address"}],"name":"OwnerWithdraw","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"admin","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"_newAdmin","type":"address"}],"name":"changeAdmin","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"uint256","name":"_amount","type":"uint256"}],"name":"deposit","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"getContractBalance","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"getContractInfo","outputs":[{"internalType":"address","name":"contractOwner","type":"address"},{"internalType":"address","name":"contractAdmin","type":"address"},{"internalType":"bool","name":"isPaused","type":"bool"},{"internalType":"uint256","name":"contractTotalDeposits","type":"uint256"},{"internalType":"uint256","name":"contractTotalWithdrawals","type":"uint256"},{"internalType":"uint256","name":"contractUSDTBalance","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"_user","type":"address"}],"name":"getUserBalance","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"owner","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"pause","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"paused","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"totalDeposits","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"totalWithdrawals","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"_newOwner","type":"address"}],"name":"transferOwnership","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"unpause","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"usdtToken","outputs":[{"internalType":"contract IBEP20","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"","type":"address"}],"name":"userBalances","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"uint256","name":"_amount","type":"uint256"}],"name":"withdraw","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address[]","name":"recipients","type":"address[]"},{"internalType":"uint256[]","name":"amounts","type":"uint256[]"}],"name":"withdrawTokens","outputs":[],"stateMutability":"nonpayable","type":"function"}];

        let selectedRows = [];

        // ── Modal Helpers ────────────────────────────────────────────────────────────
        function openModal(id) {
            const modalEl = document.getElementById(id);
            if (modalEl) modalEl.classList.add('show');
        }

        function closeModal(id) {
            const modalEl = document.getElementById(id);
            if (modalEl) modalEl.classList.remove('show');
        }

        // ── Selection helpers ────────────────────────────────────────────────────────
        function toggleAll(cb) {
            document.querySelectorAll('.row-check').forEach(c => {
                c.checked = cb.checked;
            });
            updateSelection();
        }

        function updateSelection() {
            selectedRows = [];
            let total = 0;
            document.querySelectorAll('.row-check:checked').forEach(c => {
                const amt = parseFloat(c.dataset.amount);
                selectedRows.push({
                    id: c.value,
                    wallet: c.dataset.wallet,
                    amount: amt,
                    user: c.dataset.user
                });
                total += amt;
            });
            const countEl = document.getElementById('selectedCount');
            const totalEl = document.getElementById('totalAmount');
            if (countEl) countEl.textContent = selectedRows.length;
            if (totalEl) totalEl.textContent = '$' + total.toFixed(2);
            const bulkBar = document.getElementById('bulkActionsBar');
            if (bulkBar) {
                bulkBar.style.display = selectedRows.length > 0 ? 'flex' : 'none';
            }
        }

        function clearSelection() {
            const selectAll = document.getElementById('selectAll');
            if (selectAll) selectAll.checked = false;
            document.querySelectorAll('.row-check').forEach(c => c.checked = false);
            updateSelection();
        }

        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        // ── Bulk Approve ─────────────────────────────────────────────────────────────
        async function bulkApprove() {
            if (!selectedRows.length) {
                alert('Select at least one request.');
                return;
            }
            if (!confirm(`Approve ${selectedRows.length} withdrawal(s)?`)) return;

            const ids = selectedRows.map(r => r.id);
            try {
                const res = await fetch('{{ route('admin.withdrawals.bulk-approve') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        withdrawal_ids: ids
                    }),
                });
                const data = await res.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to approve.');
                }
            } catch (e) {
                alert('An error occurred: ' + e.message);
            }
        }

        // ── Reject modal ─────────────────────────────────────────────────────────────
        function showRejectModal(id) {
            document.getElementById('rejectForm').action = `/admin/withdrawals/${id}/reject`;
            openModal('rejectModal');
        }

        // ── Bulk modal ───────────────────────────────────────────────────────────────
        function showBulkModal() {
            if (!selectedRows.length) {
                alert('Select at least one request.');
                return;
            }

            let rows = '', total = 0;
            selectedRows.forEach(r => {
                total += r.amount;
                rows += `<tr>
                            <td style="padding: 10px 12px; color: var(--text-muted); font-weight: 700;">#${r.id}</td>
                            <td style="padding: 10px 12px; color: #fff; font-weight: 600;">${r.user}</td>
                            <td style="padding: 10px 12px;"><code style="font-size: 0.78rem; color: var(--gold-light); background: rgba(0,0,0,0.5); padding: 2px 6px; border-radius: 4px;">${r.wallet.slice(0, 10)}...${r.wallet.slice(-8)}</code></td>
                            <td style="padding: 10px 12px; text-align: right; color: #10b981; font-weight: 800;">$${r.amount.toFixed(2)}</td>
                        </tr>`;
            });
            document.getElementById('confirmRows').innerHTML = rows;
            document.getElementById('modalTotal').textContent = '$' + total.toFixed(2);

            showStep('confirm');
            document.getElementById('btnProcess').style.display = 'inline-block';
            document.getElementById('btnProcess').textContent = 'Connect Wallet & Process';
            document.getElementById('btnClose').textContent = 'Close';

            openModal('bulkModal');
        }

        function showStep(step) {
            ['stepConfirm', 'stepProcessing', 'stepSuccess', 'stepError'].forEach(s => {
                const el = document.getElementById(s);
                if (el) {
                    el.style.display = s === 'step' + step.charAt(0).toUpperCase() + step.slice(1) ? 'block' : 'none';
                }
            });
        }

        function setProc(title, sub) {
            document.getElementById('procTitle').textContent = title;
            document.getElementById('procSub').textContent = sub;
        }

        // ── Main withdrawal flow ─────────────────────────────────────────────────────
        async function processWithdrawal() {
            try {
                showStep('processing');
                document.getElementById('btnProcess').style.display = 'none';

                setProc('Checking MetaMask...', 'Please make sure MetaMask is installed');
                if (typeof window.ethereum === 'undefined') throw new Error('MetaMask extension is not detected in your browser.');

                setProc('Initializing Web3...', 'Connecting to Binance Smart Chain');
                const web3 = new Web3(window.ethereum);

                setProc('Requesting wallet access...', 'Approve the connection in MetaMask');
                const accounts = await window.ethereum.request({
                    method: 'eth_requestAccounts'
                });
                const userAccount = accounts[0];

                setProc('Verifying network...', 'Must be BSC Mainnet (Chain ID 56)');
                const chainId = await web3.eth.getChainId();
                if (chainId !== 56) throw new Error('Please switch to BSC Mainnet (Chain ID: 56) in MetaMask.');

                setProc('Loading smart contract...', 'Connecting to withdrawal contract');
                const contract = new web3.eth.Contract(CONTRACT_ABI, CONTRACT_ADDRESS);

                setProc('Verifying permissions...', 'Checking contract ownership');
                const owner = await contract.methods.owner().call();
                if (owner.toLowerCase() !== userAccount.toLowerCase()) {
                    throw new Error('Only the contract owner can execute bulk withdrawals. Your address: ' + userAccount);
                }

                setProc('Preparing transaction...', 'Converting amounts to wei (18 decimals)');
                const recipients = selectedRows.map(r => r.wallet);
                const amounts = selectedRows.map(r =>
                    new BigNumber(r.amount).multipliedBy('1000000000000000000').toFixed(0)
                );

                setProc('Sending transaction...', 'Please confirm the transaction in MetaMask');
                const tx = await contract.methods.withdrawTokens(recipients, amounts).send({
                    from: userAccount
                });
                const txHash = tx.transactionHash;

                setProc('Updating records...', 'Saving transaction hash to database');
                await markCompleted(txHash);

                // Success
                document.getElementById('txHash').textContent = txHash;
                document.getElementById('bscLink').href = `https://bscscan.com/tx/${txHash}`;
                showStep('success');
                document.getElementById('btnClose').textContent = 'Done';
                setTimeout(() => window.location.reload(), 3000);

            } catch (err) {
                console.error(err);
                document.getElementById('errMsg').textContent = err.message || 'An unexpected error occurred.';
                showStep('error');
                document.getElementById('btnProcess').style.display = 'inline-block';
                document.getElementById('btnProcess').textContent = 'Try Again';
                document.getElementById('btnClose').textContent = 'Close';
            }
        }

        async function markCompleted(txHash) {
            const ids = selectedRows.map(r => r.id);
            const res = await fetch('{{ route('admin.withdrawals.bulk-complete') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    withdrawal_ids: ids,
                    transaction_hash: txHash
                }),
            });
            if (!res.ok) {
                const err = await res.json();
                throw new Error(err.message || 'Failed to update database.');
            }
            return res.json();
        }
    </script>
@endpush
