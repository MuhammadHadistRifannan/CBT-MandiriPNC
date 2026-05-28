<?php

namespace App\Http\Requests\Users;

use App\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubmitUjianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::User->value;
    }

    public function rules(): array
    {
        return [
            'submit_type' => ['nullable', Rule::in(['manual', 'auto'])],
        ];
    }
}
