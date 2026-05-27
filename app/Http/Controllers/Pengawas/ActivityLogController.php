<?php

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pengawas\ActivityLogFilterRequest;
use App\Services\UjianActivityService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function __construct(private readonly UjianActivityService $activityService) {}

    public function index(ActivityLogFilterRequest $request): View
    {
        return view('pengawas.activity-log', $this->activityService->pageData($request->validated()));
    }

    public function data(ActivityLogFilterRequest $request): JsonResponse
    {
        return response()->json($this->activityService->feed($request->validated()));
    }
}
