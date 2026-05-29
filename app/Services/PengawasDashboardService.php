<?php

namespace App\Services;

use App\Enums\UjianFlagStatus;
use App\Enums\UjianStatus;
use App\Models\Ujian;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PengawasDashboardService
{
    public function data(): array
    {
        $stats = [
            'total' => Ujian::query()->count(),
            'active' => Ujian::query()->where('status', UjianStatus::InExam->value)->count(),
            'completed' => Ujian::query()->where('status', UjianStatus::Submitted->value)->count(),
            'issues' => Ujian::query()
                ->where(function ($query): void {
                    $query->whereIn('status', [UjianStatus::Idle->value, UjianStatus::Blocked->value])
                        ->orWhereIn('flag_status', [UjianFlagStatus::Suspicious->value, UjianFlagStatus::Cheating->value]);
                })
                ->count(),
            'flagged' => Ujian::query()
                ->whereIn('flag_status', [UjianFlagStatus::Suspicious->value, UjianFlagStatus::Cheating->value])
                ->count(),
        ];

        $waiting = Ujian::query()->where('status', UjianStatus::CheckedIn->value)->count();

        return [
            'stats' => $stats,
            'monitoring' => $this->quickMonitoring(),
            'alerts' => $this->alerts($stats, $waiting),
            'flagOptions' => array_map(fn (UjianFlagStatus $status): array => [
                'value' => $status->value,
                'label' => $status->label(),
            ], UjianFlagStatus::cases()),
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
            ->map(fn (Ujian $ujian): array => $this->monitoringRow($ujian));
    }

    public function updateFlag(Ujian $ujian, array $data): array
    {
        return DB::transaction(function () use ($ujian, $data): array {
            $ujian = Ujian::query()
                ->lockForUpdate()
                ->findOrFail($ujian->id);

            $flagStatus = UjianFlagStatus::from($data['flag_status']);

            $ujian->forceFill([
                'flag_status' => $flagStatus,
                'flagged_reason' => $flagStatus === UjianFlagStatus::Normal
                    ? null
                    : ($data['flagged_reason'] ?? $ujian->flagged_reason),
            ])->save();

            return [
                'id' => $ujian->id,
                'flag_status' => $ujian->flag_status->value,
                'flag_label' => $ujian->flag_status->label(),
                'flag_class' => $ujian->flag_status->badgeClass(),
                'flagged_reason' => $ujian->flagged_reason,
            ];
        });
    }

    public function updateTimer(Ujian $ujian, array $data): array
    {
        return DB::transaction(function () use ($ujian, $data): array {
            $ujian = Ujian::query()
                ->with('user.peserta')
                ->lockForUpdate()
                ->findOrFail($ujian->id);

            match ($data['action']) {
                'start' => $this->startTimer($ujian),
                'stop' => $this->stopTimer($ujian),
                'extend' => $this->extendTimer($ujian, (int) $data['minutes']),
            };

            return $this->monitoringRow($ujian->refresh());
        });
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

    private function monitoringRow(Ujian $ujian): array
    {
        return [
            'id' => $ujian->id,
            'participant' => $ujian->user?->name ?? '-',
            'number' => $ujian->user?->peserta?->nomor_peserta ?? $ujian->kode_ujian,
            'status' => $ujian->status->value,
            'status_label' => $ujian->status->label(),
            'status_class' => $ujian->status->badgeClass(),
            'flag_status' => $ujian->flag_status->value,
            'flag_label' => $ujian->flag_status->label(),
            'flag_class' => $ujian->flag_status->badgeClass(),
            'flagged_reason' => $ujian->flagged_reason,
            'started_at' => $ujian->started_at?->format('H:i') ?? '-',
            'duration_minutes' => $ujian->duration_minutes,
            'remaining' => $this->remainingTime($ujian),
            'progress' => min(100, max(0, $ujian->progress_percentage)),
        ];
    }

    private function startTimer(Ujian $ujian): void
    {
        if (! in_array($ujian->status, [UjianStatus::CheckedIn, UjianStatus::Idle], true)) {
            throw ValidationException::withMessages([
                'action' => 'Timer hanya dapat dimulai untuk peserta check-in atau idle.',
            ]);
        }

        $ujian->forceFill([
            'status' => UjianStatus::InExam,
            'started_at' => $ujian->started_at ?? now(),
            'last_activity_at' => now(),
            'duration_minutes' => $ujian->duration_minutes ?: 120,
        ])->save();
    }

    private function stopTimer(Ujian $ujian): void
    {
        if (! in_array($ujian->status, [UjianStatus::InExam, UjianStatus::Idle], true) || ! $ujian->started_at) {
            throw ValidationException::withMessages([
                'action' => 'Timer hanya dapat dihentikan untuk ujian yang sedang berjalan.',
            ]);
        }

        $elapsedMinutes = max(0, (int) floor($ujian->started_at->diffInSeconds(now()) / 60));

        $ujian->forceFill([
            'duration_minutes' => $elapsedMinutes,
            'last_activity_at' => now(),
        ])->save();
    }

    private function extendTimer(Ujian $ujian, int $minutes): void
    {
        if (! in_array($ujian->status, [UjianStatus::InExam, UjianStatus::Idle], true) || ! $ujian->started_at) {
            throw ValidationException::withMessages([
                'minutes' => 'Waktu hanya dapat ditambahkan untuk ujian yang sedang berjalan.',
            ]);
        }

        $ujian->forceFill([
            'duration_minutes' => min(600, $ujian->duration_minutes + $minutes),
            'last_activity_at' => now(),
        ])->save();
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
