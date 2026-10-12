@extends('admin.layouts.app')

@section('title', 'Withdrawal Request #' . $withdrawal->id)

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        .binalex-show-header {
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

        .binalex-card {
            background: rgba(14, 18, 28, 0.88);
            border: 1px solid var(--border-gold);
            border-radius: 18px;
            padding: 1.5rem 1.8rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        .form-control-custom {
            padding: 0.6rem 1rem;
            background: rgba(8, 10, 15, 0.8);
            border: 1px solid var(--border-gold);
            border-radius: 10px;
            color: #fff;
            font-size: 0.88rem;
            outline: none;
            transition: all 0.25s ease;
            width: 100%;
            box-sizing: border-box;
        }

        .form-control-custom:focus {
            border-color: var(--gold-primary);
            box-shadow: var(--gold-glow);
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
            justify-content: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.88rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 100%;
            box-sizing: border-box;
        }
        .status-pill.pending { background: rgba(245, 184, 46, 0.15); color: #ffd700; border: 1px solid rgba(245, 184, 46, 0.4); }
        .status-pill.approved { background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.4); }
        .status-pill.completed, .status-pill.paid { background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.4); }
        .status-pill.rejected { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.4); }
    </style>
@endpush

@section('content')
    <!-- Top Header Banner -->
    <div class="binalex-show-header">
        <div>
            <span style="color: var(--gold-light); font-size: 0.75rem; letter-spacing: 1.5px; font-weight: 800; text-transform: uppercase;">
                <i class="fa-solid fa-file-invoice-dollar"></i> PAYOUT AUDIT & DETAILS
            </span>
            <h2 style="font-size: 1.6rem; color: #fff; font-weight: 800; margin: 4px 0 2px;">
                Withdrawal Request #{{ $withdrawal->id }}
            </h2>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0;">
                Review payout parameters, user verification details, and process contract disburse or refund.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.withdrawals.index') }}" class="btn-gold" style="font-size: 0.85rem; padding: 0.6rem 1.2rem; background: rgba(255,255,255,0.08); color: #fff; text-decoration: none;">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Withdrawals
            </a>
        </div>
    </div>

    @php
        $requested = (float) $withdrawal->amount;
        $deduction = round($requested * 0.1, 2);
        $netPayout = (float) ($withdrawal->net_usd > 0 ? $withdrawal->net_usd : round($requested - $deduction, 2));
        $userWallet = $withdrawal->bep20_usdt_address ?? $withdrawal->wallet_address;
        $txHash = $withdrawal->transaction_hash ?? $withdrawal->tx_hash;
        $refNo = $withdrawal->transaction_ref ?? $withdrawal->withdraw_no;
        $isCompleted = in_array($withdrawal->status, ['completed', 'paid'], true);
    @endphp

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
        <div>
            {{-- User Information Card --}}
            <div class="binalex-card">
                <h5 style="color: var(--gold-light); font-weight: 800; font-size: 0.95rem; margin-bottom: 1rem; padding-bottom: 0.6rem; border-bottom: 1px solid var(--border-gold);">
                    <i class="fa-solid fa-user-shield me-2"></i> User Account Details
                </h5>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 0.88rem;">
                    <div>
                        <label style="color: var(--text-muted); display: block; font-size: 0.76rem; text-transform: uppercase; margin-bottom: 2px;">Full Name</label>
                        <p style="color: #fff; font-weight: 700; margin: 0;">{{ $withdrawal->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label style="color: var(--text-muted); display: block; font-size: 0.76rem; text-transform: uppercase; margin-bottom: 2px;">Email Address</label>
                        <p style="color: #fff; font-weight: 700; margin: 0;">{{ $withdrawal->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label style="color: var(--text-muted); display: block; font-size: 0.76rem; text-transform: uppercase; margin-bottom: 2px;">Referral ID</label>
                        <p style="color: var(--gold-light); font-weight: 700; margin: 0;">{{ $withdrawal->user->referral_code ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label style="color: var(--text-muted); display: block; font-size: 0.76rem; text-transform: uppercase; margin-bottom: 2px;">Account Status</label>
                        <p style="color: #10b981; font-weight: 700; margin: 0;">{{ ucfirst($withdrawal->user->status ?? 'Active') }}</p>
                    </div>
                </div>
            </div>

            {{-- Withdrawal Details Card --}}
            <div class="binalex-card">
                <h5 style="color: var(--gold-light); font-weight: 800; font-size: 0.95rem; margin-bottom: 1rem; padding-bottom: 0.6rem; border-bottom: 1px solid var(--border-gold);">
                    <i class="fa-solid fa-coins me-2"></i> Payout Financial Breakdown
                </h5>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 1.2rem; font-size: 0.88rem;">
                    <div>
                        <label style="color: var(--text-muted); display: block; font-size: 0.76rem; text-transform: uppercase; margin-bottom: 2px;">Amount Requested</label>
                        <p style="color: #fff; font-size: 1.4rem; font-weight: 800; margin: 0;">${{ number_format($requested, 2) }} USDT</p>
                    </div>
                    <div>
                        <label style="color: var(--text-muted); display: block; font-size: 0.76rem; text-transform: uppercase; margin-bottom: 2px;">Transaction Reference</label>
                        <p style="color: var(--gold-light); font-family: monospace; font-size: 1.1rem; font-weight: 800; margin: 0;">{{ $refNo ?? '—' }}</p>
                    </div>
                </div>

                <div style="margin-bottom: 1.2rem; font-size: 0.88rem;">
                    <label style="color: var(--text-muted); display: block; font-size: 0.76rem; text-transform: uppercase; margin-bottom: 4px;">BSC Transaction Hash</label>
                    @if ($txHash)
                        <p style="font-family: monospace; word-break: break-all; margin: 0 0 6px;">
                            <a href="https://bscscan.com/tx/{{ $txHash }}" target="_blank" rel="noopener" style="color: var(--gold-light); font-weight: 700; text-decoration: none; font-size: 0.85rem;">
                                {{ $txHash }} <i class="fa-solid fa-arrow-up-right-from-square small ms-1"></i>
                            </a>
                        </p>
                        @if ($isCompleted)
                            <a href="javascript:void(0)" onclick="openModal('updateHashModal')" style="font-size: 0.8rem; color: #38bdf8; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fa-solid fa-pen-to-square"></i> Update Transaction Hash
                            </a>
                        @endif
                    @else
                        @if ($isCompleted)
                            <p style="color: #666; margin: 0 0 4px;">&mdash;</p>
                            <button type="button" class="btn-gold" onclick="openModal('updateHashModal')" style="font-size: 0.78rem; padding: 0.4rem 0.9rem;">
                                <i class="fa-solid fa-plus me-1"></i> Add Transaction Hash
                            </button>
                        @else
                            <p style="color: #666; margin: 0;">&mdash;</p>
                        @endif
                    @endif
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 1.2rem; font-size: 0.88rem;">
                    <div>
                        <label style="color: var(--text-muted); display: block; font-size: 0.76rem; text-transform: uppercase; margin-bottom: 2px;">System Fee (10%)</label>
                        <p style="color: #ef4444; font-weight: 800; font-size: 1.1rem; margin: 0;">-${{ number_format($deduction, 2) }} USDT</p>
                    </div>
                    <div>
                        <label style="color: var(--text-muted); display: block; font-size: 0.76rem; text-transform: uppercase; margin-bottom: 2px;">Net Disbursal Amount</label>
                        <p style="color: #10b981; font-size: 1.4rem; font-weight: 800; margin: 0;">${{ number_format($netPayout, 2) }} USDT</p>
                    </div>
                </div>

                <div style="margin-bottom: 1.2rem; font-size: 0.88rem;">
                    <label style="color: var(--text-muted); display: block; font-size: 0.76rem; text-transform: uppercase; margin-bottom: 4px;">BEP-20 USDT Wallet Address</label>
                    <p style="color: var(--gold-light); font-family: monospace; word-break: break-all; margin: 0; background: rgba(0,0,0,0.5); padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-gold);">{{ $userWallet }}</p>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 0.88rem;">
                    <div>
                        <label style="color: var(--text-muted); display: block; font-size: 0.76rem; text-transform: uppercase; margin-bottom: 2px;">Requested Date</label>
                        <p style="color: #fff; margin: 0;">{{ $withdrawal->created_at->format('d M, Y h:i A') }}</p>
                    </div>
                    @if ($withdrawal->rejection_reason)
                        <div>
                            <label style="color: var(--text-muted); display: block; font-size: 0.76rem; text-transform: uppercase; margin-bottom: 2px;">Rejection Reason</label>
                            <p style="color: #ef4444; margin: 0;">{{ $withdrawal->rejection_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div>
            {{-- Status Card --}}
            <div class="binalex-card">
                <h5 style="color: var(--gold-light); font-weight: 800; font-size: 0.95rem; margin-bottom: 1rem; padding-bottom: 0.6rem; border-bottom: 1px solid var(--border-gold);">
                    <i class="fa-solid fa-circle-info me-2"></i> Current Status
                </h5>
                <div>
                    <span class="status-pill {{ $withdrawal->status }}">
                        {{ ucfirst($withdrawal->status) }}
                    </span>
                </div>
            </div>

            {{-- Actions Card --}}
            @if ($withdrawal->status === 'pending')
                <div class="binalex-card">
                    <h5 style="color: var(--gold-light); font-weight: 800; font-size: 0.95rem; margin-bottom: 1rem; padding-bottom: 0.6rem; border-bottom: 1px solid var(--border-gold);">
                        <i class="fa-solid fa-sliders me-2"></i> Actions
                    </h5>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <form action="{{ route('admin.withdrawals.approve', $withdrawal) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-gold" onclick="return confirm('Approve this withdrawal of ${{ number_format($withdrawal->amount, 2) }} for on-chain disbursal?')" style="width: 100%; padding: 0.7rem; font-weight: 800; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff; box-shadow: 0 0 15px rgba(16, 185, 129, 0.4);">
                                <i class="fa-solid fa-check-double me-1"></i> Approve Request
                            </button>
                        </form>

                        <button type="button" class="btn-gold" onclick="openModal('rejectModal')" style="width: 100%; padding: 0.7rem; font-weight: 800; background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); color: #fff;">
                            <i class="fa-solid fa-xmark me-1"></i> Reject & Refund
                        </button>
                    </div>
                </div>
            @endif

            @if ($withdrawal->status === 'approved')
                <div class="binalex-card">
                    <h5 style="color: var(--gold-light); font-weight: 800; font-size: 0.95rem; margin-bottom: 1rem; padding-bottom: 0.6rem; border-bottom: 1px solid var(--border-gold);">
                        <i class="fa-solid fa-sliders me-2"></i> Actions
                    </h5>
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <button type="button" class="btn-gold" onclick="openModal('completeModal')" style="width: 100%; padding: 0.7rem; font-weight: 800; background: var(--gold-gradient); color: #000; box-shadow: var(--gold-glow);">
                            <i class="fa-solid fa-check me-1"></i> Mark as Completed
                        </button>
                        <button type="button" class="btn-gold" onclick="openModal('rejectModal')" style="width: 100%; padding: 0.7rem; font-weight: 800; background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); color: #fff;">
                            <i class="fa-solid fa-xmark me-1"></i> Reject & Refund
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Complete Modal --}}
    @if ($withdrawal->status === 'approved')
        <div class="binalex-modal" id="completeModal" tabindex="-1">
            <div class="binalex-modal-dialog">
                <div class="binalex-modal-content">
                    <div class="binalex-modal-header">
                        <h6 style="color: var(--gold-light); font-weight: 800; margin: 0; font-size: 1rem;">
                            <i class="fa-solid fa-circle-check me-2" style="color: #10b981;"></i> Mark as Completed
                        </h6>
                        <button type="button" class="btn-modal-close" onclick="closeModal('completeModal')">&times;</button>
                    </div>
                    <form action="{{ route('admin.withdrawals.complete', $withdrawal) }}" method="POST">
                        @csrf
                        <div class="binalex-modal-body">
                            <p style="color: var(--text-muted); font-size: 0.88rem; margin-bottom: 6px;">Confirm payout of <strong style="color: #10b981;">${{ number_format($netPayout, 2) }} USDT</strong> to:</p>
                            <p style="font-family: monospace; color: var(--gold-light); word-break: break-all; margin-bottom: 12px; font-size: 0.85rem; background: rgba(0,0,0,0.5); padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-gold);">{{ $userWallet }}</p>
                            <div>
                                <label style="display: block; font-size: 0.78rem; color: var(--gold-light); margin-bottom: 4px; font-weight: 700; text-transform: uppercase;">BSC Transaction Hash (optional)</label>
                                <input type="text" name="transaction_hash" placeholder="0x..." class="form-control-custom" style="font-family: monospace;">
                            </div>
                        </div>
                        <div class="binalex-modal-footer">
                            <button type="button" class="btn-gold" style="background: rgba(255,255,255,0.08); color: #fff; padding: 0.5rem 1rem;" onclick="closeModal('completeModal')">Cancel</button>
                            <button type="submit" class="btn-gold" style="padding: 0.55rem 1.4rem; font-weight: 800;">
                                <i class="fa-solid fa-check me-1"></i> Confirm Completed
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Update Transaction Hash Modal --}}
    @if ($isCompleted)
        <div class="binalex-modal" id="updateHashModal" tabindex="-1">
            <div class="binalex-modal-dialog">
                <div class="binalex-modal-content">
                    <div class="binalex-modal-header">
                        <h6 style="color: var(--gold-light); font-weight: 800; margin: 0; font-size: 1rem;">
                            <i class="fa-solid fa-pen-to-square me-2"></i> {{ $txHash ? 'Update' : 'Add' }} Transaction Hash
                        </h6>
                        <button type="button" class="btn-modal-close" onclick="closeModal('updateHashModal')">&times;</button>
                    </div>
                    <form action="{{ route('admin.withdrawals.updateHash', $withdrawal) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="binalex-modal-body">
                            <div>
                                <label style="display: block; font-size: 0.78rem; color: var(--gold-light); margin-bottom: 4px; font-weight: 700; text-transform: uppercase;">BSC Transaction Hash *</label>
                                <input type="text" name="transaction_hash" placeholder="0x..." value="{{ $txHash }}" required class="form-control-custom" style="font-family: monospace;">
                            </div>
                        </div>
                        <div class="binalex-modal-footer">
                            <button type="button" class="btn-gold" style="background: rgba(255,255,255,0.08); color: #fff; padding: 0.5rem 1rem;" onclick="closeModal('updateHashModal')">Cancel</button>
                            <button type="submit" class="btn-gold" style="padding: 0.55rem 1.4rem; font-weight: 800;">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Hash
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Reject Modal --}}
    <div class="binalex-modal" id="rejectModal" tabindex="-1">
        <div class="binalex-modal-dialog">
            <div class="binalex-modal-content">
                <div class="binalex-modal-header" style="background: rgba(239, 68, 68, 0.12);">
                    <h6 style="color: #ef4444; font-weight: 800; margin: 0; font-size: 1rem;">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i> Reject Withdrawal & Refund Coins
                    </h6>
                    <button type="button" class="btn-modal-close" onclick="closeModal('rejectModal')">&times;</button>
                </div>
                <form action="{{ route('admin.withdrawals.reject', $withdrawal) }}" method="POST">
                    @csrf
                    <div class="binalex-modal-body">
                        <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 12px;">Rejecting will immediately revert <strong style="color: #fff;">${{ number_format($requested, 2) }} USDT worth of coins</strong> back to the user's Earning Wallet.</p>
                        <div>
                            <label style="display: block; font-size: 0.78rem; color: var(--gold-light); margin-bottom: 4px; font-weight: 700; text-transform: uppercase;">Rejection Reason *</label>
                            <textarea class="form-control-custom" name="rejection_reason" rows="3" required placeholder="Enter reason for rejection..."></textarea>
                        </div>
                    </div>
                    <div class="binalex-modal-footer">
                        <button type="button" class="btn-gold" style="background: rgba(255,255,255,0.08); color: #fff; padding: 0.5rem 1rem;" onclick="closeModal('rejectModal')">Cancel</button>
                        <button type="submit" class="btn-gold" style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); color: #fff; box-shadow: 0 0 15px rgba(239, 68, 68, 0.4);">
                            <i class="fa-solid fa-rotate-left me-1"></i> Reject & Revert
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openModal(id) {
        const modalEl = document.getElementById(id);
        if (modalEl) modalEl.classList.add('show');
    }
    function closeModal(id) {
        const modalEl = document.getElementById(id);
        if (modalEl) modalEl.classList.remove('show');
    }
</script>
@endpush
