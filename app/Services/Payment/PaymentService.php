<?php

namespace App\Services\Payment;

use Midtrans\Config;
use Midtrans\Snap;

class PaymentService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function make_payment(IPaymentService $paymentService, string $kode_bayar)
    {
        $provider = $paymentService->getAttribute();

        $order_id = $kode_bayar;
        $gross_amount = (float) config('services.midtrans.gross_amount');
        $payment_type = $provider['type'];
        $bank = $provider['name'];

        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $gross_amount,
            ],
            'payment_type' => $payment_type,
            'bank_transfer' => [
                'bank' => $bank,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return [
            'snap_token' => $snapToken,
            'order_id' => $order_id,
            'payment_type' => $payment_type,
            'gross_amount' => $gross_amount,
        ];
    }
}
