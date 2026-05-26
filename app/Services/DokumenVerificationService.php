<?php

namespace App\Services;

use App\Enums\DokumenStatus;
use App\Models\Dokumen;
use App\Models\User;
use Illuminate\Http\Request;

class DokumenVerificationService
{
    public function queue(Request $request): array
    {
        $query = Dokumen::query()
            ->with(['user.peserta', 'reviewer'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->string('search')->trim()->toString();

            $query->whereHas('user', function ($userQuery) use ($search) {
                $userQuery->where(function ($builder) use ($search) {
                    $builder->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('peserta', fn ($peserta) => $peserta
                            ->where('nomor_peserta', 'like', "%{$search}%"));
                });
            });
        }

        $selectedStatus = DokumenStatus::tryFrom($request->string('status')->toString());

        if ($selectedStatus) {
            $query->where('status', $selectedStatus->value);
        }

        $counts = Dokumen::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'dokumens' => $query->paginate(15)->withQueryString(),
            'stats' => [
                'total' => Dokumen::query()->count(),
                'pending' => (int) ($counts[DokumenStatus::Pending->value] ?? 0),
                'verified' => (int) ($counts[DokumenStatus::Verified->value] ?? 0),
                'rejected' => (int) ($counts[DokumenStatus::Rejected->value] ?? 0),
            ],
        ];
    }

    public function detail(Dokumen $dokumen): array
    {
        $dokumen->load([
            'user.peserta',
            'user.pilihan.pilihan_prodi_1',
            'user.pilihan.pilihan_prodi_2',
            'reviewer',
        ]);

        return [
            'dokumen' => $dokumen,
            'files' => $dokumen->availableFiles(),
            'nextPending' => Dokumen::query()
                ->where('status', DokumenStatus::Pending->value)
                ->whereKeyNot($dokumen->id)
                ->oldest()
                ->first(),
        ];
    }

    public function review(Dokumen $dokumen, DokumenStatus $status, ?string $note, User $reviewer): void
    {
        $dokumen->update([
            'status' => $status,
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'rejection_note' => $status === DokumenStatus::Rejected ? $note : null,
        ]);
    }
}
