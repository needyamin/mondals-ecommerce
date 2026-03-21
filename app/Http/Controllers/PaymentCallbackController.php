<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    /**
     * Handle payment gateway callbacks (GET/POST).
     * URL: /payment/{gateway}/callback?order_id=X&paymentID=Y&status=Z
     */
    public function handle(Request $request, $gatewaySlug)
    {
        $orderId = $request->query('order_id') ?? $request->input('order_id');
        
        Log::info("Payment callback received for gateway: {$gatewaySlug}", [
            'order_id' => $orderId,
            'params'   => $request->all(),
        ]);

        if (!$orderId) {
            return redirect()->route('home')->with('error', 'Invalid payment callback: Missing Order ID.');
        }

        $order = Order::find($orderId);
        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        try {
            $gateways = app()->tagged('payment_gateways');
            foreach ($gateways as $gateway) {
                if ($gateway->getIdentifier() === $gatewaySlug) {
                    // Pass all request data plus order_id for verification
                    $payload = array_merge($request->all(), ['order_id' => $orderId]);
                    $verified = $gateway->verifyPayment($payload);
                    
                    if ($verified) {
                        $order->update([
                            'payment_status' => 'paid',
                            'status'         => 'processing',
                            'paid_at'        => now(),
                        ]);

                        $order->statusHistory()->create([
                            'old_status' => 'pending',
                            'new_status' => 'processing',
                            'comment'    => "Payment verified via {$gateway->getName()}.",
                            'user_id'    => $order->user_id,
                        ]);

                        Log::info("Payment verified for order {$order->order_number} via {$gatewaySlug}");

                        return redirect()->route('order.confirmation', $order->order_number)
                            ->with('success', 'Payment successful! Your order is confirmed.');
                    } else {
                        Log::warning("Payment verification failed for order {$order->order_number} via {$gatewaySlug}");
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Payment Callback Error ({$gatewaySlug}): " . $e->getMessage());
        }

        return redirect()->route('order.confirmation', $order->order_number)
            ->with('error', 'Payment verification failed. Your order is pending. Please contact support if payment was deducted.');
    }
}
