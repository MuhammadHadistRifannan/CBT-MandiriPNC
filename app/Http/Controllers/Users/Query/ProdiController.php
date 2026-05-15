<?php

namespace App\Http\Controllers\Users\Query;

use App\Http\Controllers\Controller;
use App\Models\Billings;
use App\Models\Prodi;
use App\Services\PilihanServices;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ProdiController extends Controller
{
    //
    public function index(PilihanServices $service)
    {
        
        $bill = Billings::where('user_id' , auth()->user()->id)->first();

        $data = [
            'prodis' => Prodi::all(), 
            'billing' => $bill,
            'isSave' => $service->check_savePermanently()
        ];

        return view('pilih-prodi', compact('data'));
    }
}
