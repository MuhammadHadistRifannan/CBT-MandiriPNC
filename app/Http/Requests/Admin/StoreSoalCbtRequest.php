<?php

namespace App\Http\Requests\Admin;

use App\Models\SoalCbt;
use App\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSoalCbtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::Admin->value;
    }

    public function rules(): array
    {
        return [
            'sub_soal' => ['required', Rule::in(SoalCbt::SUB_SOAL)],
            'pertanyaan' => ['required', 'string', 'max:5000'],
            'opsi_a' => ['required', 'string', 'max:2000'],
            'opsi_b' => ['required', 'string', 'max:2000'],
            'opsi_c' => ['required', 'string', 'max:2000'],
            'opsi_d' => ['required', 'string', 'max:2000'],
            'opsi_e' => ['nullable', 'required_if:jawaban_benar,E', 'string', 'max:2000'],
            'jawaban_benar' => ['required', Rule::in(SoalCbt::JAWABAN)],
            'pembahasan' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
