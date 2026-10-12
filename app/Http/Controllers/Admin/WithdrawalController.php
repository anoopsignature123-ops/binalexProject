<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WithdrawalController extends Controller
{
    public function index(Request $request): View
    {
        $activeTab = $request->get('tab', $request->get('status', 'pending'));

        $query = Withdrawal::with('user');

        // Status / Tab Filter
        $statusMap = [
            'pending'   => [Withdrawal::STATUS_PENDING],
            'approved'  => [Withdrawal::STATUS_APPROVED],
            'completed' => [Withdrawal::STATUS_PAID, Withdrawal::STATUS_COMPLETED],
            'rejected'  => [Withdrawal::STATUS_REJECTED],
        ];

        if (isset($statusMap[$activeTab])) {
            $query->whereIn('status', $statusMap[$activeTab]);
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('wallet_address', 'like', "%{$search}%")
                    ->orWhere('tx_hash', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('referral_code', 'like', "%{$search}%"));
            });
        }

        // Amount & Date Filters
        if ($request->filled('amount_from')) {
            $query->where('net_usd', '>=', (float) $request->amount_from);
        }
        if ($request->filled('amount_to')) {
            $query->where('net_usd', '<=', (float) $request->amount_to);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $withdrawals = $query->latest()->paginate(20)->withQueryString();

        $tabCounts = [
            'pending'   => Withdrawal::where('status', Withdrawal::STATUS_PENDING)->count(),
            'approved'  => Withdrawal::where('status', Withdrawal::STATUS_APPROVED)->count(),
            'completed' => Withdrawal::whereIn('status', [Withdrawal::STATUS_PAID, Withdrawal::STATUS_COMPLETED])->count(),
            'rejected'  => Withdrawal::where('status', Withdrawal::STATUS_REJECTED)->count(),
        ];

        $completedStats = [
            'today'      => (float) Withdrawal::whereIn('status', [Withdrawal::STATUS_PAID, Withdrawal::STATUS_COMPLETED])->whereDate('updated_at', today())->sum('net_usd'),
            'yesterday'  => (float) Withdrawal::whereIn('status', [Withdrawal::STATUS_PAID, Withdrawal::STATUS_COMPLETED])->whereDate('updated_at', today()->subDay())->sum('net_usd'),
            'this_month' => (float) Withdrawal::whereIn('status', [Withdrawal::STATUS_PAID, Withdrawal::STATUS_COMPLETED])->whereMonth('updated_at', now()->month)->whereYear('updated_at', now()->year)->sum('net_usd'),
            'all_time'   => (float) Withdrawal::whereIn('status', [Withdrawal::STATUS_PAID, Withdrawal::STATUS_COMPLETED])->sum('net_usd'),
        ];

        $pricing = \App\Models\TokenPricing::first();
        $smartContractAddress = env('SMART_CONTRACT_ADDRESS', $pricing->withdrawal_contract_address ?? $pricing->contract_address ?? '0xd69F8458CeE7381b2D9272a5dB27fa39699bB138');

        return view('admin.withdrawals.index', compact('withdrawals', 'tabCounts', 'activeTab', 'smartContractAddress', 'completedStats'));
    }

    public function show(Withdrawal $withdrawal): View
    {
        $withdrawal->load('user');

        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    public function approve(Withdrawal $withdrawal): RedirectResponse
    {
        if ($withdrawal->status !== Withdrawal::STATUS_PENDING) {
            return redirect()->back()->with('error', 'Request already processed.');
        }

        DB::transaction(function () use ($withdrawal) {
            $withdrawal->update([
                'status' => Withdrawal::STATUS_APPROVED,
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);
        });

        return redirect()->back()->with('success', 'Withdrawal approved successfully.');
    }

    public function bulkApprove(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'withdrawal_ids'   => 'required|array|min:1',
            'withdrawal_ids.*' => 'integer|exists:withdrawals,id',
        ]);

        $updated = Withdrawal::whereIn('id', $request->withdrawal_ids)
            ->where('status', Withdrawal::STATUS_PENDING)
            ->get();

        DB::transaction(function () use ($updated) {
            foreach ($updated as $withdrawal) {
                $withdrawal->update([
                    'status' => Withdrawal::STATUS_APPROVED,
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                ]);
            }
        });

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $updated->count() . ' withdrawal(s) approved.',
                'updated' => $updated->count(),
            ]);
        }

        return redirect()->back()->with('success', $updated->count() . ' withdrawal(s) approved.');
    }

    public function reject(Request $request, Withdrawal $withdrawal): RedirectResponse
    {
        $request->validate(['rejection_reason' => 'required|string|max:500']);

        DB::transaction(function () use ($withdrawal, $request) {
            $lockedWithdrawal = Withdrawal::lockForUpdate()->find($withdrawal->id);

            if (!$lockedWithdrawal) {
                throw new \Exception('Withdrawal request not found');
            }

            if (!in_array($lockedWithdrawal->status, [Withdrawal::STATUS_PENDING, Withdrawal::STATUS_APPROVED], true)) {
                throw new \Exception('Request already processed (status: ' . $lockedWithdrawal->status . ')');
            }

            // Revert coin balance back to user's working_coin_balance (Earning Wallet)
            $user = User::lockForUpdate()->find($lockedWithdrawal->user_id);
            if ($user) {
                $user->increment('working_coin_balance', $lockedWithdrawal->coins_amount);
            }

            $lockedWithdrawal->update([
                'status'           => Withdrawal::STATUS_REJECTED,
                'rejection_reason' => $request->rejection_reason,
                'admin_note'       => $request->rejection_reason,
            ]);
        });

        return redirect()->back()->with('success', 'Withdrawal rejected and amount reverted to user wallet.');
    }

    public function updateHash(Request $request, Withdrawal $withdrawal): RedirectResponse
    {
        if (!in_array($withdrawal->status, [Withdrawal::STATUS_PAID, Withdrawal::STATUS_COMPLETED], true)) {
            return redirect()->back()->with('error', 'Transaction hash can only be updated for completed withdrawals.');
        }

        $hashKey = $request->has('transaction_hash') ? 'transaction_hash' : 'tx_hash';
        $request->validate([
            $hashKey => 'required|string|max:255',
        ]);

        $withdrawal->update(['tx_hash' => trim($request->input($hashKey))]);

        return redirect()->back()->with('success', 'Transaction hash updated successfully.');
    }

    public function complete(Request $request, Withdrawal $withdrawal): RedirectResponse
    {
        if ($withdrawal->status !== Withdrawal::STATUS_APPROVED) {
            return redirect()->back()->with('error', 'Only approved withdrawals can be marked as completed.');
        }

        $hashKey = $request->has('transaction_hash') ? 'transaction_hash' : 'tx_hash';
        $request->validate([
            $hashKey => 'nullable|string|max:255',
        ]);

        $hashValue = trim($request->input($hashKey, ''));

        DB::transaction(function () use ($withdrawal, $hashValue) {
            $withdrawalUpdate = [
                'status'  => Withdrawal::STATUS_PAID,
                'paid_at' => now(),
            ];
            if (filled($hashValue)) {
                $withdrawalUpdate['tx_hash'] = $hashValue;
            }
            $withdrawal->update($withdrawalUpdate);
        });

        return redirect()->back()->with('success', 'Withdrawal marked as completed.');
    }

    public function bulkComplete(Request $request): JsonResponse|RedirectResponse
    {
        $hashKey = $request->has('transaction_hash') ? 'transaction_hash' : 'tx_hash';
        $request->validate([
            'withdrawal_ids'   => 'required|array|min:1',
            'withdrawal_ids.*' => 'integer|exists:withdrawals,id',
            $hashKey           => 'required|string|max:255',
        ]);

        $hashValue = trim($request->input($hashKey));

        $updated = Withdrawal::whereIn('id', $request->withdrawal_ids)
            ->where('status', Withdrawal::STATUS_APPROVED)
            ->get();

        DB::transaction(function () use ($updated, $hashValue) {
            foreach ($updated as $withdrawal) {
                $withdrawal->update([
                    'status'  => Withdrawal::STATUS_PAID,
                    'paid_at' => now(),
                    'tx_hash' => $hashValue,
                ]);
            }
        });

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $updated->count() . ' withdrawal(s) marked as completed.',
                'updated' => $updated->count(),
            ]);
        }

        return redirect()->back()->with('success', $updated->count() . ' withdrawal(s) marked as completed.');
    }

    public function markAsPaid(Withdrawal $withdrawal, Request $request): RedirectResponse
    {
        return $this->complete($request, $withdrawal);
    }
}
