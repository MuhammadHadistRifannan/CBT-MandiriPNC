<?php

namespace App\Http\Requests\Admin;

use App\Enums\AnnouncementResultStatus;
use App\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::Admin->value;
    }

    public function rules(): array
    {
        return [
            'announcement_batch_id' => ['required', 'integer', 'exists:announcement_batches,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'nomor_peserta' => [
                'required',
                'string',
                'max:50',
                Rule::unique('announcements', 'nomor_peserta')
                    ->where('announcement_batch_id', $this->input('announcement_batch_id'))
                    ->ignore($this->route('announcement')),
            ],
            'status_hasil' => ['required', Rule::enum(AnnouncementResultStatus::class)],
            'prodi_diterima' => ['nullable', 'integer', 'exists:prodi,id'],
            'jalur_seleksi' => ['required', 'string', 'max:100'],
        ];
    }
}
