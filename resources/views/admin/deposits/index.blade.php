@extends('admin.layouts.app')

@section('title', 'Manage USDT Deposits & Add Funds')

@section('content')
    <style>
        .credit-fund-card {
            background: linear-gradient(135deg, rgba(245, 184, 46, 0.12) 0%, rgba(18, 24, 38, 0.95) 100%);
            border: 1px solid var(--border-gold);
            border-radius: 18px;
            padding: 1.5rem;
            margin-bottom: 1.8rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        }
        .form-control-custom {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(8, 10, 15, 0.8);
            border: 1px solid var(--border-gold);
            border-radius: 10px;
            color: #fff;
            font-size: 0.9rem;
            outline: none;
            transition: 0.3s;
        }
        .form-control-custom:focus {
            border-color: var(--gold-primary);
            box-shadow: var(--gold-glow);
        }
        .summary-cards-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .summary-card {
            background: rgba(18, 24, 38, 0.92);
            border: 1px solid var(--border-gold);
            border-radius: 14px;
            padding: 1rem 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.9rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        .summary-card .sc-icon {
            font-size: 1.6rem;
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .summary-card .sc-label { font-size: 0.72rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .summary-card .sc-value { font-size: 1.25rem; font-weight: 800; color: #fff; }
        .alert-flash {
            padding: 0.9rem 1.2rem;
            border-radius: 12px;
            margin-bottom: 1.2rem;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }
        .alert-flash.success { background: rgba(34,197,94,0.15); border: 1px solid rgba(34,197,94,0.4); color: #4ade80; }
        .alert-flash.warning { background: rgba(245,184,46,0.12); border: 1px solid rgba(245,184,46,0.4); color: var(--gold-light); }
        .alert-flash.info    { background: rgba(56,189,248,0.12); border: 1px solid rgba(56,189,248,0.4); color: #38bdf8; }
        .alert-flash.error   { background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.4); color: #f87171; }
        .btn-verify {
            background: linear-gradient(135deg, #1e3a5f, #0f4c75);
            border: 1px solid #38bdf8;
            color: #38bdf8;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            text-decoration: none;
        }
        .btn-verify:hover {
            background: rgba(56,189,248,0.25);
            box-shadow: 0 0 10px rgba(56,189,248,0.4);
            color: #7dd3fc;
        }
        .btn-verify-all {
            background: linear-gradient(135deg, rgba(56,189,248,0.2), rgba(14,165,233,0.3));
            border: 1px solid #38bdf8;
            color: #38bdf8;
            padding: 0.5rem 1.1rem;
            border-radius: 10px;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-verify-all:hover {
            background: rgba(56,189,248,0.3);
            box-shadow: 0 0 14px rgba(56,189,248,0.35);
        }
    </style>

    {{-- Page Header --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-wallet"></i> Deposit History</h2>
            <p style="color: var(--text-muted);">Monitor all user deposit transactions, gateway recharges, and admin wallet fund credits.</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            {{-- Verify All Pending --}}
            <form action="{{ route('admin.deposits.verify-all') }}" method="POST"
                  data-confirm="Verify ALL pending iPaymentWallet deposits now? This will check each one against the gateway and auto-approve confirmed payments.">
                @csrf
                <button type="submit" class="btn-verify-all">
                    <i class="fa-solid fa-rotate"></i> Verify All Pending
                </button>
            </form>
            <button class="btn-gold" onclick="toggleAddFundForm()"><i class="fa-solid fa-plus-circle"></i> Add / Credit Funds to User</button>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert-flash success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="alert-flash warning"><i class="fa-solid fa-triangle-exclamation"></i> {{ session('warning') }}</div>
    @endif
    @if(session('info'))
        <div class="alert-flash info"><i class="fa-solid fa-circle-info"></i> {{ session('info') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-flash error"><i class="fa-solid fa-circle-xmark"></i> {{ $errors->first() }}</div>
    @endif

    {{-- Summary Cards --}}
    <div class="summary-cards-row">
        <div class="summary-card">
            <div class="sc-icon" style="background: rgba(245,184,46,0.15); color: var(--gold-light);">
                <i class="fa-solid fa-dollar-sign"></i>
            </div>
            <div>
                <div class="sc-label">Total Approved</div>
                <div class="sc-value">${{ number_format($summary['total_approved'], 2) }}</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="sc-icon" style="background: rgba(34,197,94,0.15); color: #4ade80;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <div class="sc-label">Approved</div>
                <div class="sc-value">{{ number_format($summary['approved']) }}</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="sc-icon" style="background: rgba(245,184,46,0.15); color: #fcd34d;">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <div class="sc-label">Pending</div>
                <div class="sc-value">{{ number_format($summary['pending']) }}</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="sc-icon" style="background: rgba(239,68,68,0.15); color: #f87171;">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <div>
                <div class="sc-label">Rejected</div>
                <div class="sc-value">{{ number_format($summary['rejected']) }}</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="sc-icon" style="background: rgba(156,163,175,0.15); color: #9ca3af;">
                <i class="fa-solid fa-ban"></i>
            </div>
            <div>
                <div class="sc-label">Failed / Expired</div>
                <div class="sc-value">{{ number_format($summary['failed']) }}</div>
            </div>
        </div>
    </div>

    {{-- Admin Add / Credit Funds Form Card (Togglable) --}}
    <div class="credit-fund-card" id="addFundCard" style="display: none;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem;">
            <h3 style="color: var(--gold-light); font-size: 1.2rem;"><i class="fa-solid fa-hand-holding-dollar"></i> Direct Admin Deposit Wallet Credit / Debit</h3>
            <button class="btn-gold" style="padding: 4px 10px; font-size: 0.8rem; background: rgba(255,255,255,0.1); color: #fff;" onclick="toggleAddFundForm()"><i class="fa-solid fa-xmark"></i> Close</button>
        </div>

        <form action="{{ route('admin.deposits.credit') }}" method="POST"
            data-confirm="Are you sure you want to process this Deposit Wallet transaction for the selected user?">
            @csrf
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.2rem; margin-bottom: 1.2rem;">
                <div>
                    <label style="font-size: 0.8rem; color: var(--gold-light); font-weight: 700; display: block; margin-bottom: 6px;">SELECT USER ACCOUNT *</label>
                    <select name="user_id" class="form-control-custom" required>
                        <option value="">-- Choose Member --</option>
                        @foreach($allUsers as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->referral_code }}) - Bal: ${{ number_format($u->usdt_balance, 2) }} USDT</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="font-size: 0.8rem; color: var(--gold-light); font-weight: 700; display: block; margin-bottom: 6px;">AMOUNT ($ USDT) *</label>
                    <input type="number" step="0.01" min="0.01" name="amount_usd" class="form-control-custom" placeholder="e.g. 50.00" required>
                </div>

                <div>
                    <label style="font-size: 0.8rem; color: var(--gold-light); font-weight: 700; display: block; margin-bottom: 6px;">TRANSACTION ACTION *</label>
                    <select name="txn_type" class="form-control-custom" required>
                        <option value="credit">➕ CREDIT (Add USDT to Deposit Wallet)</option>
                        <option value="debit">➖ DEBIT (Deduct USDT from Deposit Wallet)</option>
                    </select>
                </div>

                <div>
                    <label style="font-size: 0.8rem; color: var(--gold-light); font-weight: 700; display: block; margin-bottom: 6px;">REASON / ADMIN REMARKS *</label>
                    <input type="text" name="admin_note" class="form-control-custom" placeholder="e.g. Manual Cash Deposit Received" required>
                </div>
            </div>

            <button type="submit" class="btn-gold"><i class="fa-solid fa-check-circle"></i> Confirm & Process Deposit Wallet Balance</button>
        </form>
    </div>

    {{-- Search & Status Filter Bar --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; gap: 0.8rem; flex-wrap: wrap;">
            <a href="{{ route('admin.deposits.index', ['status' => 'all']) }}" class="btn-gold"
                style="font-size: 0.8rem; {{ $status === 'all' ? '' : 'background: rgba(255,255,255,0.1); color: var(--text-main);' }}">All Deposits</a>
            <a href="{{ route('admin.deposits.index', ['status' => 'pending']) }}" class="btn-gold"
                style="font-size: 0.8rem; {{ $status === 'pending' ? '' : 'background: rgba(255,255,255,0.1); color: var(--text-main);' }}"><i class="fa-solid fa-clock"></i> Pending Review</a>
            <a href="{{ route('admin.deposits.index', ['status' => 'approved']) }}" class="btn-gold"
                style="font-size: 0.8rem; {{ $status === 'approved' ? '' : 'background: rgba(255,255,255,0.1); color: var(--text-main);' }}"><i class="fa-solid fa-circle-check"></i> Approved</a>
            <a href="{{ route('admin.deposits.index', ['status' => 'rejected']) }}" class="btn-gold"
                style="font-size: 0.8rem; {{ $status === 'rejected' ? '' : 'background: rgba(255,255,255,0.1); color: var(--text-main);' }}"><i class="fa-solid fa-circle-xmark"></i> Rejected</a>
            <a href="{{ route('admin.deposits.index', ['status' => 'failed']) }}" class="btn-gold"
                style="font-size: 0.8rem; {{ $status === 'failed' ? '' : 'background: rgba(255,255,255,0.1); color: var(--text-main);' }}"><i class="fa-solid fa-ban"></i> Failed / Expired</a>
        </div>

        <form action="{{ route('admin.deposits.index') }}" method="GET" style="display: flex; gap: 8px;">
            <input type="hidden" name="status" value="{{ $status }}">
            <input type="text" name="search" class="form-control-custom" style="padding: 0.5rem 0.9rem; width: 240px;"
                placeholder="Search name, code, hash..." value="{{ request('search') }}">
            <button type="submit" class="btn-gold" style="padding: 0.5rem 1rem;"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>

    {{-- Deposit History Table --}}
    <div class="card">
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>User Profile</th>
                        <th>Deposit Amount</th>
                        <th>Method & Source</th>
                        <th>TxHash / Reference</th>
                        <th>Submitted</th>
                        <th>Paid At</th>
                        <th>Admin Remarks</th>
                        <th>Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deposits as $dep)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div class="user-profile-avatar">
                                        {{ strtoupper(substr($dep->user->name ?? $dep->user->email ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong style="color: #fff;">{{ $dep->user->name ?? 'User' }}</strong>
                                        <div style="font-size: 0.78rem; color: var(--gold-light);">
                                            {{ $dep->user->referral_code ?? '' }}
                                            @if($dep->user)
                                                <i class="fa-solid fa-copy copy-icon-btn"
                                                    onclick="copyToClipboard('{{ $dep->user->referral_code }}', 'Self ID')"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="color: var(--gold-light); font-weight: 800; font-size: 1rem;">
                                ${{ number_format(abs($dep->amount_usd), 2) }} <span style="font-size: 0.75rem; color: var(--text-muted);">USDT</span>
                                @if($dep->amount_usd < 0)
                                    <span class="badge-status badge-rejected" style="font-size: 0.65rem; padding: 2px 6px;">DEBIT</span>
                                @endif
                            </td>
                            <td>
                                <span style="font-weight: 700; color: #38bdf8;">{{ $dep->payment_method ?? 'USDT BEP20' }}</span>
                            </td>
                            <td>
                                <div style="font-family: monospace; font-size: 0.82rem; color: var(--text-muted); max-width: 180px; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $dep->transaction_hash ?? 'N/A' }}
                                </div>
                                @if($dep->transaction_hash)
                                    <i class="fa-solid fa-copy copy-icon-btn"
                                        onclick="copyToClipboard('{{ $dep->transaction_hash }}', 'TxHash')"></i>
                                @endif
                            </td>
                            <td>
                                <div style="font-size: 0.85rem; font-weight: 600; color: #fff;">{{ $dep->created_at->format('M d, Y') }}</div>
                                <div style="font-size: 0.78rem; color: var(--text-muted);">{{ $dep->created_at->format('h:i A') }}</div>
                            </td>
                            {{-- Paid At --}}
                            <td>
                                @if($dep->paid_at)
                                    <div style="font-size: 0.82rem; font-weight: 600; color: #4ade80;">{{ $dep->paid_at->format('M d, Y') }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $dep->paid_at->format('h:i A') }}</div>
                                @else
                                    <span style="font-size: 0.78rem; color: var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td style="font-size: 0.82rem; color: var(--text-muted); max-width: 160px; white-space: normal;">
                                {{ $dep->admin_note ?? 'None' }}
                            </td>
                            <td>
                                @if ($dep->status === 'approved' || $dep->status === 'success')
                                    <span class="badge-status badge-approved"><i class="fa-solid fa-circle-check"></i> SUCCESS</span>
                                @elseif($dep->status === 'pending')
                                    <span class="badge-status badge-pending"><i class="fa-solid fa-clock"></i> PENDING</span>
                                @elseif($dep->status === 'failed')
                                    <span class="badge-status" style="background:rgba(156,163,175,0.15);color:#9ca3af;border:1px solid rgba(156,163,175,0.3);padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;">
                                        <i class="fa-solid fa-ban"></i> EXPIRED
                                    </span>
                                @else
                                    <span class="badge-status badge-rejected"><i class="fa-solid fa-circle-xmark"></i> REJECTED</span>
                                @endif
                            </td>
                            <td style="text-align: center; white-space: nowrap;">
                                @if($dep->status === 'pending' && str_starts_with($dep->transaction_hash ?? '', 'DEP_'))
                                    {{-- Check Payment Status button (like FunEarn) --}}
                                    <form action="{{ route('admin.deposits.verify', $dep->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-verify"
                                            title="Check payment status on iPaymentWallet Gateway">
                                            <i class="fa-solid fa-rotate"></i> Check Status
                                        </button>
                                    </form>
                                @elseif($dep->status === 'pending')
                                    <span style="font-size: 0.78rem; color: var(--text-muted);"><i class="fa-solid fa-shield-check" style="color: var(--gold-light);"></i> No Gateway Ref</span>
                                @else
                                    <span style="font-size: 0.78rem; color: var(--text-muted);"><i class="fa-solid fa-shield-check" style="color: var(--gold-light);"></i> Logged</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; color: var(--text-muted); padding: 2rem;">No deposit records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">
            {{ $deposits->links() }}
        </div>
    </div>

    <script>
        function toggleAddFundForm() {
            const card = document.getElementById('addFundCard');
            if (card.style.display === 'none') {
                card.style.display = 'block';
                card.scrollIntoView({ behavior: 'smooth' });
            } else {
                card.style.display = 'none';
            }
        }
    </script>
@endsection
