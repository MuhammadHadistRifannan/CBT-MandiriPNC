<?php

namespace App\Services;

use App\Enums\UjianStatus;
use App\Models\BroadcastMessage;
use App\Models\Ujian;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PengawasBroadcastService
{
    public function pageData(): array
    {
        return [
            'activeParticipants' => $this->activeParticipantIds()->count(),
            'history' => BroadcastMessage::query()
                ->with('pengawas')
                ->withCount([
                    'recipients',
                    'recipients as dismissed_count' => fn ($query) => $query->whereNotNull('dismissed_at'),
                ])
                ->latest()
                ->limit(20)
                ->get(),
        ];
    }

    public function send(array $data, User $pengawas): string
    {
        $recipients = $this->activeParticipantIds();

        DB::transaction(function () use ($data, $pengawas, $recipients): void {
            $broadcast = BroadcastMessage::create([
                'pengawas_id' => $pengawas->id,
                'message' => trim($data['message']),
            ]);

            $now = now();
            $broadcast->recipients()->createMany(
                $recipients->map(fn (int $userId): array => [
                    'user_id' => $userId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all()
            );
        });

        return "Pesan berhasil dikirim kepada {$recipients->count()} peserta aktif.";
    }

    private function activeParticipantIds()
    {
        return Ujian::query()
            ->whereIn('status', [UjianStatus::InExam->value, UjianStatus::Idle->value])
            ->distinct()
            ->pluck('user_id');
    }
}
