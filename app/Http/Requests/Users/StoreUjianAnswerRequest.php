<?php

namespace App\Http\Requests\Users;

use App\Models\SoalCbt;
use App\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUjianAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::User->value;
    }

    public function rules(): array
    {
        return [
            'soal_id' => ['required', 'integer', Rule::exists('soal_cbt', 'id')],
            'jawaban' => ['nullable', 'string', Rule::in(SoalCbt::JAWABAN)],
        ];
    }
}
