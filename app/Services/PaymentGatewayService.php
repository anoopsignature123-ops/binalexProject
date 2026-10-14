<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentGatewayService
{
    private string $ipaymentwalletUrl = 'https://ipaymentwallet.com/api';
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = env('PAYMENT_GATEWAY_API_KEY', 'pk_Hwho4MCbvOT8j1e6h254lJOkh647N3Zs');
    }

    /**
     * Create payment request for BEP20 USDT deposit
     */
    public function createPayment(string $txnId, float $amount, ?string $redirectUrl = null, ?string $memberId = null): array
    {
        try {
            $payload = [
                'txnId'       => $txnId,
                'amount'      => (string) $amount,
                'apiKey'      => $this->apiKey,
                'redirectUrl' => $redirectUrl ?? config('app.url'),
            ];

            if ($memberId !== null) {
                $payload['MemberId'] = (string) $memberId;
            }

            $response = Http::timeout(30)->withoutVerifying()->post("{$this->ipaymentwalletUrl}/v1/create-payment", $payload);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data'    => $response->json(),
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to create payment',
                'error'   => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error('PaymentGateway createPayment error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Payment gateway connection failed',
                'error'   => $e->getMessage(),
            ];
        }
    }

    /**
     * Check payment status by merchant txn ID (e.g. DEP_31_1791900755)
     */
    public function checkPaymentStatus(string $merchantTxnId): array
    {
        try {
            $response = Http::timeout(30)->withoutVerifying()->withHeaders([
                'X-API-Key'    => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->ipaymentwalletUrl}/payment/check-status", [
                'transaction_id' => $merchantTxnId,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'success'           => true,
                    'data'              => $data['data'] ?? $data,
                    'payment_confirmed' => $data['payment_confirmed'] ?? false,
                ];
            }

            Log::warning("PaymentGateway checkPaymentStatus failed for [{$merchantTxnId}]", [
                'status_code' => $response->status(),
                'body'        => $response->json(),
            ]);

            return [
                'success'     => false,
                'message'     => 'Failed to check status',
                'status_code' => $response->status(),
                'response'    => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error("PaymentGateway checkPaymentStatus exception for [{$merchantTxnId}]: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Status check failed',
                'error'   => $e->getMessage(),
            ];
        }
    }
}
