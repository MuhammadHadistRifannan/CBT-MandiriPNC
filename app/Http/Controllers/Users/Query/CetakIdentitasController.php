<?php

namespace App\Http\Controllers\Users\Query;

use App\Http\Controllers\Controller;
use App\Models\Billings;
use App\Services\PilihanServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CetakIdentitasController extends Controller
{
    //
    public function index(PilihanServices $service)
    {
        $isSavePermanent = $service->check_savePermanently();
        $isPay = Billings::checkBillings(auth()->user()->id);

        $status = $isSavePermanent && $isPay ? 'valid' : 'invalid';

        return view('cetak-identitas', compact('status'));
    }

    public function uploadFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $user = auth()->user();

        // hapus foto lama
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        // upload baru
        $path = $request->file('foto')->store('foto-peserta', 'public');

        $user->update([
            'foto' => $path
        ]);

        return redirect()->back()->with('success' , 'Foto berhasil diperbarui');
    }
}
