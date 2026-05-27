<?php

namespace App\Services;

use App\Enums\BillingStatus;
use App\Models\Billings;
use App\Services\Payment\BankBNI;
use App\Services\Payment\PaymentService;
use Illuminate\Validation\ValidationException;

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

    public function create_payment(int $userId): array
    {
        $bill = Billings::query()->where('user_id', $userId)->first();

        if (! $bill) {
            throw ValidationException::withMessages([
                'payment' => 'Data pembayaran tidak ditemukan.',
            ]);
        }

        if ($bill->transaction_status === BillingStatus::Settlement || $bill->isPay) {
            throw ValidationException::withMessages([
                'payment' => 'Pembayaran Anda sudah diterima.',
            ]);
        }

        if ($bill->transaction_status === BillingStatus::Expire) {
            throw ValidationException::withMessages([
                'payment' => 'Sesi pembayaran telah kedaluwarsa.',
            ]);
        }

        if ($bill->snap_token) {
            return [
                'snap_token' => $bill->snap_token,
                'order_id' => $bill->kode_bayar,
                'payment_type' => $bill->payment_type,
                'gross_amount' => $bill->gross_amount,
            ];
        }

        $paymentService = new PaymentService;
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
