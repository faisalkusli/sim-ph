@extends('layouts.app')

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Inbox & Tugas Saya</h1>
        <nav class="flex items-center gap-2 text-sm text-slate-400 mt-1">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Dashboard</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-slate-600">Inbox</span>
        </nav>
    </div>

    {{-- Alerts --}}
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex gap-3">
        <i class="fas fa-exclamation-triangle text-red-500 text-lg flex-shrink-0 mt-0.5"></i>
        <ul class="list-disc list-inside text-sm space-y-0.5">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center gap-3">
        <i class="fas fa-check-circle text-green-500 text-lg flex-shrink-0"></i>
        <span class="text-sm">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Main Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="p-5 border-b border-slate-100">
            <h2 class="font-semibold text-slate-700 flex items-center gap-2">
                <i class="fas fa-inbox text-blue-600"></i> Daftar Tugas Masuk
                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-0.5 rounded-full">
                    {{ $disposisi_masuk->count() }}
                </span>
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-800 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold w-10">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Dari / Waktu</th>
                        <th class="px-4 py-3 text-left font-semibold">Perihal Surat</th>
                        <th class="px-4 py-3 text-left font-semibold">Status & Catatan</th>
                        <th class="px-4 py-3 text-center font-semibold w-44">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($disposisi_masuk as $d)
                    <tr class="hover:bg-slate-50 transition-colors">
                        {{-- No + Badge Tipe --}}
                        <td class="px-4 py-3 text-center text-slate-400">
                            {{ $loop->iteration }}
                            @if($d->tujuan_user_id === auth()->id())
                            <div class="mt-1"><span class="inline-block bg-blue-100 text-blue-700 text-xs font-bold px-1.5 py-0.5 rounded-full">TUGAS</span></div>
                            @elseif($d->dari_user_id === auth()->id() && in_array($d->status, [2,3]))
                            <div class="mt-1"><span class="inline-block bg-amber-100 text-amber-700 text-xs font-bold px-1.5 py-0.5 rounded-full">VERIF</span></div>
                            @elseif($d->dari_user_id === auth()->id() && in_array($d->status, [0,1,4]))
                            <div class="mt-1"><span class="inline-block bg-slate-100 text-slate-500 text-xs font-bold px-1.5 py-0.5 rounded-full">PANTAU</span></div>
                            @endif
                        </td>

                        {{-- Dari --}}
                        <td class="px-4 py-3">
                            <div class="font-semibold text-slate-800">{{ $d->pengirim->name ?? 'Sistem' }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $d->created_at->format('d M Y, H:i') }}</div>
                        </td>

                        {{-- Perihal --}}
                        <td class="px-4 py-3">
                            <div class="text-xs text-slate-400 mb-0.5">No: {{ $d->surat->no_surat ?? $d->surat->no_agenda ?? '-' }}</div>
                            <div class="text-slate-700">{{ Str::limit($d->surat->perihal ?? '-', 55) }}</div>
                            @if($d->instruksi)
                            <div class="text-xs text-blue-600 mt-1 italic">"{{ Str::limit($d->instruksi, 50) }}"</div>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3">
                            @php
                                $dBadge = match($d->status) {
                                    0 => 'bg-amber-100 text-amber-700',
                                    1 => 'bg-blue-100 text-blue-700',
                                    2 => 'bg-purple-100 text-purple-700',
                                    3 => 'bg-orange-100 text-orange-700',
                                    4 => 'bg-red-100 text-red-700',
                                    5 => 'bg-green-100 text-green-700',
                                    default => 'bg-slate-100 text-slate-600',
                                };
                                $statusLabels = [0=>'Belum Dibaca',1=>'Sedang Dikerjakan',2=>'Tunggu Verif Kasubag',3=>'Tunggu Verif Kabag',4=>'Perlu Revisi',5=>'Selesai'];
                            @endphp
                            <span class="inline-block {{ $dBadge }} text-xs font-semibold px-2.5 py-1 rounded-full">
                                {{ $statusLabels[$d->status] ?? 'Unknown' }}
                            </span>

                            @if($d->status == 4 && $d->catatan_revisi)
                            <div class="mt-2 bg-red-50 border border-red-200 rounded-lg p-2 text-xs text-red-700">
                                <strong>Catatan:</strong> {{ $d->catatan_revisi }}
                            </div>
                            @endif
                            @if($d->status == 5)
                            <div class="text-xs text-green-500 mt-1">
                                <i class="fas fa-check"></i> {{ $d->updated_at->format('d M Y') }}
                            </div>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1.5 flex-wrap">

                                {{-- Detail Surat --}}
                                @if($d->surat)
                                <a href="{{ route('surat-masuk.show', $d->surat->id) }}"
                                   class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-200 transition-colors"
                                   title="Lihat Detail Surat">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                @endif

                                {{-- Staf: Terima --}}
                                @if(in_array(auth()->user()->role, ['staf','staff']) && $d->status == 0)
                                <form action="{{ route('disposisi.terima', $d->id) }}" method="POST"
                                      onsubmit="return confirm('Mulai kerjakan tugas ini?')" style="display:inline">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center gap-1 px-2.5 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-hand-holding text-xs"></i> Terima
                                    </button>
                                </form>
                                @endif

                                {{-- Staf: Lapor Selesai --}}
                                @if(in_array(auth()->user()->role, ['staf','staff']) && $d->status == 1)
                                <button onclick="document.getElementById('modalSelesai{{ $d->id }}').classList.remove('hidden')"
                                        class="flex items-center gap-1 px-2.5 py-1.5 bg-green-600 text-white rounded-lg text-xs font-semibold hover:bg-green-700 transition-colors">
                                    <i class="fas fa-check-double text-xs"></i> Lapor
                                </button>
                                @endif

                                {{-- Verifikasi Kasubag (Status 2) --}}
                                @if(in_array(auth()->user()->role, ['kasubag','admin']) && $d->status == 2 && $d->dari_user_id === auth()->id())
                                <button onclick="document.getElementById('modalVerifikasi{{ $d->id }}').classList.remove('hidden')"
                                        class="flex items-center gap-1 px-2.5 py-1.5 bg-amber-500 text-white rounded-lg text-xs font-semibold hover:bg-amber-600 transition-colors">
                                    <i class="fas fa-clipboard-check text-xs"></i> Verif
                                </button>
                                @endif

                                {{-- Verifikasi Kabag (Status 3) --}}
                                {{-- Kabag bisa verifikasi semua disposisi berstatus 3 (multi-hop: kasubag→staf) --}}
                                {{-- Juga bisa verif status 2 jika kabag yang mengirim langsung ke staf --}}
                                @if(in_array(auth()->user()->role, ['kabag','admin']) && ($d->status == 3 || ($d->status == 2 && $d->dari_user_id === auth()->id())))
                                <button onclick="document.getElementById('modalVerifikasiKabag{{ $d->id }}').classList.remove('hidden')"
                                        class="flex items-center gap-1 px-2.5 py-1.5 bg-red-600 text-white rounded-lg text-xs font-semibold hover:bg-red-700 transition-colors">
                                    <i class="fas fa-shield-alt text-xs"></i> Verif
                                </button>
                                @endif

                                {{-- Disposisi Lanjutan --}}
                                @if(in_array(auth()->user()->role, ['admin','kabag','kasubag']) && $d->status < 2 && $d->surat)
                                <button onclick="document.getElementById('modalDisposisiInbox{{ $d->surat->id }}').classList.remove('hidden')"
                                        class="w-8 h-8 bg-slate-100 text-slate-600 rounded-lg flex items-center justify-center hover:bg-slate-200 transition-colors"
                                        title="Disposisi Lanjutan">
                                    <i class="fas fa-paper-plane text-xs"></i>
                                </button>
                                @endif

                                {{-- Hapus --}}
                                @if(in_array(auth()->user()->role, ['admin','kabag','kasubag']))
                                <form action="{{ route('disposisi.destroy', $d->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin hapus disposisi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="w-8 h-8 bg-red-100 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-200 transition-colors"
                                            title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-slate-400">
                            <i class="fas fa-inbox text-4xl block mb-2 opacity-30"></i>
                            Tidak ada tugas / pesan masuk.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODALS (Outside table) --}}
{{-- ============================================================ --}}

