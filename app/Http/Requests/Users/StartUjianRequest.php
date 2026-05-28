<?php

namespace App\Http\Requests\Users;

use App\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class StartUjianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::User->value;
    }

    public function rules(): array
    {
        return [
            'agree' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'agree.accepted' => 'Anda harus menyetujui ketentuan ujian sebelum memulai.',
        ];
    }
}
