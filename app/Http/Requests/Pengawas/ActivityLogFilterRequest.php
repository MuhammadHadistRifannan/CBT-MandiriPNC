<?php

namespace App\Http\Requests\Pengawas;

use App\Enums\UjianActivityType;
use App\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActivityLogFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::Pengawas->value;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'event_type' => ['nullable', Rule::enum(UjianActivityType::class)],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:50'],
        ];
    }
}
