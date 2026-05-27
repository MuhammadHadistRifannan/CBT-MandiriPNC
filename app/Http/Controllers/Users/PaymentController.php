<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\MidtransNotificationRequest;
use App\Services\BillingService;
use App\Services\PaymentNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function notification(MidtransNotificationRequest $request, PaymentNotificationService $service): JsonResponse
    {
        $service->handle($request->validated());

        return response()->json(['message' => 'ok']);
    }

    public function generateSnap(BillingService $service): JsonResponse
    {
        $data = $service->create_payment(auth()->id());

        return response()->json([
            'order_id' => $data['order_id'],
            'snap_token' => $data['snap_token'],
        ]);
    }

    public function sync(Request $request, PaymentNotificationService $service): JsonResponse
    {
        return response()->json($service->syncForUser($request->user()));
    }
}
