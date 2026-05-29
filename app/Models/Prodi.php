<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prodi extends Model
{
    protected $table = 'prodi';

    public const TINGKAT = ['d3', 'd4'];

    protected $fillable = [
        'nama_prodi',
        'tingkat',
        'jurusan',
        'peminat',
        'daya_tampung',
        'kuota',
        'keketatan',
    ];

    protected function casts(): array
    {
        return [
            'peminat' => 'integer',
            'daya_tampung' => 'integer',
            'kuota' => 'integer',
            'keketatan' => 'decimal:2',
        ];
    }

    public function pilihanUtama(): HasMany
    {
        return $this->hasMany(PilihanProdi::class, 'pilihan_1');
    }

    public function pilihanCadangan(): HasMany
    {
        return $this->hasMany(PilihanProdi::class, 'pilihan_2');
    }
}
