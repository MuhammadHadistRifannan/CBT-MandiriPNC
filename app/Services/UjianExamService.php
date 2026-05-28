<?php

namespace App\Services;

use App\Enums\SoalCbtStatus;
use App\Enums\UjianStatus;
use App\Models\SoalCbt;
use App\Models\Ujian;
use App\Models\UjianAnswer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UjianExamService
{
    public function page(User $user, int $page = 1): array
    {
        $ujian = $this->runningExamFor($user);
        $questions = $this->questionsQuery()->paginate(1, ['*'], 'page', max(1, $page));

        if ($questions->isEmpty()) {
            throw ValidationException::withMessages([
                'ujian' => 'Belum ada soal CBT yang dirilis oleh admin.',
            ]);
        }

        $answers = UjianAnswer::query()
            ->where('ujian_id', $ujian->id)
            ->pluck('jawaban', 'soal_id');
        $questionIds = $this->questionsQuery()->pluck('id')->values();

        return [
            'ujian' => $ujian,
            'questions' => $questions,
            'question' => $questions->first(),
            'questionIds' => $questionIds,
            'answers' => $answers,
            'answeredCount' => $answers->filter(fn (?string $answer) => filled($answer))->count(),
            'totalQuestions' => $questions->total(),
            'remainingSeconds' => $this->remainingSeconds($ujian),
        ];
    }

    public function saveAnswer(User $user, array $data): array
    {
        return DB::transaction(function () use ($user, $data): array {
            $ujian = $this->runningExamFor($user, lock: true);
            $question = $this->questionsQuery()->whereKey($data['soal_id'])->first();

            if (! $question) {
                throw ValidationException::withMessages([
                    'soal_id' => 'Soal tidak ditemukan atau belum dirilis.',
                ]);
            }

            if ($this->remainingSeconds($ujian) <= 0) {
                throw ValidationException::withMessages([
                    'jawaban' => 'Waktu ujian sudah habis.',
                ]);
            }

            UjianAnswer::updateOrCreate(
                [
                    'ujian_id' => $ujian->id,
                    'soal_id' => $question->id,
                ],
                [
                    'user_id' => $user->id,
                    'jawaban' => $data['jawaban'] ?? null,
                    'answered_at' => filled($data['jawaban'] ?? null) ? now() : null,
                ]
            );

            $totalQuestions = $this->questionsQuery()->count();
            $answeredCount = UjianAnswer::query()
                ->where('ujian_id', $ujian->id)
                ->whereNotNull('jawaban')
                ->count();

            $ujian->forceFill([
                'progress_percentage' => $totalQuestions > 0
                    ? min(100, (int) floor(($answeredCount / $totalQuestions) * 100))
                    : 0,
                'last_activity_at' => now(),
            ])->save();

            return [
                ...$this->statusPayload($ujian->refresh(), $totalQuestions, $answeredCount),
            ];
        });
    }

    public function statusFor(User $user): array
    {
        $ujian = Ujian::query()
            ->where('user_id', $user->id)
            ->first();

        if (! $ujian) {
            throw ValidationException::withMessages([
                'ujian' => 'Sesi ujian belum tersedia.',
            ]);
        }

        if (
            $ujian->started_at
            && in_array($ujian->status, [UjianStatus::InExam, UjianStatus::Idle], true)
            && $this->remainingSeconds($ujian) <= 0
        ) {
            return $this->submitFor($user, 'auto');
        }

        $totalQuestions = $this->questionsQuery()->count();
        $answeredCount = UjianAnswer::query()
            ->where('ujian_id', $ujian->id)
            ->whereNotNull('jawaban')
            ->count();

        $progress = $totalQuestions > 0
            ? min(100, (int) floor(($answeredCount / $totalQuestions) * 100))
            : 0;

        if ($ujian->progress_percentage !== $progress) {
            $ujian->forceFill(['progress_percentage' => $progress])->save();
            $ujian->refresh();
        }

        return $this->statusPayload($ujian, $totalQuestions, $answeredCount);
    }

    public function submitFor(User $user, string $submitType = 'manual'): array
    {
        return DB::transaction(function () use ($user, $submitType): array {
            $ujian = Ujian::query()
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            if (! $ujian) {
                throw ValidationException::withMessages([
                    'ujian' => 'Sesi ujian belum tersedia.',
                ]);
            }

            $totalQuestions = $this->questionsQuery()->count();
            $answeredCount = UjianAnswer::query()
                ->where('ujian_id', $ujian->id)
                ->whereNotNull('jawaban')
                ->count();

            if ($ujian->status === UjianStatus::Submitted) {
                return $this->statusPayload($ujian, $totalQuestions, $answeredCount);
            }

            if ($ujian->status === UjianStatus::Blocked) {
                throw ValidationException::withMessages([
                    'ujian' => 'Sesi ujian diblokir dan tidak dapat disubmit oleh peserta.',
                ]);
            }

            if (! in_array($ujian->status, [UjianStatus::InExam, UjianStatus::Idle], true)) {
                throw ValidationException::withMessages([
                    'ujian' => 'Ujian belum dimulai.',
                ]);
            }

            $score = $this->scoreFor($ujian, $totalQuestions);

            $ujian->forceFill([
                'status' => UjianStatus::Submitted,
                'submitted_at' => now(),
                'nilai' => $score,
                'progress_percentage' => $totalQuestions > 0
                    ? min(100, (int) floor(($answeredCount / $totalQuestions) * 100))
                    : 0,
                'last_activity_at' => now(),
            ])->save();

            return [
                ...$this->statusPayload($ujian->refresh(), $totalQuestions, $answeredCount),
                'submit_type' => $submitType,
            ];
        });
    }

    private function runningExamFor(User $user, bool $lock = false): Ujian
    {
        $query = Ujian::query()->where('user_id', $user->id);

        if ($lock) {
            $query->lockForUpdate();
        }

        $ujian = $query->first();

        if (! $ujian || ! in_array($ujian->status, [UjianStatus::InExam, UjianStatus::Idle], true)) {
            throw ValidationException::withMessages([
                'ujian' => 'Ujian belum dimulai. Silakan mulai ujian dari portal.',
            ]);
        }

        if (! $ujian->started_at) {
            throw ValidationException::withMessages([
                'ujian' => 'Waktu mulai ujian tidak ditemukan.',
            ]);
        }

        if ($ujian->status === UjianStatus::Submitted || $ujian->status === UjianStatus::Blocked) {
            throw ValidationException::withMessages([
                'ujian' => 'Sesi ujian tidak dapat diakses.',
            ]);
        }

        return $ujian;
    }

    private function questionsQuery()
    {
        return SoalCbt::query()
            ->where('status', SoalCbtStatus::Released)
            ->orderBy('sub_soal')
            ->orderBy('id');
    }

    private function statusPayload(Ujian $ujian, int $totalQuestions, int $answeredCount): array
    {
        $remainingSeconds = $ujian->started_at && in_array($ujian->status, [UjianStatus::InExam, UjianStatus::Idle], true)
            ? $this->remainingSeconds($ujian)
            : 0;

        return [
            'total_soal' => $totalQuestions,
            'total_questions' => $totalQuestions,
            'answered_count' => $answeredCount,
            'remaining_time' => $remainingSeconds,
            'remaining_seconds' => $remainingSeconds,
            'remaining_label' => $this->formatSeconds($remainingSeconds),
            'status' => $ujian->status->value,
            'status_label' => $ujian->status->label(),
            'submitted' => $ujian->status === UjianStatus::Submitted,
            'submitted_at' => $ujian->submitted_at?->toISOString(),
            'nilai' => $ujian->nilai,
            'progress_percentage' => $totalQuestions > 0
                ? min(100, (int) floor(($answeredCount / $totalQuestions) * 100))
                : 0,
        ];
    }

    private function scoreFor(Ujian $ujian, int $totalQuestions): float
    {
        if ($totalQuestions <= 0) {
            return 0.0;
        }

        $correctAnswers = UjianAnswer::query()
            ->join('soal_cbt', 'ujian_answers.soal_id', '=', 'soal_cbt.id')
            ->where('ujian_answers.ujian_id', $ujian->id)
            ->where('soal_cbt.status', SoalCbtStatus::Released->value)
            ->whereColumn('ujian_answers.jawaban', 'soal_cbt.jawaban_benar')
            ->count();

        return round(($correctAnswers / $totalQuestions) * 100, 2);
    }

    private function remainingSeconds(Ujian $ujian): int
    {
        $endsAt = $ujian->started_at->copy()->addMinutes($ujian->duration_minutes);

        return (int) max(0, ceil(now()->diffInSeconds($endsAt, false)));
    }

    private function formatSeconds(int $seconds): string
    {
        return sprintf('%02d:%02d:%02d', intdiv($seconds, 3600), intdiv($seconds % 3600, 60), $seconds % 60);
    }
}
