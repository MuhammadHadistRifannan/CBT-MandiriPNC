<?php

namespace App\Http\Requests\Admin;

use App\Enums\AnnouncementStatus;
use App\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveAnnouncementBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::Admin->value;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'tahun' => ['required', 'integer', 'min:2020', 'max:2100'],
            'announcement_date' => ['required', 'date'],
            'status' => ['required', Rule::enum(AnnouncementStatus::class)],
        ];
    }
}
