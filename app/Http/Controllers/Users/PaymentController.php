<?php

namespace App\Http\Controllers\Users;

use App\Enums\BillingStatus;
use App\Http\Controllers\Controller;
use App\Models\Billings;
use App\Models\Peserta;
use App\Services\BillingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function notification(Request $request): JsonResponse
    {
        $data = $request->validate([
            'order_id' => ['required', 'string'],
            'status_code' => ['required', 'string'],
            'gross_amount' => ['required'],
            'signature_key' => ['required', 'string'],
            'transaction_status' => ['required', 'in:pending,settlement,expire'],
            'payment_type' => ['nullable', 'string'],
            'va_numbers' => ['nullable', 'array'],
            'va_numbers.0.va_number' => ['nullable', 'string'],
        ]);

        $signature = hash('sha512', $data['order_id']
            .$data['status_code']
            .$data['gross_amount']
            .config('services.midtrans.server_key'));

        abort_unless(hash_equals($signature, $data['signature_key']), 403);

        $payment = Billings::query()->where('kode_bayar', $data['order_id'])->firstOrFail();
        $status = BillingStatus::from($data['transaction_status']);

        $payment->update([
            'payment_type' => $data['payment_type'] ?? $payment->payment_type,
            'virtual_account' => data_get($data, 'va_numbers.0.va_number', $payment->virtual_account),
            'transaction_status' => $status,
            'gross_amount' => $data['gross_amount'],
            'isPay' => $status === BillingStatus::Settlement,
        ]);

        if ($status === BillingStatus::Settlement) {
            Peserta::firstOrCreate(
                ['user_id' => $payment->user_id],
                ['nomor_peserta' => Peserta::CreateNomorPeserta()]
            );
        }

        return response()->json(['message' => 'ok']);
    }

    public function generateSnap(BillingService $service): JsonResponse
    {
        $data = $service->create_payment(auth()->id());

        return response()->json([
            'order_id' => $data['order_id'],
            'snap_token' => $data['snap_token'],
        ]);
    }
}
