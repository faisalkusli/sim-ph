<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidasiAwalSuratRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin', 'kabag']);
    }

    public function rules(): array
    {
        return [
            'status_verifikasi' => 'required|in:Setuju,Tolak',
            'alasan_tolak' => 'required_if:status_verifikasi,Tolak|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'status_verifikasi.required' => 'Keputusan validasi harus dipilih.',
            'alasan_tolak.required_if' => 'Alasan penolakan harus diisi jika surat ditolak.',
        ];
    }
}
