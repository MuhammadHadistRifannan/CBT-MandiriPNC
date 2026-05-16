<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Billings;
use App\Models\Peserta;
use App\Services\BillingService;
use Illuminate\Http\Request;
use Str;

class PaymentController extends Controller
{
    //
    public function payment_success(Request $request){
        $payment = Billings::where('kode_bayar' , $request->order_id)->first();
        if ($payment){
            $payment->update([
                'isPay' => true
            ]);

            Peserta::create([
                'user_id' => auth()->user()->id,
                'nomor_peserta' => Peserta::CreateNomorPeserta()
            ]);

        }

        return response()->json(['message' => 'ok']);
    }

    public function generateSnap(BillingService $service){
        $data = $service->create_payment(auth()->user()->id);
        return response()->json([
            'order_id' => $data['order_id'],
            'snap_token' => $data['snap_token']
        ]);
    }
}
