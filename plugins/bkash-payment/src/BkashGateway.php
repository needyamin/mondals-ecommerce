<?php

namespace Plugins\BkashPayment;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class BkashGateway implements PaymentGatewayInterface
{
    protected array $settings;

    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }

    public function getIdentifier(): string { return 'bkash'; }
    public function getName(): string { return 'bKash'; }

    public function supportsCurrency(string $currency): bool
    {
        return strtoupper($currency) === 'BDT';
    }

    /**
     * Initiate a bKash payment.
     */
    public function initiatePayment(Order $order, array $options = []): array
    {
        $baseUrl = $this->getBaseUrl();

        // 1. Get grant token
        $token = $this->getToken($baseUrl);
        if (!$token) {
            return [
                'success' => false,
                'message' => 'Failed to obtain bKash token. Check your API credentials in Plugin Settings.'
            ];
        }

        // 2. Create payment
        // Build callback URL using app URL to ensure valid domain (not localhost)
        $appUrl = rtrim(config('app.url', 'https://mondalsecommerce.test'), '/');
        $callbackUrl = $appUrl . '/payment/bkash/callback?order_id=' . $order->id;

        $createPayload = [
            'mode'                  => '0011',
            'payerReference'        => $order->shipping_phone ?? ($order->user->phone ?? $order->user->email ?? '01770618575'),
            'callbackURL'           => $callbackUrl,
            'amount'                => number_format($order->total, 2, '.', ''),
            'currency'              => 'BDT',
            'intent'                => 'sale',
            'merchantInvoiceNumber' => $order->order_number,
        ];

        Log::info('bKash Create Payment Request', ['payload' => $createPayload]);

        // Force Bearer token format correctly
        $idToken = str_starts_with($token, 'eyJ') ? "Bearer {$token}" : $token;

        $paymentResponse = Http::withHeaders([
            'Authorization' => $idToken,
            'X-APP-Key'     => $this->settings['app_key'] ?? '',
            'Content-Type'  => 'application/json',
        ])->post("{$baseUrl}/tokenized/checkout/create", $createPayload);

        $data = $paymentResponse->json();

        Log::info('bKash Create Payment Response', ['data' => $data, 'orderId' => $order->id]);

        if (($data['statusCode'] ?? '') === '0000' && !empty($data['bkashURL'])) {
            // Store token in session for execute step
            Session::put("bkash_token_{$order->id}", $token);
            Session::put("bkash_payment_id_{$order->id}", $data['paymentID']);

            return [
                'success'      => true,
                'payment_id'   => $data['paymentID'],
                'redirect_url' => $data['bkashURL'],
                'message'      => 'Redirecting to bKash...',
            ];
        }

        return [
            'success' => false,
            'message' => $data['statusMessage'] ?? 'bKash payment creation failed.',
        ];
    }

    /**
     * Verify and execute bKash payment after callback.
     */
    public function verifyPayment(array $payload): bool
    {
        $paymentId = $payload['paymentID'] ?? null;
        $status    = $payload['status'] ?? null;
        $orderId   = $payload['order_id'] ?? null;

        if (!$paymentId || $status !== 'success') {
            Log::warning('bKash verify failed: missing paymentID or status not success', $payload);
            return false;
        }

        $baseUrl = $this->getBaseUrl();
        $token   = Session::pull("bkash_token_{$orderId}") ?? $this->getToken($baseUrl);

        if (!$token) {
            Log::error('bKash verify: Could not obtain token');
            return false;
        }

        // Execute payment
        $executeResponse = Http::withHeaders([
            'Authorization' => $token,
            'X-APP-Key'     => $this->settings['app_key'] ?? '',
        ])->post("{$baseUrl}/tokenized/checkout/execute", [
            'paymentID' => $paymentId,
        ]);

        $data = $executeResponse->json();

        Log::info('bKash Execute Payment Response', ['data' => $data]);

        if (($data['statusCode'] ?? '') === '0000'
            && ($data['transactionStatus'] ?? '') === 'Completed') {

            // Store transaction ID on order
            if ($orderId) {
                $order = Order::find($orderId);
                if ($order) {
                    $order->update([
                        'transaction_id' => $data['trxID'] ?? $paymentId,
                    ]);
                }
            }
            return true;
        }

        return false;
    }

    /**
     * Process refund (placeholder for future).
     */
    public function refund(Order $order, float $amount): bool
    {
        return false;
    }

    /**
     * Get bKash API grant token.
     */
    protected function getToken(string $baseUrl): ?string
    {
        try {
            $response = Http::withHeaders([
                'username' => $this->settings['username'] ?? '',
                'password' => $this->settings['password'] ?? '',
            ])->post("{$baseUrl}/tokenized/checkout/token/grant", [
                'app_key'    => $this->settings['app_key'] ?? '',
                'app_secret' => $this->settings['app_secret'] ?? '',
            ]);

            $token = $response->json('id_token');

            if (!$token) {
                Log::error('bKash token grant failed', [
                    'response' => $response->json(),
                    'status'   => $response->status(),
                ]);
            }

            return $token;
        } catch (\Exception $e) {
            Log::error('bKash token exception: ' . $e->getMessage());
            return null;
        }
    }

    protected function getBaseUrl(): string
    {
        return $this->isSandbox()
            ? 'https://tokenized.sandbox.bka.sh/v1.2.0-beta'
            : 'https://tokenized.pay.bka.sh/v1.2.0-beta';
    }

    protected function isSandbox(): bool
    {
        return filter_var($this->settings['sandbox'] ?? true, FILTER_VALIDATE_BOOLEAN);
    }
}
