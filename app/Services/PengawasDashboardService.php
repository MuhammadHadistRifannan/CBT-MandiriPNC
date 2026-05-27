<?php

namespace App\Services;

use App\Enums\UjianStatus;
use App\Models\Ujian;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PengawasDashboardService
{
    public function data(): array
    {
        $stats = [
            'total' => Ujian::query()->count(),
            'active' => Ujian::query()->where('status', UjianStatus::InExam->value)->count(),
            'completed' => Ujian::query()->where('status', UjianStatus::Submitted->value)->count(),
            'issues' => Ujian::query()
                ->whereIn('status', [UjianStatus::Idle->value, UjianStatus::Blocked->value])
                ->count(),
        ];

        $waiting = Ujian::query()->where('status', UjianStatus::CheckedIn->value)->count();

        return [
            'stats' => $stats,
            'monitoring' => $this->quickMonitoring(),
            'alerts' => $this->alerts($stats, $waiting),
            'updatedAt' => now()->format('H:i:s'),
        ];
    }

    private function quickMonitoring(): Collection
    {
        return Ujian::query()
            ->with('user.peserta')
            ->whereIn('status', [
                UjianStatus::CheckedIn->value,
                UjianStatus::InExam->value,
                UjianStatus::Idle->value,
                UjianStatus::Blocked->value,
                UjianStatus::Submitted->value,
            ])
            ->latest('updated_at')
            ->limit(8)
            ->get()
            ->map(fn (Ujian $ujian): array => [
                'id' => $ujian->id,
                'participant' => $ujian->user?->name ?? '-',
                'number' => $ujian->user?->peserta?->nomor_peserta ?? $ujian->kode_ujian,
                'status' => $ujian->status->value,
                'status_label' => $ujian->status->label(),
                'status_class' => $ujian->status->badgeClass(),
                'started_at' => $ujian->started_at?->format('H:i') ?? '-',
                'remaining' => $this->remainingTime($ujian),
                'progress' => min(100, max(0, $ujian->progress_percentage)),
            ]);
    }

    private function alerts(array $stats, int $waiting): array
    {
        $alerts = [];

        if ($stats['issues'] > 0) {
            $alerts[] = [
                'tone' => 'danger',
                'title' => 'Perlu Penanganan',
                'message' => "{$stats['issues']} peserta berstatus idle atau blocked dan perlu diperiksa.",
            ];
        }

        if ($waiting > 0) {
            $alerts[] = [
                'tone' => 'warning',
                'title' => 'Menunggu Mulai',
                'message' => "{$waiting} peserta telah check-in dan menunggu ujian dimulai.",
            ];
        }

        if ($stats['total'] === 0) {
            $alerts[] = [
                'tone' => 'neutral',
                'title' => 'Belum Ada Sesi',
                'message' => 'Belum ada data sesi ujian peserta yang dapat dimonitor.',
            ];
        } elseif ($stats['issues'] === 0) {
            $alerts[] = [
                'tone' => 'success',
                'title' => 'Kondisi Aman',
                'message' => 'Tidak ada peserta yang terdeteksi bermasalah saat ini.',
            ];
        }

        return $alerts;
    }

    private function remainingTime(Ujian $ujian): string
    {
        if (! $ujian->started_at || ! in_array($ujian->status, [UjianStatus::InExam, UjianStatus::Idle], true)) {
            return '-';
        }

        $endsAt = $ujian->started_at->copy()->addMinutes($ujian->duration_minutes);
        $seconds = $endsAt->isFuture() ? (int) now()->diffInSeconds($endsAt) : 0;

        return Carbon::createFromTimestampUTC($seconds)->format($seconds >= 3600 ? 'H:i:s' : 'i:s');
    }
}
