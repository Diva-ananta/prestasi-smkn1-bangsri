<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

      public function rules(): array
    {
        // Ambil ID dari route parameter 'siswa' (model binding)
        $siswa = $this->route('siswa');
        $id = $siswa ? $siswa->id : null;

        return [
            'nis' => ['required', 'string', 'max:20', Rule::unique('siswa', 'nis')->ignore($id)],
            'nisn' => ['required', 'string', 'max:20', Rule::unique('siswa', 'nisn')->ignore($id)],
            'nama' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas' => 'required|string|max:10',
            'jurusan' => 'required|string|max:50',
            'angkatan' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'is_published' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah terdaftar.',
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.unique' => 'NISN sudah terdaftar.',
            'nama.required' => 'Nama wajib diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'kelas.required' => 'Kelas wajib diisi.',
            'jurusan.required' => 'Jurusan wajib diisi.',
            'angkatan.required' => 'Angkatan wajib diisi.',
            'angkatan.min' => 'Angkatan minimal tahun 2000.',
            'angkatan.max' => 'Angkatan maksimal tahun ' . (date('Y') + 1) . '.',
        ];
    }
}