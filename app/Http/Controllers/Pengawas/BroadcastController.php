<?php

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pengawas\StoreBroadcastMessageRequest;
use App\Services\PengawasBroadcastService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BroadcastController extends Controller
{
    public function __construct(private readonly PengawasBroadcastService $broadcastService) {}

    public function index(): View
    {
        return view('pengawas.broadcast', $this->broadcastService->pageData());
    }

    public function store(StoreBroadcastMessageRequest $request): RedirectResponse
    {
        $notice = $this->broadcastService->send($request->validated(), $request->user());

        return redirect()->route('pengawas.broadcast')->with('success', $notice);
    }
}
