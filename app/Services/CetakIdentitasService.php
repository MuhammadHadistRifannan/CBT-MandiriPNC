<?php

namespace App\Services;

use App\Enums\BillingStatus;
use App\Enums\DokumenStatus;
use App\Models\User;

class CetakIdentitasService
{
    public function accessFor(User $user): array
    {
        $user->loadMissing(['pilihan', 'billing', 'dokumen']);

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

        return [
            'canPrint' => $hasPermanentChoice && $isPaid && $isDocumentVerified,
            'hasPermanentChoice' => $hasPermanentChoice,
            'isPaid' => $isPaid,
            'isDocumentComplete' => $isDocumentComplete,
            'isDocumentVerified' => $isDocumentVerified,
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
            ],
        ];
    }
}
