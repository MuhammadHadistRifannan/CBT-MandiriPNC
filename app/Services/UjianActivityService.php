<?php

namespace App\Services;

use App\Enums\UjianActivityType;
use App\Enums\UjianStatus;
use App\Models\Ujian;
use App\Models\UjianActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UjianActivityService
{
    public function record(User $user, array $data): void
    {
        $eventType = UjianActivityType::from($data['event_type']);

        DB::transaction(function () use ($user, $eventType): void {
            $ujian = Ujian::query()
                ->where('user_id', $user->id)
                ->lockForUpdate()
                ->first();

            if (! $ujian || ! in_array($ujian->status, [UjianStatus::InExam, UjianStatus::Idle], true)) {
                throw ValidationException::withMessages([
                    'event_type' => 'Aktivitas hanya dapat dicatat selama ujian berlangsung.',
                ]);
            }

            UjianActivityLog::create([
                'ujian_id' => $ujian->id,
                'user_id' => $user->id,
                'event_type' => $eventType,
                'occurred_at' => now(),
            ]);

            $ujian->update(['last_activity_at' => now()]);
        });
    }

    public function pageData(array $filters = []): array
    {
        return [
            'filters' => $filters,
            'participants' => Ujian::query()
                ->with('user.peserta')
                ->orderBy('user_id')
                ->get()
                ->map(fn (Ujian $ujian): array => [
                    'id' => $ujian->user_id,
                    'name' => $ujian->user?->name ?? '-',
                    'number' => $ujian->user?->peserta?->nomor_peserta ?? $ujian->kode_ujian,
                ]),
            'eventTypes' => array_map(fn (UjianActivityType $type): array => [
                'value' => $type->value,
                'label' => $type->label(),
            ], UjianActivityType::cases()),
            ...$this->feed($filters),
        ];
    }

    public function feed(array $filters = []): array
    {
        $page = max(1, (int) ($filters['page'] ?? 1));
        $perPage = min(50, max(5, (int) ($filters['per_page'] ?? 10)));

        $query = UjianActivityLog::query()
            ->with(['user.peserta', 'ujian'])
            ->when($filters['user_id'] ?? null, fn ($builder, $userId) => $builder->where('user_id', $userId))
            ->when($filters['event_type'] ?? null, fn ($builder, $eventType) => $builder->where('event_type', $eventType));

        $stats = [
            'total' => (clone $query)->count(),
            'tab_switches' => (clone $query)->where('event_type', UjianActivityType::TabHidden->value)->count(),
            'idle' => (clone $query)->where('event_type', UjianActivityType::Idle->value)->count(),
            'refreshes' => (clone $query)->where('event_type', UjianActivityType::Refresh->value)->count(),
        ];

        $paginator = $query->latest('occurred_at')
            ->paginate($perPage, ['*'], 'page', $page);

        $logs = $paginator->getCollection()
            ->map(fn (UjianActivityLog $log): array => [
                'id' => $log->id,
                'participant' => $log->user?->name ?? '-',
                'number' => $log->user?->peserta?->nomor_peserta ?? $log->ujian?->kode_ujian ?? '-',
                'event_type' => $log->event_type->value,
                'event_label' => $log->event_type->label(),
                'event_class' => $log->event_type->badgeClass(),
                'occurred_at' => $log->occurred_at->format('d M Y, H:i:s'),
            ])
            ->all();

        return [
            'stats' => $stats,
            'logs' => $logs,
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
                'has_more_pages' => $paginator->hasMorePages(),
            ],
        ];
    }
}
