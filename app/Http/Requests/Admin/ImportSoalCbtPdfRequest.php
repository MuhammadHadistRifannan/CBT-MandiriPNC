<?php

namespace App\Http\Requests\Admin;

use App\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class ImportSoalCbtPdfRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::Admin->value;
    }

    public function rules(): array
    {
        return [
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }
}
