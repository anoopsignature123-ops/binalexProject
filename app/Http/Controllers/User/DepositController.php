<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DepositController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $deposits = $user->deposits()->latest()->paginate(10);
        $adminBep20Address = config('app.usdt_bep20_address', '0x1234567890abcdef1234567890abcdef12345678');
        $hasOxapayKey = !empty(config('services.oxapay.merchant_key'));

        return view('user.deposit.index', compact('user', 'deposits', 'adminBep20Address', 'hasOxapayKey'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount_usd' => 'required|numeric|min:1',
            'transaction_hash' => 'required|string|max:255',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        Deposit::create([
            'user_id' => $user->id,
            'amount_usd' => $request->input('amount_usd'),
            'payment_method' => 'USDT_BEP20',
            'transaction_hash' => $request->input('transaction_hash'),
            'status' => 'pending',
        ]);

        return redirect()->route('user.deposit.index')
            ->with('success', 'USDT Deposit request submitted successfully! Admin will verify your transaction hash shortly.');
    }

    public function createOxapayInvoice(Request $request)
    {
        $request->validate([
            'amount_usd' => 'required|numeric|min:1',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $apiKey = config('services.oxapay.merchant_key');

        if (empty($apiKey)) {
            return back()->withErrors(['amount_usd' => 'Payment Gateway API Key is missing in .env file.']);
        }

        $amountUsd = (float) $request->input('amount_usd');

        // Create pending deposit record
        $deposit = Deposit::create([
            'user_id' => $user->id,
            'amount_usd' => $amountUsd,
            'payment_method' => 'iPaymentWallet Gateway',
            'transaction_hash' => null,
            'status' => 'pending',
        ]);

        try {
            $merchantTxnId = 'DEP_' . $deposit->id . '_' . time();

            // 1. Try iPaymentWallet API (funEarn Project Gateway)
            $response = Http::timeout(30)->withoutVerifying()->post('https://ipaymentwallet.com/api/v1/create-payment', [
                'txnId' => $merchantTxnId,
                'amount' => (string) $amountUsd,
                'apiKey' => $apiKey,
                'redirectUrl' => route('user.deposit.index'),
                'MemberId' => ($user->referral_code ?? ('User#' . $user->id)) . ' (' . $user->email . ')',
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['success']) && $result['success'] === true) {
                $data = $result['data'] ?? [];
                $paymentUrl = $data['redirectUrl'] ?? $data['qrUrl'] ?? $data['paymentUrl'] ?? $data['payment_url'] ?? null;
                $walletAddress = $data['paymentAddress'] ?? null;

                if ($paymentUrl || $walletAddress) {
                    $deposit->wallet_address = $walletAddress;
                    $deposit->gateway_url = $paymentUrl;
                    $deposit->transaction_hash = $merchantTxnId;
                    $deposit->save();

                    // Redirect to our OWN internal layout payment checkout page!
                    return redirect()->route('user.deposit.payment', $deposit->id);
                }
            }

            // 2. Fallback: Try OxaPay API
            $oxapayResponse = Http::timeout(30)->withoutVerifying()->post('https://api.oxapay.com/merchants/request', [
                'merchant' => $apiKey,
                'amount' => $amountUsd,
                'currency' => 'USD',
                'lifeTime' => 60,
                'feePaidByPayer' => 0,
                'underPaidCover' => 0,
                'callbackUrl' => route('oxapay.callback'),
                'returnUrl' => route('user.deposit.index'),
                'description' => 'Binalex Deposit ($' . number_format($amountUsd, 2) . ' USD)',
                'orderId' => (string) $deposit->id,
                'email' => $user->email ?? '',
            ]);

            $oxapayResult = $oxapayResponse->json();

            if (isset($oxapayResult['result']) && (int)$oxapayResult['result'] === 100 && !empty($oxapayResult['payLink'])) {
                $deposit->payment_method = 'OxaPay Gateway';
                $deposit->gateway_url = $oxapayResult['payLink'];
                $deposit->transaction_hash = 'TrackId: ' . ($oxapayResult['trackId'] ?? $deposit->id);
                $deposit->save();

                return redirect()->route('user.deposit.payment', $deposit->id);
            }

            $errorMsg = $result['message'] ?? $oxapayResult['message'] ?? 'Failed to initialize payment gateway. Please check API Key.';
            $deposit->delete();
            return back()->withErrors(['amount_usd' => 'Payment Gateway Error: ' . $errorMsg]);
        } catch (\Exception $e) {
            $deposit->delete();
            return back()->withErrors(['amount_usd' => 'Failed to connect to Payment Gateway: ' . $e->getMessage()]);
        }
    }

    public function paymentView(Deposit $deposit)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($deposit->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        return view('user.deposit.payment', compact('deposit'));
    }

    public function checkStatus(Deposit $deposit)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($deposit->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json(['status' => 'unauthorized'], 403);
        }

        if ($deposit->status === 'approved' || $deposit->status === 'success') {
            return response()->json(['status' => 'approved', 'message' => 'Payment already verified & credited.']);
        }

        $apiKey = config('services.oxapay.merchant_key');
        if (!empty($apiKey) && !empty($deposit->transaction_hash)) {
            $merchantTxnId = str_replace(['Ref: ', 'TrackId: ', 'GatewayTxn: '], '', $deposit->transaction_hash);

            try {
                // Check payment status with iPaymentWallet (funEarn gateway)
                $response = Http::timeout(10)->withoutVerifying()->withHeaders([
                    'X-API-Key' => $apiKey,
                    'Content-Type' => 'application/json',
                ])->post('https://ipaymentwallet.com/api/payment/check-status', [
                    'transaction_id' => $merchantTxnId,
                ]);

                if ($response->successful()) {
                    $resData = $response->json();
                    $confirmed = $resData['payment_confirmed'] ?? $resData['data']['payment_confirmed'] ?? false;
                    $status = strtolower((string)($resData['status'] ?? $resData['data']['status'] ?? ''));

                    if ($confirmed || $status === 'completed' || $status === 'paid' || $status === 'success') {
                        DB::transaction(function () use ($deposit) {
                            $deposit->status = 'approved';
                            $deposit->admin_note = 'Auto-approved via Gateway Verification (Ref: ' . $deposit->transaction_hash . ')';
                            $deposit->save();

                            $user = $deposit->user;
                            if ($user) {
                                $user->usdt_balance += $deposit->amount_usd;
                                $user->save();
                            }
                        });

                        return response()->json(['status' => 'approved', 'message' => 'Payment confirmed successfully!']);
                    }
                }
            } catch (\Exception $e) {
                // Ignore polling errors silently
            }
        }

        return response()->json(['status' => $deposit->status, 'message' => 'Waiting for payment confirmation...']);
    }

    public function oxapayCallback(Request $request)
    {
        $merchantKey = config('services.oxapay.merchant_key');
        $rawBody = $request->getContent();

        // Validate HMAC signature if header is present
        $hmacHeader = $request->header('HMAC');
        if (!empty($hmacHeader) && !empty($merchantKey)) {
            $calculatedHmac = hash_hmac('sha512', $rawBody, $merchantKey);
            if (!hash_equals($calculatedHmac, $hmacHeader)) {
                return response('Invalid Signature', 400);
            }
        }

        $status = strtolower((string) ($request->input('status') ?? ''));
        $orderId = $request->input('orderId') ?? $request->input('merchantTxnId') ?? $request->input('txnId');
        $trackId = $request->input('trackId');
        $txID = $request->input('txID') ?? $request->input('transaction_id') ?? $trackId;

        if (!$orderId) {
            return response('Missing OrderId', 400);
        }

        $deposit = Deposit::find($orderId) ?? Deposit::where('transaction_hash', $orderId)->first();
        if (!$deposit) {
            return response('Deposit Not Found', 404);
        }

        if ($status === 'paid' || $status === '200' || $status === '100' || $status === 'success' || $status === 'completed') {
            if ($deposit->status === 'pending') {
                DB::transaction(function () use ($deposit, $txID, $trackId) {
                    $deposit->status = 'approved';
                    $deposit->transaction_hash = $txID ? (string)$txID : ('TrackId: ' . $trackId);
                    $deposit->admin_note = 'Auto-approved via Payment Gateway Webhook Callback';
                    $deposit->save();

                    $user = $deposit->user;
                    if ($user) {
                        $user->usdt_balance += $deposit->amount_usd;
                        $user->save();
                    }
                });
            }
            return response('ok', 200);
        }

        if ($status === 'expired' || $status === 'failed' || $status === 'rejected') {
            if ($deposit->status === 'pending') {
                $deposit->status = 'rejected';
                $deposit->admin_note = 'Expired/Failed via Payment Gateway';
                $deposit->save();
            }
            return response('ok', 200);
        }

        return response('ok', 200);
    }
}