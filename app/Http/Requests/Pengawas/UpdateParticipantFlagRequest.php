<?php

namespace App\Http\Requests\Pengawas;

use App\Enums\UjianFlagStatus;
use App\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParticipantFlagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::Pengawas->value;
    }

    public function rules(): array
    {
        return [
            'flag_status' => ['required', Rule::enum(UjianFlagStatus::class)],
            'flagged_reason' => ['nullable', 'string', 'max:500'],
        ];
    }
}
