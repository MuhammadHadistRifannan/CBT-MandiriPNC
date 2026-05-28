<?php

namespace App\Http\Requests\Users;

use App\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class UjianStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::User->value;
    }

    public function rules(): array
    {
        return [];
    }
}
