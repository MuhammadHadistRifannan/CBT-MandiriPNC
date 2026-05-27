<?php

namespace App\Http\Requests\Pengawas;

use App\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class StoreBroadcastMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::Pengawas->value;
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Isi pesan broadcast terlebih dahulu.',
            'message.max' => 'Pesan broadcast maksimal 500 karakter.',
        ];
    }
}
