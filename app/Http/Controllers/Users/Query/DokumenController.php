<?php

namespace App\Http\Controllers\Users\Query;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    //

    public function index(){
        $dokumen = Dokumen::where('user_id' , auth()->user()->id)->first();
        return view ('upload-dokumen' , compact('dokumen'));
    }
}
