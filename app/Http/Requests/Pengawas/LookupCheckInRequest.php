<?php

namespace App\Http\Requests\Pengawas;

use App\Enums\UjianCheckInMethod;
use App\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LookupCheckInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::Pengawas->value;
    }

    public function rules(): array
    {
        return [
            'method' => ['required', Rule::enum(UjianCheckInMethod::class)],
            'qr_payload' => ['nullable', 'required_if:method,qr', 'string', 'max:255'],
            'kode_ujian' => ['nullable', 'required_if:method,manual', 'string', 'max:100'],
        ];
    }
}
