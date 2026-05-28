<?php

namespace App\Services;

use App\Enums\UjianStatus;
use App\Models\Ujian;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UjianServices
{
    public function accessFor(User $user): array
    {
        $user->loadMissing(['pilihan', 'ujian']);

        if (! $user->pilihan) {
            return $this->accessPayload('locked');
        }

        if (! $user->ujian || $user->ujian->status === UjianStatus::NotCheckedIn) {
            return $this->accessPayload('verification', $user->ujian);
        }

        $status = match ($user->ujian->status) {
            UjianStatus::Blocked => 'blocked',
            UjianStatus::Submitted => 'submitted',
            default => 'ready',
        };

        return $this->accessPayload($status, $user->ujian);
    }

    public function startFor(User $user): Ujian
    {
        return DB::transaction(function () use ($user) {
            $user->loadMissing('pilihan');

            if (! $user->pilihan) {
                throw ValidationException::withMessages([
                    'ujian' => 'Pilihan program studi belum disimpan permanen.',
                ]);
            }

            $ujian = Ujian::query()
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            if (! $ujian) {
                throw ValidationException::withMessages([
                    'ujian' => 'Sesi ujian belum tersedia. Silakan lakukan check-in kepada pengawas.',
                ]);
            }

            if (in_array($ujian->status, [UjianStatus::InExam, UjianStatus::Idle], true)) {
                return $ujian;
            }

            if ($ujian->status !== UjianStatus::CheckedIn) {
                throw ValidationException::withMessages([
                    'ujian' => match ($ujian->status) {
                        UjianStatus::NotCheckedIn => 'Anda belum check-in kepada pengawas.',
                        UjianStatus::Submitted => 'Ujian sudah disubmit dan tidak dapat dimulai ulang.',
                        UjianStatus::Blocked => 'Sesi ujian diblokir. Silakan hubungi pengawas.',
                        default => 'Sesi ujian tidak dapat dimulai saat ini.',
                    },
                ]);
            }

            $now = now();

            $ujian->forceFill([
                'status' => UjianStatus::InExam,
                'started_at' => $ujian->started_at ?? $now,
                'last_activity_at' => $now,
                'duration_minutes' => $ujian->duration_minutes ?: 120,
            ])->save();

            return $ujian->refresh();
        });
    }

    private function accessPayload(string $status, ?Ujian $ujian = null): array
    {
        $examStarted = $ujian && in_array($ujian->status, [UjianStatus::InExam, UjianStatus::Idle], true);
        $endsAt = $examStarted && $ujian->started_at
            ? $ujian->started_at->copy()->addMinutes($ujian->duration_minutes)
            : null;
        $remainingSeconds = $endsAt ? (int) max(0, ceil(now()->diffInSeconds($endsAt, false))) : null;

        return [
            'status' => $status,
            'ujian' => $ujian,
            'canStart' => $ujian?->status === UjianStatus::CheckedIn,
            'examStarted' => $examStarted,
            'activityTrackingEnabled' => $examStarted,
            'durationMinutes' => $ujian?->duration_minutes ?? 120,
            'startedAt' => $ujian?->started_at,
            'endsAt' => $endsAt,
            'remainingSeconds' => $remainingSeconds,
            'remainingLabel' => $remainingSeconds === null
                ? null
                : sprintf('%02d:%02d:%02d', intdiv($remainingSeconds, 3600), intdiv($remainingSeconds % 3600, 60), $remainingSeconds % 60),
        ];
    }
}
