<?php

namespace App\Services;

use App\Enums\AnnouncementStatus;
use App\Models\AnnouncementBatch;
use App\Models\AnnouncementResult;
use App\Models\Prodi;
use Illuminate\Support\Carbon;

class AnnouncementService
{
    public function landingData(): array
    {
        $batch = $this->currentOrUpcomingBatch();

        return [
            'year' => $batch?->tahun ?? now('Asia/Jakarta')->year,
            'publishedAt' => $batch?->published_at,
            'announcementDate' => $batch?->announcement_date,
            'activeBatch' => $batch,
            'isPublished' => $batch?->isOpen() ?? false,
        ];
    }

    public function currentOrUpcomingBatch(): ?AnnouncementBatch
    {
        $now = now('Asia/Jakarta');

        return AnnouncementBatch::query()
            ->where('status', AnnouncementStatus::Published)
            ->where('announcement_date', '<=', $now)
            ->latest('announcement_date')
            ->first()
            ?? AnnouncementBatch::query()
                ->where('status', AnnouncementStatus::Published)
                ->where('announcement_date', '>', $now)
                ->orderBy('announcement_date')
                ->first()
            ?? AnnouncementBatch::query()
                ->latest('announcement_date')
                ->first();
    }

    public function openBatch(): ?AnnouncementBatch
    {
        return AnnouncementBatch::query()
            ->where('status', AnnouncementStatus::Published)
            ->where('announcement_date', '<=', now('Asia/Jakarta'))
            ->latest('announcement_date')
            ->first();
    }

    public function check(string $participantNumber): array
    {
        $batch = $this->currentOrUpcomingBatch();

        if (! $batch) {
            return [
                'state' => 'not_published',
                'message' => 'Pengumuman belum tersedia. Silakan kembali sesuai jadwal yang telah ditentukan.',
            ];
        }

        if (! $batch->isOpen()) {
            return [
                'state' => $batch->status === AnnouncementStatus::Closed ? 'closed' : 'not_published',
                'message' => $batch->status === AnnouncementStatus::Closed
                    ? 'Periode pengumuman telah ditutup. Silakan hubungi panitia PMB.'
                    : 'Pengumuman belum tersedia. Silakan kembali sesuai jadwal yang telah ditentukan.',
            ];
        }

        $announcement = AnnouncementResult::query()
            ->with(['user', 'prodiDiterima', 'batch'])
            ->where('announcement_batch_id', $batch->id)
            ->where('nomor_peserta', trim($participantNumber))
            ->first();

        if (! $announcement) {
            return [
                'state' => 'not_found',
                'message' => 'Data pengumuman tidak ditemukan. Periksa kembali nomor peserta Anda.',
            ];
        }

        return [
            'state' => 'found',
            'announcement' => $announcement,
            'participant' => [
                'name' => $announcement->user?->name ?? 'Peserta PMB',
                'number' => $announcement->nomor_peserta,
                'program' => $announcement->prodiDiterima
                    ? strtoupper($announcement->prodiDiterima->tingkat).' '.$announcement->prodiDiterima->nama_prodi
                    : '-',
                'year' => $announcement->batch?->tahun ?? Carbon::now('Asia/Jakarta')->year,
            ],
        ];
    }

    public function adminData(array $filters = []): array
    {
        $announcements = AnnouncementResult::query()
            ->with(['user', 'prodiDiterima', 'pilihanPertama', 'pilihanKedua', 'batch'])
            ->when($filters['batch_id'] ?? null, fn ($query, $batchId) => $query->where('announcement_batch_id', $batchId))
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder->where('nomor_peserta', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderByDesc('announcement_batch_id')
            ->orderBy('ranking_position')
            ->paginate(15)
            ->withQueryString();

        $batches = AnnouncementBatch::query()
            ->withCount('results')
            ->latest('announcement_date')
            ->get();

        $prodis = Prodi::query()
            ->orderBy('nama_prodi')
            ->get(['id', 'nama_prodi', 'tingkat', 'jurusan', 'kuota', 'daya_tampung']);

        return compact('announcements', 'batches', 'filters', 'prodis');
    }

    public function saveBatch(array $data, int $adminId, ?AnnouncementBatch $batch = null): AnnouncementBatch
    {
        $status = AnnouncementStatus::from($data['status']);
        $payload = [
            ...$data,
            'created_by' => $batch?->created_by ?? $adminId,
            'published_at' => $status === AnnouncementStatus::Published
                ? ($batch?->published_at ?? now('Asia/Jakarta'))
                : null,
        ];

        if ($batch) {
            $batch->update($payload);

            return $batch->refresh();
        }

        return AnnouncementBatch::create($payload);
    }
}
