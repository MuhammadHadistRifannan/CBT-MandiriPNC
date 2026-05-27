<?php

namespace App\Services;

use App\Enums\BillingStatus;
use App\Enums\UjianStatus;
use App\Models\Billings;
use App\Models\Peserta;
use App\Models\Ujian;
use Illuminate\Support\Str;
use LogicException;

class UjianRegistrationService
{
    public function ensureForSettledBilling(Billings $billing): Ujian
    {
        if ($billing->transaction_status !== BillingStatus::Settlement && ! $billing->isPay) {
            throw new LogicException('Sesi ujian hanya dapat dibuat untuk pembayaran yang berhasil.');
        }

        $peserta = Peserta::firstOrCreate(
            ['user_id' => $billing->user_id],
            ['nomor_peserta' => Peserta::CreateNomorPeserta()]
        );

        return Ujian::firstOrCreate(
            ['user_id' => $billing->user_id],
            [
                'kode_ujian' => $this->uniqueExamCode($peserta),
                'status' => UjianStatus::NotCheckedIn,
            ]
        );
    }

    private function uniqueExamCode(Peserta $peserta): string
    {
        do {
            $code = 'UJN-'.$peserta->id.'-'.Str::upper(Str::random(8));
        } while (Ujian::query()->where('kode_ujian', $code)->exists());

        return $code;
    }
}
