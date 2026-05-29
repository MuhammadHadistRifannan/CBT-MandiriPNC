<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProdiRequest;
use App\Http\Requests\Admin\UpdateProdiRequest;
use App\Models\Prodi;
use App\Services\ProdiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProdiController extends Controller
{
    public function __construct(private readonly ProdiService $prodiService)
    {
    }

    public function index(Request $request): View
    {
        $query = Prodi::query()->latest();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($builder) use ($search) {
                $builder->where('nama_prodi', 'like', "%{$search}%")
                    ->orWhere('jurusan', 'like', "%{$search}%");
            });
        }

        if (in_array($request->string('tingkat')->toString(), Prodi::TINGKAT, true)) {
            $query->where('tingkat', $request->string('tingkat')->toString());
        }

        if ($request->filled('jurusan')) {
            $query->where('jurusan', $request->string('jurusan')->toString());
        }

        $prodis = $query->paginate(10)->withQueryString();
        $favorite = Prodi::query()->orderByDesc('peminat')->first();
        $tightest = Prodi::query()->orderByDesc('keketatan')->first();

        $stats = [
            'total' => Prodi::count(),
            'daya_tampung' => (int) Prodi::sum('daya_tampung'),
            'kuota' => (int) Prodi::sum('kuota'),
            'favorite' => $favorite?->nama_prodi,
            'tightest' => $tightest?->keketatan,
        ];

        $jurusanOptions = Prodi::query()
            ->whereNotNull('jurusan')
            ->distinct()
            ->orderBy('jurusan')
            ->pluck('jurusan');

        $editingId = old('_method') === 'PUT'
            ? filter_var(old('_editing_id'), FILTER_VALIDATE_INT)
            : false;
        $editingId = $editingId > 0 ? $editingId : false;

        $formState = [
            'method' => $editingId ? 'PUT' : 'POST',
            'editing_id' => $editingId ?: null,
            'action' => $editingId
                ? route('admin.prodi.update', $editingId)
                : route('admin.prodi.store'),
        ];

        return view('admin.program-studi', compact('prodis', 'stats', 'jurusanOptions', 'formState'));
    }

    public function store(StoreProdiRequest $request): RedirectResponse
    {
        $this->prodiService->create($request->validated());

        return redirect()
            ->route('admin.prodi')
            ->with('success', 'Program studi berhasil ditambahkan.');
    }

    public function update(UpdateProdiRequest $request, Prodi $prodi): RedirectResponse
    {
        $this->prodiService->update($prodi, $request->validated());

        return redirect()
            ->route('admin.prodi')
            ->with('success', 'Program studi berhasil diperbarui.');
    }

    public function destroy(Prodi $prodi): RedirectResponse
    {
        try {
            $this->prodiService->delete($prodi);
        } catch (ValidationException $exception) {
            return redirect()
                ->route('admin.prodi')
                ->with('error', $exception->errors()['prodi'][0]);
        }

        return redirect()
            ->route('admin.prodi')
            ->with('success', 'Program studi berhasil dihapus.');
    }
}
