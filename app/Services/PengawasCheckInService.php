<?php

namespace App\Services;

use App\Enums\BillingStatus;
use App\Enums\UjianCheckInMethod;
use App\Enums\UjianStatus;
use App\Models\Ujian;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PengawasCheckInService
{
    public function __construct(private readonly UjianQrService $qrService) {}

    public function pageData(): array
    {
        return [
            'recentCheckIns' => Ujian::query()
                ->with(['user.peserta', 'pengawas'])
                ->whereNotNull('checked_in_at')
                ->latest('checked_in_at')
                ->limit(8)
                ->get(),
        ];
    }

    public function lookup(array $data): array
    {
        $method = UjianCheckInMethod::from($data['method']);
        $examCode = $this->examCode($data, $method);
        $ujian = $this->eligibleExam($examCode);

        return $this->participantData($ujian, $method);
    }

    public function confirm(Ujian $ujian, array $data, User $pengawas): array
    {
        $method = UjianCheckInMethod::from($data['method']);
        $examCode = $this->examCode($data, $method);

        if ($ujian->kode_ujian !== $examCode) {
            throw ValidationException::withMessages([
                'kode_ujian' => 'Data sesi ujian tidak sesuai dengan peserta yang dipilih.',
            ]);
        }

        return DB::transaction(function () use ($ujian, $method, $examCode, $pengawas): array {
            $lockedExam = Ujian::query()->whereKey($ujian->id)->lockForUpdate()->firstOrFail();

            if ($lockedExam->kode_ujian !== $examCode) {
                throw ValidationException::withMessages([
                    'kode_ujian' => 'Data sesi ujian tidak lagi sesuai.',
                ]);
            }

            $lockedExam = $this->eligibleExam($lockedExam->kode_ujian, $lockedExam);
            $lockedExam->update([
                'status' => UjianStatus::CheckedIn,
                'checked_in_at' => now(),
                'check_in_method' => $method,
                'pengawas_id' => $pengawas->id,
            ]);

            return $this->participantData($lockedExam->fresh(), $method);
        });
    }

    private function eligibleExam(string $examCode, ?Ujian $exam = null): Ujian
    {
        $ujian = ($exam ?? Ujian::query()->where('kode_ujian', $examCode)->first());

        if (! $ujian) {
            throw ValidationException::withMessages([
                'kode_ujian' => 'Kode ujian tidak ditemukan.',
            ]);
        }

        $ujian->loadMissing([
            'user.peserta',
            'user.billing',
            'user.pilihan.pilihan_prodi_1',
            'user.pilihan.pilihan_prodi_2',
        ]);

        if ($ujian->status === UjianStatus::Blocked) {
            throw ValidationException::withMessages([
                'kode_ujian' => 'Peserta diblokir dan tidak dapat melakukan check-in.',
            ]);
        }

        if ($ujian->status === UjianStatus::Submitted) {
            throw ValidationException::withMessages([
                'kode_ujian' => 'Peserta sudah menyelesaikan ujian.',
            ]);
        }

        if ($ujian->status !== UjianStatus::NotCheckedIn || $ujian->checked_in_at !== null) {
            throw ValidationException::withMessages([
                'kode_ujian' => 'Peserta sudah melakukan check-in.',
            ]);
        }

        if ($ujian->user?->billing?->transaction_status !== BillingStatus::Settlement) {
            throw ValidationException::withMessages([
                'kode_ujian' => 'Pembayaran peserta belum berhasil.',
            ]);
        }

        if (! $ujian->user?->pilihan) {
            throw ValidationException::withMessages([
                'kode_ujian' => 'Peserta belum menyimpan pilihan program studi.',
            ]);
        }

        return $ujian;
    }

    private function participantData(Ujian $ujian, UjianCheckInMethod $method): array
    {
        $ujian->loadMissing([
            'user.peserta',
            'user.billing',
            'user.pilihan.pilihan_prodi_1',
            'user.pilihan.pilihan_prodi_2',
        ]);

        return [
            'id' => $ujian->id,
            'name' => $ujian->user->name,
            'participant_number' => $ujian->user->peserta?->nomor_peserta ?? '-',
            'exam_code' => $ujian->kode_ujian,
            'primary_prodi' => $ujian->user->pilihan?->pilihan_prodi_1?->nama_prodi ?? '-',
            'secondary_prodi' => $ujian->user->pilihan?->pilihan_prodi_2?->nama_prodi ?? '-',
            'payment_label' => $ujian->user->billing?->transaction_status?->label() ?? '-',
            'status' => $ujian->status->value,
            'status_label' => $ujian->status->label(),
            'status_class' => $ujian->status->badgeClass(),
            'method' => $method->value,
            'method_label' => $method->label(),
            'checked_in_at' => $ujian->checked_in_at?->format('d M Y, H:i'),
            'confirm_url' => route('pengawas.check-in.confirm', $ujian),
        ];
    }

    private function examCode(array $data, UjianCheckInMethod $method): string
    {
        if ($method === UjianCheckInMethod::Qr) {
            return $this->qrService->examCodeFromPayload($data['qr_payload']);
        }

        return trim($data['kode_ujian']);
    }
}
