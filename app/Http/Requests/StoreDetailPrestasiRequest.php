<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDetailPrestasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'siswa_id' => [
                'required',
                'exists:siswa,id',
                Rule::unique('detail_prestasi')->where(function ($query) {
                    return $query->where('prestasi_id', $this->route('prestasi')->id);
                }),
            ],
            'peran' => 'required|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'siswa_id.required' => 'Pilih siswa terlebih dahulu.',
            'siswa_id.exists' => 'Siswa tidak ditemukan.',
            'siswa_id.unique' => 'Siswa ini sudah terdaftar pada prestasi ini.',
            'peran.required' => 'Peran harus diisi.',
        ];
    }
}