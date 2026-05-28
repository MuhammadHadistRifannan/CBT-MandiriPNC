<?php

namespace App\Http\Controllers\Users\Query;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UjianStatusRequest;
use App\Services\UjianExamService;
use App\Services\UjianServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class UjianController extends Controller
{
    public function index(Request $request, UjianServices $service): View
    {
        $access = $service->accessFor($request->user());
        $status = $access['status'];
        $activityTrackingEnabled = $access['activityTrackingEnabled'];

        return view('portal-ujian', compact('status', 'activityTrackingEnabled', 'access'));
    }

    public function show(Request $request, UjianExamService $service): View|RedirectResponse
    {
        try {
            $data = $service->page($request->user(), (int) $request->query('page', 1));
        } catch (ValidationException $exception) {
            return redirect()
                ->route('portal.ujian')
                ->withErrors($exception->errors());
        }

        return view('ujian.show', $data);
    }

    public function status(UjianStatusRequest $request, UjianExamService $service): JsonResponse
    {
        return response()->json($service->statusFor($request->user()));
    }
}
