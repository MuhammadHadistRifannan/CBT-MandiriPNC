<?php

namespace App\Services\Payment;

class BankBNI implements IPaymentService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAttribute(): array{
        return [
            'name' => 'bni',
            'type' => 'bank_transfer'
        ];
    }

    public function Pay(){

    }
}
