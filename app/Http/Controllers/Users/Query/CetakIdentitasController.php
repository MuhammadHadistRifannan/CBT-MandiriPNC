<?php

namespace App\Http\Controllers\Users\Query;

use App\Http\Controllers\Controller;
use App\Services\CetakIdentitasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CetakIdentitasController extends Controller
{
    public function index(Request $request, CetakIdentitasService $service)
    {
        $cetakAccess = $service->accessFor($request->user());
        $status = $cetakAccess['canPrint'] ? 'valid' : 'invalid';

        return view('cetak-identitas', compact('status', 'cetakAccess'));
    }

    public function uploadFoto(Request $request, CetakIdentitasService $service)
    {
        if (! $service->accessFor($request->user())['canPrint']) {
            return redirect()
                ->route('cetak.identitas')
                ->with('error', 'Cetak identitas masih terkunci. Selesaikan pembayaran dan validasi dokumen terlebih dahulu.');
        }

        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = $request->user();

        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        $path = $request->file('foto')->store('foto-peserta', 'public');

        $user->update([
            'foto' => $path
        ]);

        return redirect()->back()->with('success', 'Foto berhasil diperbarui');
    }
}
