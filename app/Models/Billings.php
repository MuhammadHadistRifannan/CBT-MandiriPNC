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

    public static function checkBillings($user_id){
        $bill = Billings::where('user_id' , $user_id)->first();
        return $bill ?  $bill->isPay : null;
    }

    public static function createKodeBayar(){
        return 'PAY-' . Carbon::now()->format('Ymd') . '-' . strtoupper(Str::random(6));
    }
}
