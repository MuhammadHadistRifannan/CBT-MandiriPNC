<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DokumenStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReviewDokumenRequest;
use App\Models\Dokumen;
use App\Services\DokumenVerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DokumenController extends Controller
{
    public function __construct(private readonly DokumenVerificationService $verificationService) {}

    public function index(Request $request): View
    {
        return view('admin.dokumen.index', $this->verificationService->queue($request));
    }

    public function show(Dokumen $dokumen): View
    {
        return view('admin.dokumen.show', $this->verificationService->detail($dokumen));
    }

    public function review(ReviewDokumenRequest $request, Dokumen $dokumen): RedirectResponse
    {
        $data = $request->validated();
        $status = DokumenStatus::from($data['status']);

        $this->verificationService->review(
            $dokumen,
            $status,
            $data['rejection_note'] ?? null,
            $request->user(),
        );

        $message = $status === DokumenStatus::Verified
            ? 'Dokumen peserta berhasil diverifikasi.'
            : 'Dokumen peserta ditolak dan menunggu perbaikan.';

        if ($request->boolean('continue_next')) {
            $next = Dokumen::query()
                ->where('status', DokumenStatus::Pending->value)
                ->whereKeyNot($dokumen->id)
                ->oldest()
                ->first();

            if ($next) {
                return redirect()
                    ->route('admin.dokumen.show', $next)
                    ->with('success', $message);
            }
        }

        return redirect()
            ->route('admin.dokumen', ['status' => DokumenStatus::Pending->value])
            ->with('success', $message);
    }
}
