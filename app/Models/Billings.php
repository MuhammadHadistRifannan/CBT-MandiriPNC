<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Str;

class Billings extends Model
{
    //
    protected $table = 'billings';
    protected $fillable = [
        'user_id',
        'kode_bayar',
        'isPay'
    ];

    public function checkBillings($id){
        $bill = $this->find($id)->first();
        return $bill->isPay;
    }

    public static function createKodeBayar(){
        return 'PAY-' . Carbon::now()->format('Ymd') . '-' . strtoupper(Str::random(6));
    }
}
