<?php

namespace App\Services;

use App\Models\Prodi;
use Illuminate\Validation\ValidationException;

class ProdiService
{
    public function create(array $data): Prodi
    {
        return Prodi::create($this->payload($data));
    }

    public function update(Prodi $prodi, array $data): Prodi
    {
        $prodi->update($this->payload($data));

        return $prodi->fresh();
    }

    public function delete(Prodi $prodi): void
    {
        if ($prodi->pilihanUtama()->exists() || $prodi->pilihanCadangan()->exists()) {
            throw ValidationException::withMessages([
                'prodi' => 'Program studi tidak dapat dihapus karena sudah dipilih peserta.',
            ]);
        }

        $prodi->delete();
    }

    private function payload(array $data): array
    {
        return [
            'nama_prodi' => trim($data['nama_prodi']),
            'tingkat' => strtolower($data['tingkat']),
            'jurusan' => trim($data['jurusan']),
            'peminat' => (int) $data['peminat'],
            'daya_tampung' => (int) $data['daya_tampung'],
            'kuota' => (int) $data['kuota'],
            'keketatan' => round(((float) $data['keketatan_persen']) / 100, 2),
        ];
    }
}
