<?php

namespace App\Services\Payment;

interface IPaymentService
{
    public function getAttribute():array;
    public function Pay();

}
