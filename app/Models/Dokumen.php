<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    //
    protected $table = 'dokumens';
    protected $fillable = [
        'user_id',
        'kartu_identitas',
        'foto',
        'suket',
        'ijazah'
    ];



}
