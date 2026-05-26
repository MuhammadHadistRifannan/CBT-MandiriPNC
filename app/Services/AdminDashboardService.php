<?php

namespace App\Services;

use App\Enums\BillingStatus;
use App\Enums\DokumenStatus;
use App\Models\Billings;
use App\Models\Dokumen;
use App\Models\Peserta;
use App\Models\PilihanProdi;
use App\Models\Prodi;
use Illuminate\Support\Collection;

class AdminDashboardService
{
    public function data(): array
    {
        $totalSelections = PilihanProdi::query()->count();
        $favoriteProdis = $this->favoriteProdis($totalSelections);

        return [
            'stats' => [
                'participants' => Peserta::query()->count(),
                'balance' => (float) Billings::query()
                    ->where('transaction_status', BillingStatus::Settlement->value)
                    ->sum('gross_amount'),
                'verified_documents' => Dokumen::query()
                    ->where('status', DokumenStatus::Verified->value)
                    ->count(),
                'favorite_prodi' => $favoriteProdis->first(),
            ],
            'recentParticipants' => Peserta::query()
                ->with([
                    'user.pilihan.pilihan_prodi_1',
                    'user.billing',
                    'user.dokumen',
                ])
                ->latest()
                ->limit(5)
                ->get(),
            'favoriteProdis' => $favoriteProdis,
        ];
    }

    private function favoriteProdis(int $totalSelections): Collection
    {
        return Prodi::query()
            ->withCount(['pilihanUtama as selection_count'])
            ->whereHas('pilihanUtama')
            ->orderByDesc('selection_count')
            ->orderBy('nama_prodi')
            ->limit(3)
            ->get()
            ->map(fn (Prodi $prodi): array => [
                'name' => $prodi->nama_prodi,
                'selections' => (int) $prodi->selection_count,
                'percentage' => $totalSelections > 0
                    ? (int) round($prodi->selection_count / $totalSelections * 100)
                    : 0,
            ]);
    }
}