@foreach($disposisi_masuk as $d)

    {{-- 1. MODAL STAF: Lapor Selesai --}}
    @if(in_array(auth()->user()->role, ['staf','staff']) && $d->status == 1)
    <div id="modalSelesai{{ $d->id }}"
         class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
         onclick="if(event.target===this)this.classList.add('hidden')">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg" onclick="event.stopPropagation()">
            <form action="{{ route('disposisi.selesai', $d->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex items-center justify-between p-5 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-check-double text-green-600"></i> Lapor Pekerjaan Selesai
                    </h3>
                    <button type="button" onclick="document.getElementById('modalSelesai{{ $d->id }}').classList.add('hidden')"
                            class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
                </div>
                <div class="p-5 space-y-4">

                    {{-- Upload File dengan Live Preview --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            File Hasil Kerja
                            <span class="text-slate-400 font-normal">(PDF / Word / Gambar &mdash; maks 5MB)</span>
                        </label>

                        {{-- Drop zone --}}
                        <label for="fileInput{{ $d->id }}"
                               id="dropZone{{ $d->id }}"
                               class="flex flex-col items-center justify-center gap-2 w-full border-2 border-dashed border-slate-300 rounded-xl p-5 cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-colors group">
                            <i class="fas fa-cloud-upload-alt text-3xl text-slate-300 group-hover:text-blue-400 transition-colors" id="dropIcon{{ $d->id }}"></i>
                            <span class="text-sm text-slate-400 group-hover:text-blue-500" id="dropText{{ $d->id }}">Klik atau seret file ke sini</span>
                            <span class="text-xs text-slate-300">.pdf &nbsp;.doc &nbsp;.docx &nbsp;.jpg &nbsp;.png</span>
                            <input type="file" id="fileInput{{ $d->id }}" name="file_hasil"
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                   class="hidden"
                                   onchange="handleFilePreview(this, '{{ $d->id }}')"
                            >
                        </label>

                        {{-- Live Preview Area --}}
                        <div id="previewArea{{ $d->id }}" class="hidden mt-3">
                            {{-- Preview Gambar --}}
                            <div id="previewImg{{ $d->id }}" class="hidden">
                                <img id="previewImgEl{{ $d->id }}" src="" alt="Preview"
                                     class="rounded-xl max-h-48 mx-auto border border-slate-200 shadow-sm">
                            </div>
                            {{-- Preview PDF --}}
                            <div id="previewPdf{{ $d->id }}" class="hidden">
                                <iframe id="previewPdfEl{{ $d->id }}" src="" class="w-full h-48 rounded-xl border border-slate-200"></iframe>
                            </div>
                            {{-- Preview Word / File lain --}}
                            <div id="previewDoc{{ $d->id }}" class="hidden">
                                <div class="flex items-center gap-3 bg-blue-50 border border-blue-200 rounded-xl px-4 py-3">
                                    <i class="fas fa-file-word text-2xl text-blue-600"></i>
                                    <div>
                                        <p class="text-sm font-semibold text-blue-800" id="previewDocName{{ $d->id }}"></p>
                                        <p class="text-xs text-blue-500" id="previewDocSize{{ $d->id }}"></p>
                                    </div>
                                    <button type="button" onclick="clearFile('{{ $d->id }}')"
                                            class="ml-auto text-slate-400 hover:text-red-500 transition-colors" title="Hapus">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            {{-- Info hapus file --}}
                            <div id="previewOtherActions{{ $d->id }}" class="hidden mt-2 text-right">
                                <button type="button" onclick="clearFile('{{ $d->id }}')"
                                        class="text-xs text-red-500 hover:underline">
                                    <i class="fas fa-trash-alt"></i> Hapus pilihan
                                </button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Catatan Pengerjaan <span class="text-red-500">*</span></label>
                        <textarea name="catatan_staff" rows="3" required
                                  placeholder="Contoh: Surat balasan sudah saya print dan taruh di meja bapak."
                                  class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none resize-none"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 px-5 pb-5 border-t border-slate-100 pt-4">
                    <button type="button" onclick="document.getElementById('modalSelesai{{ $d->id }}').classList.add('hidden')"
                            class="px-4 py-2 text-sm border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-5 py-2 text-sm bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i> Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- 2. MODAL VERIFIKASI KASUBAG (Status 2) --}}
    @if(in_array(auth()->user()->role, ['admin','kasubag','kabag']) && $d->status == 2)
    <div id="modalVerifikasi{{ $d->id }}"
         class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
         onclick="if(event.target===this)this.classList.add('hidden')">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between p-5 border-b border-amber-200 bg-amber-50 rounded-t-2xl">
                <h3 class="font-bold text-amber-800 flex items-center gap-2">
                    <i class="fas fa-clipboard-check"></i> Verifikasi Hasil Pekerjaan
                </h3>
                <button type="button" onclick="document.getElementById('modalVerifikasi{{ $d->id }}').classList.add('hidden')"
                        class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="p-5 space-y-4">
                <div class="bg-slate-50 rounded-xl p-4 grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase mb-0.5">Dari Staff</p>
                        <p class="font-semibold text-slate-800">{{ $d->penerima->name ?? 'Bawahan' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase mb-0.5">Waktu</p>
                        <p class="text-slate-600">{{ $d->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs text-slate-400 font-semibold uppercase mb-0.5">Catatan Staff</p>
                        <p class="italic text-blue-700">"{{ $d->catatan_staff ?? '-' }}"</p>
                    </div>
                </div>

                @if($d->file_hasil)
                @php $ext = pathinfo($d->file_hasil, PATHINFO_EXTENSION); $fileUrl = asset('storage/'.$d->file_hasil); @endphp
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <div class="flex items-center justify-between px-4 py-2.5 bg-slate-50 border-b border-slate-200">
                        <span class="text-xs font-bold text-slate-600 uppercase">Preview File ({{ $ext }})</span>
                        <a href="{{ $fileUrl }}" target="_blank" download class="text-xs text-blue-600 hover:underline flex items-center gap-1">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                    @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                    <img src="{{ $fileUrl }}" class="max-h-64 mx-auto block">
                    @elseif(strtolower($ext) == 'pdf')
                    <iframe src="{{ $fileUrl }}" class="w-full h-64 border-0"></iframe>
                    @else
                    <div class="py-8 text-center text-slate-400 text-sm">Preview tidak tersedia</div>
                    @endif
                </div>
                @else
                <div class="bg-amber-50 border border-amber-200 text-amber-700 rounded-xl p-3 text-sm text-center">
                    Staff tidak melampirkan file.
                </div>
                @endif

                <form action="{{ route('disposisi.verifikasi', $d->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Catatan (jika perlu revisi)</label>
                        <input type="text" name="catatan_revisi" placeholder="Tulis catatan..."
                               class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="submit" name="status_akhir" value="Revisi"
                                class="py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 flex items-center justify-center gap-2">
                            <i class="fas fa-undo"></i> Minta Revisi
                        </button>
                        <button type="submit" name="status_akhir" value="Selesai"
                                onclick="return confirm('ACC pekerjaan ini?')"
                                class="py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 flex items-center justify-center gap-2">
                            <i class="fas fa-check-double"></i> ACC Selesai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- 2B. MODAL VERIFIKASI KABAG (Status 3) --}}
    {{-- Kabag bisa verifikasi semua disposisi berstatus 3, tidak hanya yang dia kirim sendiri --}}
    @if(in_array(auth()->user()->role, ['kabag','admin']) && $d->status == 3 && $d->surat)
    <div id="modalVerifikasiKabag{{ $d->id }}"
         class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
         onclick="if(event.target===this)this.classList.add('hidden')">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between p-5 border-b border-red-200 bg-red-50 rounded-t-2xl">
                <h3 class="font-bold text-red-800 flex items-center gap-2">
                    <i class="fas fa-shield-alt"></i> Verifikasi Akhir Kabag
                </h3>
                <button type="button" onclick="document.getElementById('modalVerifikasiKabag{{ $d->id }}').classList.add('hidden')"
                        class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="p-5 space-y-4">
                <div class="bg-slate-50 rounded-xl p-4 text-sm">
                    <p class="text-xs text-slate-400 font-semibold uppercase mb-1">Dari Kasubag</p>
                    <p class="font-semibold text-slate-800">{{ $d->penerima->name ?? 'Kasubag' }}</p>
                    @if($d->catatan_staff)
                    <p class="mt-2 italic text-blue-700">"{{ $d->catatan_staff }}"</p>
                    @endif
                </div>

                @if($d->file_hasil)
                @php $ext2 = pathinfo($d->file_hasil, PATHINFO_EXTENSION); $fileUrl2 = asset('storage/'.$d->file_hasil); @endphp
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <div class="flex items-center justify-between px-4 py-2.5 bg-slate-50 border-b border-slate-200">
                        <span class="text-xs font-bold text-slate-600 uppercase">File Hasil ({{ $ext2 }})</span>
                        <a href="{{ $fileUrl2 }}" target="_blank" download class="text-xs text-blue-600 hover:underline">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                    @if(in_array(strtolower($ext2), ['jpg','jpeg','png']))
                    <img src="{{ $fileUrl2 }}" class="max-h-64 mx-auto block">
                    @elseif(strtolower($ext2) == 'pdf')
                    <iframe src="{{ $fileUrl2 }}" class="w-full h-64 border-0"></iframe>
                    @else
                    <div class="py-8 text-center text-slate-400 text-sm">Preview tidak tersedia</div>
                    @endif
                </div>
                @endif

                <form action="{{ route('disposisi.verifikasi', $d->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Catatan Akhir Kabag</label>
                        <textarea name="catatan_revisi" rows="3" placeholder="Tulis catatan jika perlu revisi atau ACC..."
                                  class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none resize-none"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="submit" name="status_akhir" value="Revisi"
                                class="py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 flex items-center justify-center gap-2">
                            <i class="fas fa-undo"></i> Minta Revisi
                        </button>
                        <button type="submit" name="status_akhir" value="Selesai"
                                onclick="return confirm('ACC verifikasi akhir ini?')"
                                class="py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 flex items-center justify-center gap-2">
                            <i class="fas fa-check-circle"></i> ACC Selesai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- 3. MODAL DISPOSISI LANJUTAN --}}
    @if(in_array(auth()->user()->role, ['admin','kabag','kasubag']) && $d->status < 2 && $d->surat)
    <div id="modalDisposisiInbox{{ $d->surat->id }}"
         class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
         onclick="if(event.target===this)this.classList.add('hidden')">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg" onclick="event.stopPropagation()">
            <form action="{{ route('disposisi.store') }}" method="POST">
                @csrf
                <input type="hidden" name="surat_masuk_id" value="{{ $d->surat->id }}">
                <div class="flex items-center justify-between p-5 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-paper-plane text-blue-600"></i> Disposisi Lanjutan
                    </h3>
                    <button type="button" onclick="document.getElementById('modalDisposisiInbox{{ $d->surat->id }}').classList.add('hidden')"
                            class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Diteruskan Kepada <span class="text-red-500">*</span></label>
                        <select name="tujuan_user_id" required
                                class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="">-- Pilih Tujuan --</option>
                            @if(isset($listTujuan) && $listTujuan->count())
                                @php
                                    $kasubagList = $listTujuan->whereIn('role', ['kasubag']);
                                    $stafList    = $listTujuan->whereIn('role', ['staf','staff']);
                                @endphp
                                @if($kasubagList->count())
                                <optgroup label="── Kasubag ──">
                                    @foreach($kasubagList as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </optgroup>
                                @endif
                                @if($stafList->count())
                                <optgroup label="── Staf ──">
                                    @foreach($stafList as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </optgroup>
                                @endif
                                @if(!$kasubagList->count() && !$stafList->count())
                                @foreach($listTujuan as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ ucfirst($u->role) }})</option>
                                @endforeach
                                @endif
                            @else
                            <option value="" disabled>Tidak ada bawahan tersedia</option>
                            @endif
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Surat <span class="text-red-500">*</span></label>
                        <input type="text" name="jenis_surat" value="{{ $d->surat->jenis_surat ?? 'Surat' }}" required
                               class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Sifat <span class="text-red-500">*</span></label>
                        <select name="sifat" required
                                class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="Biasa">Biasa</option>
                            <option value="Segera">Segera</option>
                            <option value="Sangat Segera">Sangat Segera</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Instruksi <span class="text-red-500">*</span></label>
                        <textarea name="instruksi" rows="3" required
                                  placeholder="Berikan instruksi untuk penyelesaian surat ini..."
                                  class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 px-5 pb-5 border-t border-slate-100 pt-4">
                    <button type="button" onclick="document.getElementById('modalDisposisiInbox{{ $d->surat->id }}').classList.add('hidden')"
                            class="px-4 py-2 text-sm border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-5 py-2 text-sm bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i> Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

@endforeach
@endsection

@push('scripts')
<script>
function handleFilePreview(input, id) {
    const file = input.files[0];
    if (!file) return;

    const previewArea  = document.getElementById('previewArea'  + id);
    const previewImg   = document.getElementById('previewImg'   + id);
    const previewPdf   = document.getElementById('previewPdf'   + id);
    const previewDoc   = document.getElementById('previewDoc'   + id);
    const previewOther = document.getElementById('previewOtherActions' + id);
    const dropIcon     = document.getElementById('dropIcon'     + id);
    const dropText     = document.getElementById('dropText'     + id);

    // Reset semua preview
    [previewImg, previewPdf, previewDoc, previewOther].forEach(el => el.classList.add('hidden'));
    previewArea.classList.remove('hidden');

    const ext  = file.name.split('.').pop().toLowerCase();
    const url  = URL.createObjectURL(file);
    const size = (file.size / 1024) < 1024
        ? (file.size / 1024).toFixed(1) + ' KB'
        : (file.size / 1024 / 1024).toFixed(2) + ' MB';

    dropIcon.className = 'fas fa-check-circle text-3xl text-green-500';
    dropText.textContent = file.name;
    dropText.className = 'text-sm font-semibold text-green-700 truncate max-w-xs';

    if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
        document.getElementById('previewImgEl' + id).src = url;
        previewImg.classList.remove('hidden');
        previewOther.classList.remove('hidden');
    } else if (ext === 'pdf') {
        document.getElementById('previewPdfEl' + id).src = url;
        previewPdf.classList.remove('hidden');
        previewOther.classList.remove('hidden');
    } else if (['doc','docx'].includes(ext)) {
        document.getElementById('previewDocName' + id).textContent = file.name;
        document.getElementById('previewDocSize' + id).textContent = size;
        previewDoc.classList.remove('hidden');
    }
}

function clearFile(id) {
    const input = document.getElementById('fileInput' + id);
    input.value = '';

    const previewArea  = document.getElementById('previewArea'  + id);
    const previewImg   = document.getElementById('previewImg'   + id);
    const previewPdf   = document.getElementById('previewPdf'   + id);
    const previewDoc   = document.getElementById('previewDoc'   + id);
    const previewOther = document.getElementById('previewOtherActions' + id);
    const dropIcon     = document.getElementById('dropIcon'     + id);
    const dropText     = document.getElementById('dropText'     + id);

    [previewImg, previewPdf, previewDoc, previewOther, previewArea].forEach(el => el.classList.add('hidden'));
    dropIcon.className = 'fas fa-cloud-upload-alt text-3xl text-slate-300 group-hover:text-blue-400 transition-colors';
    dropText.textContent = 'Klik atau seret file ke sini';
    dropText.className = 'text-sm text-slate-400 group-hover:text-blue-500';
}

// Drag & drop support
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[id^="dropZone"]').forEach(zone => {
        const id = zone.id.replace('dropZone','');
        zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('border-blue-400','bg-blue-50'); });
        zone.addEventListener('dragleave', () => zone.classList.remove('border-blue-400','bg-blue-50'));
        zone.addEventListener('drop', e => {
            e.preventDefault();
            zone.classList.remove('border-blue-400','bg-blue-50');
            const input = document.getElementById('fileInput' + id);
            if (e.dataTransfer.files.length) {
                const dt = new DataTransfer();
                dt.items.add(e.dataTransfer.files[0]);
                input.files = dt.files;
                handleFilePreview(input, id);
            }
        });
    });
});
</script>
@endpush
