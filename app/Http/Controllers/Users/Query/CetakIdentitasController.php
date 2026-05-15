<?php

namespace App\Http\Controllers\Users\Query;

use App\Http\Controllers\Controller;
use App\Services\PilihanServices;
use Illuminate\Http\Request;

class CetakIdentitasController extends Controller
{
    //
    public function index(PilihanServices $service){
        $isSavePermanent = $service->check_savePermanently();
        $status = $isSavePermanent ? 'valid' : 'invalid';

        return view('cetak-identitas' , compact('status'));
    }
}
