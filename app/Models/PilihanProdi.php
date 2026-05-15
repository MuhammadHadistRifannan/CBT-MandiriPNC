<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PilihanProdi extends Model
{
    //
        protected $table = 'pilihanprodi';
    protected $fillable = [
        'user_id', 
        'pilihan_1',
        'pilihan_2',
        'is_verified'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }   

    public function pilihan_prodi_1(){
        return $this->belongsTo(Prodi::class , 'pilihan_1');
    }
    public function pilihan_prodi_2(){
        return $this->belongsTo(Prodi::class , 'pilihan_2');
    }

    
}
