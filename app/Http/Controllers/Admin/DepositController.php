<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\User;
use App\Services\PaymentGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DepositController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $query  = Deposit::with('user')->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('referral_code', 'like', "%{$search}%");
            })->orWhere('transaction_hash', 'like', "%{$search}%");
        }

        $deposits = $query->paginate(15)->withQueryString();
        $allUsers = User::whereHas('role', fn($q) => $q->where('name', 'user'))->orderBy('name')->get();

        // Summary counts
        $summary = [
            'total_approved' => Deposit::where('status', 'approved')->sum('amount_usd'),
            'approved'       => Deposit::where('status', 'approved')->count(),
            'pending'        => Deposit::where('status', 'pending')->count(),
            'rejected'       => Deposit::where('status', 'rejected')->count(),
            'failed'         => Deposit::where('status', 'failed')->count(),
        ];

        return view('admin.deposits.index', compact('deposits', 'status', 'allUsers', 'summary'));
    }

    public function update(Request $request, Deposit $deposit)
    {
        $request->validate([
            'status'     => 'required|in:approved,rejected',
            'admin_note' => 'nullable|string|max:255',
        ]);

        if ($deposit->status !== 'pending') {
            return back()->withErrors(['deposit' => 'Deposit status has already been processed.']);
        }

        $newStatus = $request->input('status');

        DB::transaction(function () use ($deposit, $newStatus, $request) {
            $deposit->status     = $newStatus;
            $deposit->admin_note = $request->input('admin_note');
            $deposit->save();

            if ($newStatus === 'approved') {
                $user               = $deposit->user;
                $user->usdt_balance += $deposit->amount_usd;
                $user->save();
            }
        });

        $action = $newStatus === 'approved' ? 'approved and user balance credited' : 'rejected';
        return redirect()->route('admin.deposits.index')
            ->with('success', "Deposit #{$deposit->id} has been {$action}.");
    }

    /**
     * Verify a single pending deposit via iPaymentWallet gateway — same as FunEarn flow.
     */
    public function verifyPayment(Deposit $deposit)
    {
        if ($deposit->status !== 'pending') {
            return back()->with('info', "Deposit #{$deposit->id} is already {$deposit->status}. No action taken.");
        }

        $txnRef = $deposit->transaction_hash;

        if (empty($txnRef) || !str_starts_with($txnRef, 'DEP_')) {
            return back()->withErrors(['verify' => "No valid gateway reference found for Deposit #{$deposit->id}."]);
        }

        $gateway       = app(PaymentGatewayService::class);
        $statusResponse = $gateway->checkPaymentStatus($txnRef);

        Log::info("Admin manual verify deposit #{$deposit->id} (Ref: {$txnRef})", $statusResponse);

        if ($statusResponse['success'] && ($statusResponse['payment_confirmed'] ?? false)) {
            DB::transaction(function () use ($deposit, $txnRef, $statusResponse) {
                $innerData = $statusResponse['data']['data'] ?? $statusResponse['data'] ?? [];

                $deposit->status     = 'approved';
                $deposit->paid_at    = now();
                $deposit->admin_note = 'Auto-approved via Gateway Verification (Ref: ' . $txnRef . ')';

                // Update txn_hash if gateway returned it
                if (!empty($innerData['tx_hash'] ?? $innerData['blockchain_tx_hash'] ?? null)) {
                    $deposit->transaction_hash = $innerData['tx_hash'] ?? $innerData['blockchain_tx_hash'];
                }

                $deposit->save();

                // Credit user balance
                $user = $deposit->user;
                if ($user) {
                    $user->usdt_balance += $deposit->amount_usd;
                    $user->save();
                }
            });

            return redirect()->route('admin.deposits.index')
                ->with('success', "✅ Deposit #{$deposit->id} (Ref: {$txnRef}) payment confirmed! User balance credited \${$deposit->amount_usd} USDT.");
        }

        // Gateway says explicitly FAILED (expired, no payment received)
        $gatewayStatus = $statusResponse['data']['status'] ?? null;
        if ($statusResponse['success'] && $gatewayStatus === 'failed') {
            $deposit->status     = 'failed';
            $deposit->admin_note = 'Gateway Verification: Payment expired / not received (Ref: ' . $txnRef . ')';
            $deposit->save();

            return redirect()->route('admin.deposits.index')
                ->with('warning', "❌ Deposit #{$deposit->id} (Ref: {$txnRef}) is FAILED on gateway — payment was not made. Status updated to Failed.");
        }

        // Payment not confirmed yet (still pending on gateway)
        $message = $statusResponse['message'] ?? 'Payment not confirmed on blockchain yet.';
        return back()->with('warning', "⚠️ Deposit #{$deposit->id} (Ref: {$txnRef}): {$message}");
    }

    /**
     * Verify ALL pending iPaymentWallet deposits at once — bulk gateway check.
     */
    public function verifyAllPending()
    {
        $pending = Deposit::where('status', 'pending')
            ->where('payment_method', 'like', '%iPayment%')
            ->whereNotNull('transaction_hash')
            ->where('transaction_hash', 'like', 'DEP_%')
            ->get();

        if ($pending->isEmpty()) {
            return back()->with('info', 'No pending iPaymentWallet deposits found to verify.');
        }

        $gateway   = app(PaymentGatewayService::class);
        $approved  = 0;
        $failed    = 0;
        $notPaid   = 0;
        $errors    = 0;

        foreach ($pending as $deposit) {
            $txnRef         = $deposit->transaction_hash;
            $statusResponse = $gateway->checkPaymentStatus($txnRef);

            Log::info("Bulk verify deposit #{$deposit->id} (Ref: {$txnRef})", $statusResponse);

            if ($statusResponse['success'] && ($statusResponse['payment_confirmed'] ?? false)) {
                // CONFIRMED — approve and credit user
                DB::transaction(function () use ($deposit, $txnRef, $statusResponse) {
                    $innerData = $statusResponse['data']['data'] ?? $statusResponse['data'] ?? [];

                    $deposit->status     = 'approved';
                    $deposit->paid_at    = now();
                    $deposit->admin_note = 'Auto-approved via Gateway Verification (Ref: ' . $txnRef . ')';

                    if (!empty($innerData['tx_hash'] ?? $innerData['blockchain_tx_hash'] ?? null)) {
                        $deposit->transaction_hash = $innerData['tx_hash'] ?? $innerData['blockchain_tx_hash'];
                    }

                    $deposit->save();

                    $user = $deposit->user;
                    if ($user) {
                        $user->usdt_balance += $deposit->amount_usd;
                        $user->save();
                    }
                });
                $approved++;

            } elseif ($statusResponse['success'] && ($statusResponse['data']['status'] ?? null) === 'failed') {
                // FAILED — mark as failed in DB (expired / not paid)
                $deposit->status     = 'failed';
                $deposit->admin_note = 'Gateway Verification: Payment expired / not received (Ref: ' . $txnRef . ')';
                $deposit->save();
                $failed++;

            } elseif (!$statusResponse['success']) {
                $errors++;
            } else {
                $notPaid++;
            }
        }

        $total = $pending->count();
        return redirect()->route('admin.deposits.index')
            ->with('success', "Bulk Verify Complete — {$total} checked | ✅ {$approved} approved | ❌ {$failed} failed/expired | ⏳ {$notPaid} still pending | ⚠️ {$errors} API errors.");
    }

    public function creditUser(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'amount_usd' => 'required|numeric|min:0.01',
            'txn_type'   => 'required|in:credit,debit',
            'admin_note' => 'required|string|max:255',
        ]);

        $user   = User::findOrFail($request->input('user_id'));
        $amount = (float) $request->input('amount_usd');
        $type   = $request->input('txn_type');
        $note   = $request->input('admin_note');

        DB::transaction(function () use ($user, $amount, $type, $note) {
            if ($type === 'credit') {
                $user->usdt_balance += $amount;
                $user->save();

                Deposit::create([
                    'user_id'          => $user->id,
                    'amount_usd'       => $amount,
                    'payment_method'   => 'Admin Credit',
                    'transaction_hash' => 'ADMIN_CREDIT_' . strtoupper(uniqid()),
                    'status'           => 'approved',
                    'admin_note'       => $note,
                ]);
            } else {
                $user->usdt_balance = max(0, $user->usdt_balance - $amount);
                $user->save();

                Deposit::create([
                    'user_id'          => $user->id,
                    'amount_usd'       => -$amount,
                    'payment_method'   => 'Admin Debit',
                    'transaction_hash' => 'ADMIN_DEBIT_' . strtoupper(uniqid()),
                    'status'           => 'approved',
                    'admin_note'       => $note,
                ]);
            }
        });

        $msg = $type === 'credit'
            ? "Successfully credited \${$amount} USDT to {$user->name}'s Deposit Wallet!"
            : "Successfully debited \${$amount} USDT from {$user->name}'s Deposit Wallet.";

        return redirect()->route('admin.deposits.index')->with('success', $msg);
    }
}
