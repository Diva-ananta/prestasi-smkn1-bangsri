<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePrestasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Pastikan true
    }

    protected function prepareForValidation()
    {
        // Jika tidak ada file sertifikat yang diupload, hapus dari rules
        if (!$this->hasFile('sertifikat')) {
            $this->request->remove('sertifikat');
        }
        if (!$this->hasFile('foto')) {
            $this->request->remove('foto');
        }
    }

    public function rules(): array
    {
        return [
            'nama_lomba' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'tingkat' => 'nullable|string|max:100',
            'hasil' => 'required|string|max:255',
            'penyelenggara' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'jenis_peserta' => 'required|in:Individu,Tim',
            'nama_tim' => 'required_if:jenis_peserta,Tim|nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:Draft,Publish',
            'keterangan' => 'nullable|string',
            'siswa_id' => [
                'required',
                'array',
                'min:1',
                Rule::when($this->input('jenis_peserta') === 'Individu', 'max:1'),
            ],
            'siswa_id.*' => 'integer|exists:siswa,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_tim.required_if' => 'Ekstrakurikuler wajib dipilih untuk peserta tim.',
            'siswa_id.required' => 'Pilih minimal satu siswa.',
            'siswa_id.min' => 'Pilih minimal satu siswa.',
            'siswa_id.max' => 'Peserta individu hanya boleh memiliki satu siswa.',
            'siswa_id.*.exists' => 'Siswa yang dipilih tidak ditemukan.',
        ];
    }
}