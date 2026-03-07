<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifikasiDisposisiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin', 'kabag']);
    }

    public function rules(): array
    {
        return [
            'status_akhir' => 'required|in:Revisi,ACC',
            'catatan_revisi' => 'required_if:status_akhir,Revisi|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'status_akhir.required' => 'Keputusan harus dipilih.',
            'catatan_revisi.required_if' => 'Catatan revisi harus diisi jika pekerjaan perlu direvisi.',
        ];
    }
}
