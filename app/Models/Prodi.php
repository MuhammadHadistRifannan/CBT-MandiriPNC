<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodi';

    protected $fillable = [
        'nama_prodi',
        'tingkat',
        'peminat',
        'daya_tampung',
        'keketatan',
    ];

    public function pilihan(){
        return $this->hasMany(PilihanProdi::class);
    }
}
