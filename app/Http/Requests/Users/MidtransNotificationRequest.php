<?php

namespace App\Http\Requests\Users;

use App\Enums\BillingStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MidtransNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => ['required', 'string'],
            'status_code' => ['required', 'string'],
            'gross_amount' => ['required', 'numeric'],
            'signature_key' => ['required', 'string'],
            'transaction_status' => ['required', Rule::enum(BillingStatus::class)],
            'payment_type' => ['nullable', 'string'],
            'va_numbers' => ['nullable', 'array'],
            'va_numbers.0.va_number' => ['nullable', 'string'],
        ];
    }
}
