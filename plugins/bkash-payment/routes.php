<?php

use Illuminate\Support\Facades\Route;

/**
 * bKash Payment Plugin Routes.
 */
Route::prefix('api/v1/payments/bkash')->middleware('force-json')->group(function () {
    Route::get('/callback', function (\Illuminate\Http\Request $request) {
        $orderId   = $request->input('order_id');
        $paymentId = $request->input('paymentID');
        $status    = $request->input('status');

        $order = \App\Models\Order::findOrFail($orderId);

        if ($status === 'success') {
            $gateway = app('payment.bkash');
            $verified = $gateway->verifyPayment(['paymentID' => $paymentId]);

            if ($verified) {
                $order->update([
                    'payment_status' => 'paid',
                    'paid_at'        => now(),
                    'transaction_id' => $paymentId,
                ]);

                \App\Events\PaymentCompleted::dispatch($order, 'bkash', $paymentId);

                return response()->json(['success' => true, 'message' => 'Payment verified.']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Payment failed or cancelled.'], 400);
    });
});
