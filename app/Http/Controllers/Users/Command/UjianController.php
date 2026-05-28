<?php

namespace App\Http\Controllers\Users\Command;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StartUjianRequest;
use App\Http\Requests\Users\StoreUjianAnswerRequest;
use App\Http\Requests\Users\SubmitUjianRequest;
use App\Services\UjianExamService;
use App\Services\UjianServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class UjianController extends Controller
{
    public function start(StartUjianRequest $request, UjianServices $service): RedirectResponse
    {
        $service->startFor($request->user());

        return redirect()
            ->route('ujian.show')
            ->with('success', 'Ujian berhasil dimulai. Timer berjalan berdasarkan waktu server.');
    }

    public function saveAnswer(StoreUjianAnswerRequest $request, UjianExamService $service): JsonResponse
    {
        $result = $service->saveAnswer($request->user(), $request->validated());

        return response()->json([
            'message' => 'Jawaban tersimpan.',
            ...$result,
        ]);
    }

    public function submit(SubmitUjianRequest $request, UjianExamService $service): JsonResponse
    {
        $result = $service->submitFor(
            $request->user(),
            $request->validated('submit_type', 'manual')
        );

        return response()->json([
            'message' => 'Ujian berhasil disubmit.',
            ...$result,
        ]);
    }
}
