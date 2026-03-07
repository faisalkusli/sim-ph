<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDisposisiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'surat_masuk_id' => 'required|exists:surat_masuks,id',
            'tujuan_user_id' => 'required|exists:users,id|different:user_id',
            'sifat' => 'required|string|max:50',
            'jenis_surat' => 'required|string|max:100',
            'instruksi' => 'required|string|min:10|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'surat_masuk_id.required' => 'Surat masuk harus dipilih.',
            'tujuan_user_id.required' => 'Tujuan pengirim harus dipilih.',
            'tujuan_user_id.different' => 'Tidak bisa mengirim disposisi ke diri sendiri.',
            'sifat.required' => 'Sifat disposisi harus diisi.',
            'jenis_surat.required' => 'Jenis surat harus dipilih.',
            'instruksi.required' => 'Instruksi tidak boleh kosong.',
            'instruksi.min' => 'Instruksi minimal 10 karakter.',
        ];
    }
}
