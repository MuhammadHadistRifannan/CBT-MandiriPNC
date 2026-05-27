<?php

namespace App\Http\Requests\Pengawas;

use App\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class DashboardPollingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::Pengawas->value;
    }

    public function rules(): array
    {
        return [];
    }
}
