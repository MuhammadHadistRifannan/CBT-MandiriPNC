<?php

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pengawas\DashboardPollingRequest;
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
}
