<!DOCTYPE html>
<html>
<head>
    <title>Lembar Disposisi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }

        .kop-surat { width: 100%; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop-logo { width: 80px; text-align: center; }
        .kop-text { text-align: center; }
        .kop-text h3 { margin: 0; font-size: 14pt; font-weight: normal; text-transform: uppercase; }
        .kop-text h2 { margin: 0; font-size: 16pt; font-weight: bold; text-transform: uppercase; }
        .kop-text p { margin: 0; font-size: 10pt; }

        .table-info { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table-info td { padding: 5px; vertical-align: top; }
        .label { font-weight: bold; width: 130px; }

        .box-disposisi { border: 1px solid #000; padding: 10px; min-height: 200px; }
        .disposisi-item { margin-bottom: 15px; border-bottom: 1px dashed #ccc; padding-bottom: 5px; }

        .signature-container { width: 100%; margin-top: 50px; }
        .signature-box { float: right; width: 250px; text-align: center; }
        
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
    </style>
</head>
<body>

    <table class="kop-surat">
        <tr>
            <td class="kop-logo">
                <img src="{{ public_path('assets/img/logo_kab_malang.png') }}" width="70" alt="Logo">
            </td>
            <td class="kop-text">
                <h3>PEMERINTAH KABUPATEN MALANG</h3>
                <h2>SEKRETARIAT DAERAH</h2>
                <p>Jalan Raden Panji Nomor 158 Kepanjen, Kabupaten Malang, Jawa Timur</p>
                <p>Telepon/Faksimile (0341) 392024 Laman: jdih.malangkab.go.id</p>
                <p>Pos-el: sekda@malangkab.go.id, Kode Pos: 65163</p>
            </td>
        </tr>
    </table>

    <div class="text-center">
        <h3 style="text-decoration: underline;">LEMBAR DISPOSISI</h3>
    </div>

    <table class="table-info">
        <tr>
            <td class="label">No. Agenda</td>
            <td>: {{ $surat->no_agenda }}</td>
            <td class="label">Tanggal Diterima</td>
            <td>: {{ \Carbon\Carbon::parse($surat->tgl_diterima)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Asal Surat</td>
            <td>: {{ $surat->asal_instansi }}</td>
            <td class="label">Tanggal Surat</td>
            <td>: {{ \Carbon\Carbon::parse($surat->tgl_surat)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Nomor Surat</td>
            <td>: {{ $surat->no_surat_pengirim }}</td>
            <td class="label">Sifat Surat</td>
            <td>: Penting / Segera / Biasa</td>
        </tr>
        <tr>
            <td class="label">Perihal</td>
            <td colspan="3">: {{ $surat->perihal }}</td>
        </tr>
    </table>

    <div style="border: 1px solid #000; padding: 10px; margin-top: 20px;">
    <h4 style="margin-top: 0; text-decoration: underline;">RIWAYAT DISPOSISI / INSTRUKSI:</h4>

        @if($surat->disposisi->isEmpty())
            <p style="text-align: center; font-style: italic;">Belum ada data disposisi.</p>
        @else
            <table style="width: 100%; border-collapse: collapse;">
                @foreach($surat->disposisi as $index => $d)
                    <tr>
                        <td style="padding: 8px 0; border-bottom: 1px dashed #999; vertical-align: top;">
                            <div style="font-weight: bold; margin-bottom: 4px;">
                                <span style="color: #333;">Tahap {{ $index + 1 }}:</span> 
                                {{ $d->pengirim->name ?? 'Admin' }} 
                                </span>
                                {{ $d->penerima->name ?? '-' }}
                                </span>
                            </div>

                            <div style="font-size: 11px; color: #555; margin-bottom: 4px;">
                                Tanggal: {{ \Carbon\Carbon::parse($d->created_at)->format('d/m/Y H:i') }} WIB
                            </div>

                            <div style="font-style: italic; background-color: #f9f9f9; padding: 5px; border-left: 3px solid #333;">
                                "{{ $d->instruksi ?? '-' }}"
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>

    <div class="signature-container">
        <div class="signature-box">
            <p>Kepanjen, {{ date('d F Y') }}</p>
            <p>Kepala Bagian,</p>
            <br><br><br><br> <p style="text-decoration: underline; font-weight: bold;"> ARRIE HENDRAWAN MAHARDHIEKA, SH. </p>
            <p>NIP. .....................................</p>
        </div>
    </div>

</body>
</html>