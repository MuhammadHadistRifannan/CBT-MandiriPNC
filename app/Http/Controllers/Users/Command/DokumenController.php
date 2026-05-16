<?php

namespace App\Http\Controllers\Users\Command;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Services\DokumenService;
use App\Services\Status;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class DokumenController extends Controller
{
    //
    public function simpan(DokumenService $serviceDoc ,Request $request){
        
        $result = $serviceDoc->simpan($request);

        if ($result['status'] != Status::Success){
            Alert::error('error' , $result['message']);
        }

        Alert::success('Success' , $result['message']);
        return redirect()->back()->with(
            'success',
            'Dokumen berhasil diupload'
        );
    }
}
