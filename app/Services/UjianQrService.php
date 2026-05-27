<?php

namespace App\Services;

use App\Models\Ujian;
use Illuminate\Validation\ValidationException;

class UjianQrService
{
    public function payload(Ujian $ujian): string
    {
        return $ujian->kode_ujian.'.'.$this->signature($ujian->kode_ujian);
    }

    public function examCodeFromPayload(string $payload): string
    {
        $separator = strrpos($payload, '.');

        if ($separator === false) {
            throw $this->invalidQr();
        }

        $examCode = substr($payload, 0, $separator);
        $signature = substr($payload, $separator + 1);

        if ($examCode === '' || ! hash_equals($this->signature($examCode), $signature)) {
            throw $this->invalidQr();
        }

        return $examCode;
    }

    private function signature(string $examCode): string
    {
        return hash_hmac('sha256', $examCode, (string) config('app.key'));
    }

    private function invalidQr(): ValidationException
    {
        return ValidationException::withMessages([
            'qr_payload' => 'QR kartu ujian tidak valid atau telah dimodifikasi.',
        ]);
    }
}
