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
    </style>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="color: var(--gold-light); margin-bottom: 0.3rem;"><i class="fa-solid fa-wallet"></i> Deposit History</h2>
            <p style="color: var(--text-muted);">Monitor all user deposit transactions, gateway recharges, and admin wallet fund
                credits.</p>
            </div>
            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <button class="btn-gold" onclick="toggleAddFundForm()"><i class="fa-solid fa-plus-circle"></i> Add / Credit Funds to
                    User</button>
            </div>
            </div>

            <!-- Admin Add / Credit Funds Form Card (Togglable) -->
            <div class="credit-fund-card" id="addFundCard" style="display: none;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem;">
                    <h3 style="color: var(--gold-light); font-size: 1.2rem;"><i class="fa-solid fa-hand-holding-dollar"></i> Direct
                        Admin Deposit Wallet Credit / Debit</h3>
                    <button class="btn-gold"
                        style="padding: 4px 10px; font-size: 0.8rem; background: rgba(255,255,255,0.1); color: #fff;"
                        onclick="toggleAddFundForm()"><i class="fa-solid fa-xmark"></i> Close</button>
                </div>

                <form action="{{ route('admin.deposits.credit') }}" method="POST"
                    data-confirm="Are you sure you want to process this Deposit Wallet transaction for the selected user?">
                    @csrf
                    <div
                        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.2rem; margin-bottom: 1.2rem;">
                        <div>
                            <label
                                style="font-size: 0.8rem; color: var(--gold-light); font-weight: 700; display: block; margin-bottom: 6px;">SELECT
                                USER ACCOUNT *</label>
                            <select name="user_id" class="form-control-custom" required>
                                <option value="">-- Choose Member --</option>
                                @foreach($allUsers as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->referral_code }}) - Bal:
                                        ${{ number_format($u->usdt_balance, 2) }} USDT</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label
                                style="font-size: 0.8rem; color: var(--gold-light); font-weight: 700; display: block; margin-bottom: 6px;">AMOUNT
                                ($ USDT) *</label>
                            <input type="number" step="0.01" min="0.01" name="amount_usd" class="form-control-custom"
                                placeholder="e.g. 50.00" required>
                        </div>

                        <div>
                            <label
                                style="font-size: 0.8rem; color: var(--gold-light); font-weight: 700; display: block; margin-bottom: 6px;">TRANSACTION
                                ACTION *</label>
                            <select name="txn_type" class="form-control-custom" required>
                                <option value="credit">➕ CREDIT (Add USDT to Deposit Wallet)</option>
                                <option value="debit">➖ DEBIT (Deduct USDT from Deposit Wallet)</option>
                            </select>
                        </div>

                        <div>
                            <label
                                style="font-size: 0.8rem; color: var(--gold-light); font-weight: 700; display: block; margin-bottom: 6px;">REASON
                                / ADMIN REMARKS *</label>
                            <input type="text" name="admin_note" class="form-control-custom"
                                placeholder="e.g. Manual Cash Deposit Received" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-gold"><i class="fa-solid fa-check-circle"></i> Confirm & Process Deposit Wallet
                        Balance</button>
                </form>
            </div>

            <!-- Search & Status Filter Bar -->
            <div
                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                <div style="display: flex; gap: 0.8rem; flex-wrap: wrap;">
                    <a href="{{ route('admin.deposits.index', ['status' => 'all']) }}" class="btn-gold"
                        style="font-size: 0.8rem; {{ $status === 'all' ? '' : 'background: rgba(255,255,255,0.1); color: var(--text-main);' }}">All
                        Deposits</a>
                    <a href="{{ route('admin.deposits.index', ['status' => 'pending']) }}" class="btn-gold"
                        style="font-size: 0.8rem; {{ $status === 'pending' ? '' : 'background: rgba(255,255,255,0.1); color: var(--text-main);' }}"><i
                            class="fa-solid fa-clock"></i> Pending Review</a>
                    <a href="{{ route('admin.deposits.index', ['status' => 'approved']) }}" class="btn-gold"
                        style="font-size: 0.8rem; {{ $status === 'approved' ? '' : 'background: rgba(255,255,255,0.1); color: var(--text-main);' }}"><i
                            class="fa-solid fa-circle-check"></i> Approved</a>
                    <a href="{{ route('admin.deposits.index', ['status' => 'rejected']) }}" class="btn-gold"
                        style="font-size: 0.8rem; {{ $status === 'rejected' ? '' : 'background: rgba(255,255,255,0.1); color: var(--text-main);' }}"><i
                            class="fa-solid fa-circle-xmark"></i> Rejected</a>
                </div>

                <form action="{{ route('admin.deposits.index') }}" method="GET" style="display: flex; gap: 8px;">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input type="text" name="search" class="form-control-custom" style="padding: 0.5rem 0.9rem; width: 240px;"
                        placeholder="Search name, code, hash..." value="{{ request('search') }}">
                    <button type="submit" class="btn-gold" style="padding: 0.5rem 1rem;"><i
                            class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>

            <!-- Deposit History Table -->
            <div class="card">
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>User Profile</th>
                                <th>Deposit Amount</th>
                                <th>Method & Source</th>
                                <th>TxHash / Reference</th>
                                <th>Submitted Date & Time</th>
                                <th>Admin Remarks</th>
                                <th>Status</th>
                                {{-- <th>Actions</th> --}}
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
                                            ${{ number_format(abs($dep->amount_usd), 2) }} <span
                                                style="font-size: 0.75rem; color: var(--text-muted);">USDT</span>
                                            @if($dep->amount_usd < 0)
                                                <span class="badge-status badge-rejected"
                                                    style="font-size: 0.65rem; padding: 2px 6px;">DEBIT</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                style="font-weight: 700; color: #38bdf8;">{{ $dep->payment_method ?? 'USDT BEP20' }}</span>
                                        </td>
                                        <td>
                                            <div
                                                style="font-family: monospace; font-size: 0.82rem; color: var(--text-muted); max-width: 180px; overflow: hidden; text-overflow: ellipsis;">
                                                {{ $dep->txn_hash ?? $dep->transaction_hash ?? 'N/A' }}
                                            </div>
                                            @if($dep->txn_hash || $dep->transaction_hash)
                                                <i class="fa-solid fa-copy copy-icon-btn"
                                                    onclick="copyToClipboard('{{ $dep->txn_hash ?? $dep->transaction_hash }}', 'TxHash')"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="font-size: 0.85rem; font-weight: 600; color: #fff;">
                                                {{ $dep->created_at->format('M d, Y') }}</div>
                                            <div style="font-size: 0.78rem; color: var(--text-muted);">
                                                {{ $dep->created_at->format('h:i A') }}</div>
                                        </td>
                                        <td style="font-size: 0.82rem; color: var(--text-muted); max-width: 160px; white-space: normal;">
                                            {{ $dep->admin_note ?? 'None' }}
                                        </td>
                                        <td>
                                            @if ($dep->status === 'approved' || $dep->status === 'success')
                                                <span class="badge-status badge-approved"><i class="fa-solid fa-circle-check"></i>
                                                    SUCCESS</span>
                                            @elseif($dep->status === 'pending')
                                                <span class="badge-status badge-pending"><i class="fa-solid fa-clock"></i> PENDING</span>
                                            @else
                                                <span class="badge-status badge-rejected"><i class="fa-solid fa-circle-xmark"></i> FAILED</span>
                                            @endif
                                        </td>
                                        {{-- <td style="text-align: right;">
                                            <span style="font-size: 0.82rem; color: var(--text-muted);"><i class="fa-solid fa-shield-check"
                                                    style="color: var(--gold-light);"></i> Logged</span>
                                        </td> --}}
                                </tr>
                            @empty
                        <tr>
                            <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 2rem;">No deposit records found.</td>
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
