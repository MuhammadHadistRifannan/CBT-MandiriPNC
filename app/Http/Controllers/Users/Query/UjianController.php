<?php

namespace App\Http\Controllers\Users\Query;

use App\Http\Controllers\Controller;
use App\Services\UjianServices;
use Illuminate\Http\Request;

class UjianController extends Controller
{
    public function index(Request $request, UjianServices $service)
    {
        $access = $service->accessFor($request->user());
        $status = $access['status'];
        $activityTrackingEnabled = $access['activityTrackingEnabled'];

        return view('portal-ujian', compact('status', 'activityTrackingEnabled'));
    }
}
