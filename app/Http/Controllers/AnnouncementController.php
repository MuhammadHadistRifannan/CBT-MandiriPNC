<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckAnnouncementRequest;
use App\Services\AnnouncementService;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function __construct(private readonly AnnouncementService $announcementService) {}

    public function index(): View
    {
        return view('pengumuman.index', [
            ...$this->announcementService->landingData(),
            'result' => null,
            'participantNumber' => null,
        ]);
    }

    public function check(CheckAnnouncementRequest $request): View
    {
        $participantNumber = $request->validated('nomor_peserta');

        return view('pengumuman.index', [
            ...$this->announcementService->landingData(),
            'result' => $this->announcementService->check($participantNumber),
            'participantNumber' => $participantNumber,
        ]);
    }
}
