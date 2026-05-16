<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Str;

class Peserta extends Model
{
    //
    protected $table = 'peserta';
    protected $fillable = [
        'user_id',
        'nomor_peserta'
    ];

    public static function CreateNomorPeserta(){
        return 'CBT-' . Carbon::now()->format('Ymd') . Str::random(4); 
    }
}
