<?php

namespace App\Http\Controllers\Users\Query;

use App\Http\Controllers\Controller;
use App\Services\PilihanServices;
use App\Services\UjianServices;
use Illuminate\Http\Request;

class UjianController extends Controller
{
    //
    public function index(UjianServices $ujianservice , PilihanServices $pilihanservice){
        $isSavePermanent = $pilihanservice->check_savePermanently();
        $isVerified = $pilihanservice->isVerified(); 

        if (!$isSavePermanent){
            $status = 'locked';
        }

        if ($isSavePermanent && !$isVerified){
            $status = 'verification';
        }

        if ($isSavePermanent && $isVerified){
            $status = 'ready';
        }

        return view('portal-ujian' , compact('status'));
    }
}
