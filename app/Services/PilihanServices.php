<?php

namespace App\Services;

use App\Models\Billings;
use App\Models\PilihanProdi;
use Illuminate\Http\Request;
use Validator;

class PilihanServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function save_permanent(Request $request , BillingService $billService){

        $data = $request->validate([
            'pilihan_1' => 'required|different:pilihan_2',
            'pilihan_2' => 'required|different:pilihan_1'
        ]);


        PilihanProdi::insert([
            'user_id' => auth()->user()->id,
            'pilihan_1' => $data['pilihan_1'],
            'pilihan_2' => $data['pilihan_2']
        ]);

        $billing = $billService->create_billing();
        $payment = $billService->create_payment(auth()->user()->id);

        return ResponseService::MakeResponse(Status::Success , 'Sukses Menyimpan Permanen' , [
            'billing' => $billing,
            'payment' => $payment
        ]);
    }

    public function check_savePermanently()
    {
        $isExist = PilihanProdi::where('user_id', auth()->user()->id)->exists();
        return $isExist;
    }


    public function verification($data)
    {
        $validate = Validator::make($data, [
            'user_id' => 'required',
            'pilihan_1' => 'required|different:pilihan_2',
            'pilihan_2' => 'required|different:pilihan_1',
        ]);

        if ($validate->fails()) {
            return ResponseService::MakeResponse(Status::Fail, $validate->errors());
        }

        $validated = $validate->validated(); 

        PilihanProdi::where('user_id', $validated['user_id'])->update([
            'is_verified' => true
        ]);

        return ResponseService::MakeResponse(Status::Success, "Verifikasi identitas berhasil");
    }

    public function isVerified()
    {
        $user = PilihanProdi::where('user_id', auth()->user()->id)->first();
        if (!$user)
            return false;
        return $user->is_verified;
    }
}
