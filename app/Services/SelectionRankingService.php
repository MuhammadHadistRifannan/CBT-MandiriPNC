<?php

namespace App\Services;

use App\Enums\AnnouncementResultStatus;
use App\Enums\UjianStatus;
use App\Models\AnnouncementBatch;
use App\Models\Prodi;
use App\Models\Ujian;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SelectionRankingService
{
    public function generate(AnnouncementBatch $batch): array
    {
        return DB::transaction(function () use ($batch): array {
            $lockedBatch = AnnouncementBatch::query()
                ->whereKey($batch->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedBatch->ranking_locked) {
                throw ValidationException::withMessages([
                    'batch' => 'Ranking sudah dikunci. Gunakan prosedur reset resmi sebelum generate ulang.',
                ]);
            }

            $quota = Prodi::query()
                ->get(['id', 'kuota', 'daya_tampung'])
                ->mapWithKeys(fn (Prodi $prodi) => [
                    $prodi->id => max(0, (int) ($prodi->kuota ?: $prodi->daya_tampung)),
                ])
                ->all();

            $submittedSessions = Ujian::query()
                ->with([
                    'user:id,name',
                    'user.peserta:id,user_id,nomor_peserta',
                    'user.pilihan:id,user_id,pilihan_1,pilihan_2',
                ])
                ->where('status', UjianStatus::Submitted->value)
                ->whereNotNull('submitted_at')
                ->whereNotNull('nilai')
                ->get()
                ->filter(fn (Ujian $ujian) => $ujian->user?->peserta && $ujian->user?->pilihan)
                ->sort(function (Ujian $first, Ujian $second): int {
                    $scoreCompare = ((float) $second->nilai) <=> ((float) $first->nilai);

                    if ($scoreCompare !== 0) {
                        return $scoreCompare;
                    }

                    $timeCompare = $first->submitted_at <=> $second->submitted_at;

                    if ($timeCompare !== 0) {
                        return $timeCompare;
                    }

                    return strcmp(
                        $first->user->peserta->nomor_peserta,
                        $second->user->peserta->nomor_peserta
                    );
                })
                ->values();

            $now = now('Asia/Jakarta');
            $rows = [];
            $accepted = 0;

            foreach ($submittedSessions as $index => $ujian) {
                $pilihan = $ujian->user->pilihan;
                $firstChoice = (int) $pilihan->pilihan_1;
                $secondChoice = $pilihan->pilihan_2 ? (int) $pilihan->pilihan_2 : null;
                $acceptedProdi = null;

                if (($quota[$firstChoice] ?? 0) > 0) {
                    $acceptedProdi = $firstChoice;
                    $quota[$firstChoice]--;
                } elseif ($secondChoice && ($quota[$secondChoice] ?? 0) > 0) {
                    $acceptedProdi = $secondChoice;
                    $quota[$secondChoice]--;
                }

                if ($acceptedProdi) {
                    $accepted++;
                }

                $rows[] = [
                    'announcement_batch_id' => $lockedBatch->id,
                    'user_id' => $ujian->user_id,
                    'nomor_peserta' => $ujian->user->peserta->nomor_peserta,
                    'skor_akhir' => $ujian->nilai,
                    'pilihan_1_id' => $firstChoice,
                    'pilihan_2_id' => $secondChoice,
                    'prodi_diterima_id' => $acceptedProdi,
                    'status_hasil' => $acceptedProdi
                        ? AnnouncementResultStatus::Lulus->value
                        : AnnouncementResultStatus::TidakLulus->value,
                    'ranking_position' => $index + 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $lockedBatch->results()->delete();

            if ($rows !== []) {
                $lockedBatch->results()->insert($rows);
            }

            $lockedBatch->update([
                'ranking_locked' => true,
                'generated_at' => $now,
            ]);

            return [
                'total' => count($rows),
                'accepted' => $accepted,
                'rejected' => count($rows) - $accepted,
            ];
        });
    }
}
