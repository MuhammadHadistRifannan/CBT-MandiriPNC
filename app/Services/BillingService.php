<?php

namespace App\Services;

use App\Enums\BillingStatus;
use App\Models\Billings;
use App\Services\Payment\BankBNI;
use App\Services\Payment\PaymentService;

class BillingService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function create_billing()
    {
        $billing = Billings::create([
            'kode_bayar' => Billings::createKodeBayar(),
            'user_id' => auth()->user()->id,
            'transaction_status' => BillingStatus::Pending,
        ]);

        return $billing;
    }

    public function create_payment($user_id)
    {
        $paymentService = new PaymentService;
        $bill = Billings::where('user_id', $user_id)->first();

        if (! $bill) {
            return null;
        }

        $data = $paymentService->make_payment(new BankBNI, $bill->kode_bayar);

        $bill->update([
            'snap_token' => $data['snap_token'],
            'payment_type' => $data['payment_type'],
            'gross_amount' => $data['gross_amount'],
            'transaction_status' => BillingStatus::Pending,
        ]);

        return $data;
    }
}
