<?php

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pengawas\ConfirmCheckInRequest;
use App\Http\Requests\Pengawas\LookupCheckInRequest;
use App\Models\Ujian;
use App\Services\PengawasCheckInService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CheckInController extends Controller
{
    public function __construct(private readonly PengawasCheckInService $checkInService) {}

    public function index(): View
    {
        return view('pengawas.check-in', $this->checkInService->pageData());
    }

    public function lookup(LookupCheckInRequest $request): JsonResponse
    {
        return response()->json([
            'participant' => $this->checkInService->lookup($request->validated()),
        ]);
    }

    public function confirm(ConfirmCheckInRequest $request, Ujian $ujian): JsonResponse
    {
        return response()->json([
            'message' => 'Check-in peserta berhasil dikonfirmasi.',
            'participant' => $this->checkInService->confirm($ujian, $request->validated(), $request->user()),
        ]);
    }
}
