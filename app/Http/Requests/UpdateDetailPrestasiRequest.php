<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDetailPrestasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'peran' => 'required|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'peran.required' => 'Peran harus diisi.',
        ];
    }
}