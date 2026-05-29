<?php

namespace App\Http\Requests\Pengawas;

use App\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExamTimerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::Pengawas->value;
    }

    public function rules(): array
    {
        return [
            'action' => ['required', Rule::in(['start', 'stop', 'extend'])],
            'minutes' => ['nullable', 'required_if:action,extend', 'integer', 'min:1', 'max:180'],
        ];
    }
}
