<?php

namespace App\Http\Requests\Users;

use App\Enums\UjianActivityType;
use App\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUjianActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::User->value;
    }

    public function rules(): array
    {
        return [
            'event_type' => ['required', Rule::enum(UjianActivityType::class)],
        ];
    }
}
