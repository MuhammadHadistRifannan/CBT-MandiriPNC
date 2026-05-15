<?php

namespace App\Http\Controllers\Users\Command;

use App\Http\Controllers\Controller;
use App\Services\BillingService;
use App\Services\PilihanServices;
use App\Services\Status;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ProdiController extends Controller
{
    //
    public function simpan(PilihanServices $pilService , BillingService $billService , Request $request){
        $result = $pilService->save_permanent($request , $billService);

        if ($result['status'] != Status::Success) return redirect()->back();

        Alert::success('Success' , $result['message']);
        return redirect()->back()
        ->with('snap_token' , $result['data']['payment']['snap_token'])
        ->with('order_id' , $result['data']['payment']['order_id']);
    }
}

