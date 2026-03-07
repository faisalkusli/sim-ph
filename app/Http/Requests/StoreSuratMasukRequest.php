<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuratMasukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'no_agenda' => 'required|unique:surat_masuks,no_agenda',
            'no_surat_pengirim' => 'required|string|max:100',
            'asal_instansi' => 'required|string|max:255',
            'jenis_surat' => 'nullable|string|max:100',
            'perihal' => 'required|string|max:1000',
            'tgl_surat' => 'required|date|before_or_equal:today',
            'tgl_diterima' => 'required|date|before_or_equal:today',
            'file_scan' => 'required|mimes:pdf,jpg,png,jpeg|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'no_agenda.required' => 'No Agenda harus diisi.',
            'no_agenda.unique' => 'No Agenda sudah terdaftar di sistem.',
            'no_surat_pengirim.required' => 'No Surat dari Pengirim harus diisi.',
            'asal_instansi.required' => 'Asal Instansi harus diisi.',
            'perihal.required' => 'Perihal surat harus diisi.',
            'tgl_surat.required' => 'Tanggal Surat harus diisi.',
            'tgl_diterima.required' => 'Tanggal Diterima harus diisi.',
            'file_scan.required' => 'File scan surat harus diupload.',
            'file_scan.mimes' => 'File scan harus berformat PDF, JPG, PNG, atau JPEG.',
            'file_scan.max' => 'File scan maksimal 10MB.',
        ];
    }
}
