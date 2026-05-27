<?php

namespace App\Services;

use App\Enums\BillingStatus;
use App\Models\Billings;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Midtrans\Config;
use Midtrans\Transaction;

class PaymentNotificationService
{
    public function __construct(private readonly UjianRegistrationService $registrationService) {}

    public function handle(array $data): void
    {
        $signature = hash(
            'sha512',
            $data['order_id'].$data['status_code'].$data['gross_amount'].config('services.midtrans.server_key')
        );

        abort_unless(hash_equals($signature, $data['signature_key']), 403);

        $this->storeProviderStatus($data);
    }

    public function syncForUser(User $user): array
    {
        $payment = Billings::query()->where('user_id', $user->id)->firstOrFail();

        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');

        $response = json_decode(json_encode(Transaction::status($payment->kode_bayar)), true);

        return $this->storeProviderStatus($response);
    }

    private function storeProviderStatus(array $data): array
    {
        $status = BillingStatus::tryFrom($data['transaction_status'] ?? '');

        if (! $status) {
            throw ValidationException::withMessages([
                'payment' => 'Status transaksi Midtrans belum didukung.',
            ]);
        }

        return DB::transaction(function () use ($data, $status): array {
            $payment = Billings::query()
                ->where('kode_bayar', $data['order_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $payment->update([
                'payment_type' => $data['payment_type'] ?? $payment->payment_type,
                'virtual_account' => data_get($data, 'va_numbers.0.va_number', $payment->virtual_account),
                'transaction_status' => $status,
                'gross_amount' => $data['gross_amount'],
                'isPay' => $status === BillingStatus::Settlement,
            ]);

            if ($status === BillingStatus::Settlement) {
                $this->registrationService->ensureForSettledBilling($payment);
            }

            return [
                'transaction_status' => $status->value,
                'is_paid' => $status === BillingStatus::Settlement,
            ];
        });
    }
}
