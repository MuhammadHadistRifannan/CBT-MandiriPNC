<?php

namespace App\Http\Controllers\Users\Command;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUjianActivityRequest;
use App\Services\UjianActivityService;
use Illuminate\Http\JsonResponse;

class UjianActivityController extends Controller
{
    public function store(StoreUjianActivityRequest $request, UjianActivityService $activityService): JsonResponse
    {
        $activityService->record($request->user(), $request->validated());

        return response()->json(['message' => 'Aktivitas tersimpan.']);
    }
}
