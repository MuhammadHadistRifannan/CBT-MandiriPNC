<?php

namespace App\Services;

use App\Enums\BillingStatus;
use App\Enums\DokumenStatus;
use App\Models\User;

class CetakIdentitasService
{
    public function __construct(private readonly UjianQrService $qrService) {}

    public function accessFor(User $user): array
    {
        $user->loadMissing(['pilihan', 'billing', 'dokumen', 'ujian']);

        $dokumen = $user->dokumen;
        $hasPermanentChoice = $user->pilihan !== null;
        $isPaid = $user->billing?->transaction_status === BillingStatus::Settlement
            || (bool) $user->billing?->isPay;
        $isDocumentComplete = $dokumen !== null
            && filled($dokumen->foto)
            && filled($dokumen->kartu_identitas)
            && (filled($dokumen->suket) || filled($dokumen->ijazah));
        $isDocumentVerified = $isDocumentComplete
            && $dokumen->status === DokumenStatus::Verified;
        $hasExamSession = $user->ujian !== null;

        return [
            'canPrint' => $hasPermanentChoice && $isPaid && $isDocumentVerified && $hasExamSession,
            'hasPermanentChoice' => $hasPermanentChoice,
            'isPaid' => $isPaid,
            'isDocumentComplete' => $isDocumentComplete,
            'isDocumentVerified' => $isDocumentVerified,
            'hasExamSession' => $hasExamSession,
            'qrPayload' => $hasExamSession ? $this->qrService->payload($user->ujian) : null,
            'examCode' => $user->ujian?->kode_ujian,
            'documentStatus' => $dokumen?->status,
            'requirements' => [
                [
                    'label' => 'Pilihan program studi sudah disimpan permanen',
                    'completed' => $hasPermanentChoice,
                ],
                [
                    'label' => 'Pembayaran sudah berhasil',
                    'completed' => $isPaid,
                ],
                [
                    'label' => 'Dokumen sudah lengkap',
                    'completed' => $isDocumentComplete,
                ],
                [
                    'label' => 'Dokumen sudah divalidasi admin',
                    'completed' => $isDocumentVerified,
                ],
                [
                    'label' => 'Sesi dan kode ujian sudah diterbitkan',
                    'completed' => $hasExamSession,
                ],
            ],
        ];
    }
}
