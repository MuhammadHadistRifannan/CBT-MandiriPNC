<?php

namespace App\Http\Requests\Admin;

use App\Enums\DokumenStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewDokumenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in([DokumenStatus::Verified->value, DokumenStatus::Rejected->value]),
            ],
            'rejection_note' => [
                Rule::requiredIf($this->input('status') === DokumenStatus::Rejected->value),
                'nullable',
                'string',
                'max:1000',
            ],
            'continue_next' => ['nullable', 'boolean'],
        ];
    }
}
