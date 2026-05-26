<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SoalCbtStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ImportSoalCbtPdfRequest;
use App\Http\Requests\Admin\StoreSoalCbtRequest;
use App\Http\Requests\Admin\UpdateSoalCbtRequest;
use App\Models\SoalCbt;
use App\Services\Cbt\SoalCbtService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class SoalCbtController extends Controller
{
    public function __construct(private readonly SoalCbtService $soalCbtService)
    {
    }

    public function index(Request $request): View
    {
        $perPage = (int) $request->integer('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;

        $query = SoalCbt::query()
            ->with('pembuat')
            ->latest();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($builder) use ($search) {
                $builder->where('kode_soal', 'like', "%{$search}%")
                    ->orWhere('pertanyaan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sub_soal')) {
            $query->where('sub_soal', $request->string('sub_soal')->toString());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        $soal = $query->paginate($perPage)->withQueryString();
        $statusCounts = SoalCbt::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats = [
            'total' => SoalCbt::count(),
            'draft' => (int) ($statusCounts[SoalCbtStatus::Draft->value] ?? 0),
            'preview' => (int) ($statusCounts[SoalCbtStatus::Preview->value] ?? 0),
            'released' => (int) ($statusCounts[SoalCbtStatus::Released->value] ?? 0),
        ];

        $categoryStats = SoalCbt::query()
            ->selectRaw('sub_soal, count(*) as total')
            ->groupBy('sub_soal')
            ->pluck('total', 'sub_soal');

        return view('admin.soal', compact('soal', 'stats', 'categoryStats'));
    }

    public function create(): View
    {
        return view('admin.soal.form', [
            'soal' => null,
            'title' => 'Tambah Soal Manual',
            'action' => route('admin.soal.store'),
            'method' => 'POST',
        ]);
    }

    public function store(StoreSoalCbtRequest $request): RedirectResponse
    {
        $this->soalCbtService->createManual($request->validated(), $request->user()->id);

        return redirect()
            ->route('admin.soal')
            ->with('success', 'Soal manual berhasil disimpan sebagai draft.');
    }

    public function importPdf(ImportSoalCbtPdfRequest $request): RedirectResponse
    {
        try {
            $count = $this->soalCbtService->importPdf($request->file('pdf'), $request->user()->id);
        } catch (Throwable $exception) {
            return back()
                ->withInput()
                ->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('admin.soal')
            ->with('success', "{$count} soal berhasil diimpor sebagai draft dan siap direview.");
    }

    public function preview(SoalCbt $soal): View
    {
        $soal->load('pembuat');

        return view('admin.soal.preview', compact('soal'));
    }

    public function edit(SoalCbt $soal): View
    {
        return view('admin.soal.form', [
            'soal' => $soal,
            'title' => 'Edit Soal CBT',
            'action' => route('admin.soal.update', $soal),
            'method' => 'PUT',
        ]);
    }

    public function update(UpdateSoalCbtRequest $request, SoalCbt $soal): RedirectResponse
    {
        $this->soalCbtService->update($soal, $request->validated());

        return redirect()
            ->route('admin.soal.preview', $soal)
            ->with('success', 'Soal berhasil diperbarui dan masuk status preview.');
    }

    public function release(SoalCbt $soal): RedirectResponse
    {
        $this->soalCbtService->release($soal);

        return redirect()
            ->route('admin.soal')
            ->with('success', 'Soal berhasil dirilis ke platform ujian.');
    }

    public function destroy(SoalCbt $soal): RedirectResponse
    {
        $this->soalCbtService->delete($soal);

        return redirect()
            ->route('admin.soal')
            ->with('success', 'Soal berhasil dihapus dari bank soal.');
    }
}
