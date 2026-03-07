<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaporkanHasilKerjaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'catatan_staff' => 'required|string|min:5|max:2000',
            'file_hasil' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'catatan_staff.required' => 'Catatan pekerjaan harus diisi.',
            'catatan_staff.min' => 'Catatan minimal 5 karakter.',
            'file_hasil.mimes' => 'File harus berformat PDF, JPG, PNG, DOC, atau DOCX.',
            'file_hasil.max' => 'File maksimal 5MB.',
        ];
    }
}
