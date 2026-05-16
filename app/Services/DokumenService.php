<?php

namespace App\Services;

use App\Models\Dokumen;
use Illuminate\Http\Request;

class DokumenService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function simpan(Request $request)
    {
        $user = auth()->user();

        $dokumen = Dokumen::where(
            'user_id',
            $user->id
        )->first();

        $request->validate([

            'foto' => [
                $dokumen?->foto ? 'nullable' : 'required',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048'
            ],

            'identitas' => [
                $dokumen?->kartu_identitas ? 'nullable' : 'required',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048'
            ],

            'surat_keterangan' => [
                'nullable',
                'mimes:pdf',
                'max:2048'
            ],

            'ijazah' => [
                'nullable',
                'mimes:pdf',
                'max:2048'
            ],
        ]);

        // upload file
        $foto = $request->file('foto')
            ? $request->file('foto')->store('dokumen/foto', 'public')
            : $dokumen?->foto;

        $identitas = $request->file('identitas')
            ? $request->file('identitas')->store('dokumen/identitas', 'public')
            : $dokumen?->kartu_identitas;

        $surat = $request->file('surat_keterangan')
            ? $request->file('surat_keterangan')->store('dokumen/surat-keterangan', 'public')
            : $dokumen?->suket;

        $ijazah = $request->file('ijazah')
            ? $request->file('ijazah')->store('dokumen/ijazah', 'public')
            : $dokumen?->ijazah;

        Dokumen::updateOrCreate(
            [
                'user_id' => $user->id
            ],
            [
                'foto' => $foto,
                'kartu_identitas' => $identitas,
                'suket' => $surat,
                'ijazah' => $ijazah,
            ]
        );

        return ResponseService::MakeResponse(Status::Success, 'Upload dokumen telah berhasil');
    }
}
