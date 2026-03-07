<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan - {{ $judul }}</title>
    <style>
        @media print {
            @page { size: landscape; margin: 10mm; }
            body { -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            margin: 0;
            padding: 20px;
        }

        table.kop-surat {
            width: 100%;
            border-bottom: 5px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-logo { width: 80px; text-align: center; vertical-align: middle; }
        .kop-text { text-align: center; vertical-align: middle; padding-right: 10px; }
        .kop-text h3 { font-size: 14pt; font-weight: normal; margin: 0; text-transform: uppercase; letter-spacing: 1px; }
        .kop-text h2 { font-size: 18pt; font-weight: bold; margin: 5px 0; text-transform: uppercase; }
        .kop-text p { font-size: 10pt; margin: 0; line-height: 1.3; }
        .judul-laporan { text-align: center; margin-bottom: 20px; }
        .judul-laporan h4 { font-weight: bold; text-decoration: underline; text-transform: uppercase; margin: 0; }
        .judul-laporan p { margin: 5px 0 0 0; font-size: 10pt; }
        table.table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.table-data th, table.table-data td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
            font-size: 10pt;
        }
        table.table-data th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
        }
        .text-center { text-align: center; }
        .ttd-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
            padding-right: 30px;
        }
        .ttd-box { text-align: center; width: 250px; }
        .ttd-space { height: 70px; }
        .nama-terang { font-weight: bold; text-decoration: underline; }
        .btn-print {
            position: fixed; top: 10px; right: 10px;
            background: #198754; color: white; border: none;
            padding: 10px 20px; border-radius: 5px; cursor: pointer;
            font-weight: bold; z-index: 9999;
        }
        .btn-print:hover { background: #157347; }
    </style>
</head>
<body>

    <button onclick="window.print()" class="no-print btn-print">🖨️ Cetak Laporan</button>

    <header>
        <table class="kop-surat">
            <tr>
                <td class="kop-logo">
                    <img src="{{ asset('assets/img/logo_kab_malang.png') }}" width="80" alt="Logo">
                </td>
                <td class="kop-text">
                    <h3>Pemerintah Kabupaten Malang</h3>
                    <h2>Sekretariat Daerah</h2>
                    <p>Jalan Raden Panji Nomor 158 Kepanjen, Kabupaten Malang, Jawa Timur</p>
                    <p>Telepon/Faksimile (0341) 392024 | Laman: malangkab.go.id</p>
                </td>
            </tr>
        </table>
    </header>

    <div class="judul-laporan">
        <h4>{{ $judul }}</h4>
        <p>Periode: {{ \Carbon\Carbon::parse($tgl_awal)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($tgl_akhir)->translatedFormat('d F Y') }}</p>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th width="5%">No</th>
                @if($jenis == 'surat_masuk')
                    <th width="15%">No Agenda</th>
                    <th width="10%">Tanggal Input</th>
                    <th width="20%">Asal Surat</th>
                    <th width="15%">No. Surat</th>
                    <th>Perihal</th>
                    <th width="10%">Status</th> @else
                    <th width="15%">No Surat</th>
                    <th width="10%">Tanggal Surat</th>
                    <th width="20%">Tujuan</th>
                    <th>Perihal</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    
                    @if($jenis == 'surat_masuk')
                        <td>{{ $row->no_agenda }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y') }}</td>
                        
                        <td>{{ $row->asal_surat ?? $row->asal_instansi }}</td>
                        
                        <td>{{ $row->nomor_surat ?? $row->no_surat_pengirim ?? '-' }}</td>
                        
                        <td>{{ $row->perihal }}</td>
                        
                        <td class="text-center">
                            <strong>{{ $row->status ?? '-' }}</strong>
                        </td>
                    @else
                        <td>{{ $row->no_surat }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($row->tanggal_surat ?? $row->tgl_surat)->format('d/m/Y') }}</td>
                        <td>{{ $row->tujuan_surat ?? $row->tujuan }}</td>
                        <td>{{ $row->perihal }}</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $jenis == 'surat_masuk' ? 7 : 4 }}" class="text-center" style="padding: 20px;">
                        <i>Data tidak ditemukan pada periode ini.</i>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="ttd-wrapper">
        <div class="ttd-box">
            <p>Kepanjen, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Mengetahui,<br>Kepala Bagian Hukum</p>
            <div class="ttd-space"></div>
            <p class="nama-terang">_________________________</p>
            <p>NIP. ....................................</p>
        </div>
    </div>

    <script>
    </script>
</body>
</html>