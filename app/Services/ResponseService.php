<?php

namespace App\Services;

enum Status: string{
    case Fail = "fail";
    case Success = "success";
    case Warning = "warning";
}

class ResponseService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
     public static function MakeResponse(Status $status , $message = null ,$data =null){
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
    }

}
