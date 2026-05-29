<?php

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pengawas\DashboardPollingRequest;
use App\Http\Requests\Pengawas\UpdateExamTimerRequest;
use App\Http\Requests\Pengawas\UpdateParticipantFlagRequest;
use App\Models\Ujian;
use App\Services\PengawasDashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly PengawasDashboardService $dashboardService) {}

    public function index(): View
    {
        return view('pengawas.dashboard', $this->dashboardService->data());
    }

    public function data(DashboardPollingRequest $request): JsonResponse
    {
        return response()->json($this->dashboardService->data());
    }

    public function updateFlag(UpdateParticipantFlagRequest $request, Ujian $ujian): JsonResponse
    {
        return response()->json([
            'message' => 'Status flag peserta berhasil diperbarui.',
            'participant' => $this->dashboardService->updateFlag($ujian, $request->validated()),
        ]);
    }

    public function updateTimer(UpdateExamTimerRequest $request, Ujian $ujian): JsonResponse
    {
        return response()->json([
            'message' => 'Timer ujian berhasil diperbarui.',
            'participant' => $this->dashboardService->updateTimer($ujian, $request->validated()),
        ]);
    }
}
