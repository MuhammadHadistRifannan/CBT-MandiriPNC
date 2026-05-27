<?php

namespace App\Http\Controllers\Users\Query;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\BroadcastFeedRequest;
use App\Http\Requests\Users\DismissBroadcastMessageRequest;
use App\Models\BroadcastMessage;
use App\Services\ParticipantBroadcastService;
use Illuminate\Http\JsonResponse;

class BroadcastMessageController extends Controller
{
    public function __construct(private readonly ParticipantBroadcastService $broadcastService) {}

    public function index(BroadcastFeedRequest $request): JsonResponse
    {
        return response()->json([
            'messages' => $this->broadcastService->feedFor($request->user()),
        ]);
    }

    public function dismiss(DismissBroadcastMessageRequest $request, BroadcastMessage $broadcastMessage): JsonResponse
    {
        $this->broadcastService->dismiss($broadcastMessage, $request->user());

        return response()->json(['message' => 'Pesan ditutup.']);
    }
}
