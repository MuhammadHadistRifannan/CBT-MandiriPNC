<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Billings;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    //
    public function payment_success(Request $request){
        $payment = Billings::where('kode_bayar' , $request->order_id)->first();
        if ($payment){
            $payment->update([
                'isPay' => true
            ]);

        }

        return response()->json(['message' => 'ok']);
    }
}
