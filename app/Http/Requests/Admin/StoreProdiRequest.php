<?php

namespace App\Http\Requests\Admin;

use App\Models\Prodi;
use App\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProdiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === UserRole::Admin->value;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama_prodi' => is_string($this->nama_prodi) ? trim($this->nama_prodi) : $this->nama_prodi,
            'tingkat' => is_string($this->tingkat) ? strtolower(trim($this->tingkat)) : $this->tingkat,
            'jurusan' => is_string($this->jurusan) ? trim($this->jurusan) : $this->jurusan,
        ]);
    }

    public function rules(): array
    {
        $prodi = $this->route('prodi');

        return [
            'nama_prodi' => [
                'required',
                'string',
                'max:255',
                Rule::unique('prodi', 'nama_prodi')
                    ->where(fn ($query) => $query
                        ->where('tingkat', $this->input('tingkat'))
                        ->where('jurusan', $this->input('jurusan')))
                    ->ignore($prodi),
            ],
            'tingkat' => ['required', Rule::in(Prodi::TINGKAT)],
            'jurusan' => ['required', 'string', 'max:255'],
            'peminat' => ['required', 'integer', 'min:0'],
            'daya_tampung' => ['required', 'integer', 'min:1'],
            'keketatan_persen' => ['required', 'numeric', 'between:0,100'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_prodi.unique' => 'Program studi dengan jenjang dan jurusan tersebut sudah tersedia.',
            'keketatan_persen.between' => 'Keketatan harus berada di antara 0 sampai 100 persen.',
        ];
    }
}
