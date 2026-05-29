<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AnnouncementStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveAnnouncementBatchRequest;
use App\Models\AnnouncementBatch;
use App\Services\AnnouncementService;
use App\Services\SelectionRankingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function __construct(
        private readonly AnnouncementService $announcementService,
        private readonly SelectionRankingService $selectionRankingService,
    ) {}

    public function index(Request $request): View
    {
        return view('admin.pengumuman', [
            ...$this->announcementService->adminData($request->only('search', 'batch_id')),
            'announcementStatuses' => AnnouncementStatus::cases(),
        ]);
    }

    public function storeBatch(SaveAnnouncementBatchRequest $request): RedirectResponse
    {
        $this->announcementService->saveBatch($request->validated(), $request->user()->id);

        return redirect()
            ->route('admin.pengumuman')
            ->with('success', 'Jadwal pengumuman berhasil disimpan.');
    }

    public function updateBatch(SaveAnnouncementBatchRequest $request, AnnouncementBatch $batch): RedirectResponse
    {
        $this->announcementService->saveBatch($request->validated(), $request->user()->id, $batch);

        return redirect()
            ->route('admin.pengumuman')
            ->with('success', 'Jadwal pengumuman berhasil diperbarui.');
    }

    public function generate(AnnouncementBatch $batch): RedirectResponse
    {
        try {
            $summary = $this->selectionRankingService->generate($batch);
        } catch (ValidationException $exception) {
            return redirect()
                ->route('admin.pengumuman', ['batch_id' => $batch->id])
                ->withErrors($exception->errors());
        }

        return redirect()
            ->route('admin.pengumuman', ['batch_id' => $batch->id])
            ->with('success', "Ranking berhasil digenerate: {$summary['accepted']} lulus, {$summary['rejected']} tidak lulus dari {$summary['total']} peserta.");
    }
}
