<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tanda Terima - {{ $item->nomor_register }}</title>
    <style>
        @page { size: A4; margin: 2cm; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; margin: 0; padding: 0; color: #000; }
        .container { width: 100%; max-width: 100%; }
        /* KOP SURAT */
        table.kop-surat { width: 100%; border-bottom: 5px double #000; padding-bottom: 10px; margin-bottom: 30px; }
        .kop-logo { width: 100px; text-align: center; vertical-align: middle; }
        .kop-text { text-align: center; vertical-align: middle; padding-right: 20px; }
        .kop-text h3 { font-size: 14pt; font-weight: normal; margin: 0; text-transform: uppercase; letter-spacing: 1px; }
        .kop-text h2 { font-size: 18pt; font-weight: bold; margin: 5px 0; text-transform: uppercase; }
        .kop-text p { font-size: 10pt; margin: 0; line-height: 1.3; }
        .kop-text .kontak { font-size: 9pt; font-style: italic; }
        .judul-surat { text-align: center; text-transform: uppercase; font-weight: bold; text-decoration: underline; margin-bottom: 30px; font-size: 14pt; }
        .content { margin-bottom: 40px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data td { padding: 8px; vertical-align: top; }
        table.data td:first-child { width: 200px; font-weight: bold; }
        table.data td:nth-child(2) { width: 20px; text-align: center; }
        .signature-section { width: 100%; display: flex; justify-content: space-between; margin-top: 50px; }
        .signature-box { width: 40%; text-align: center; }
        .signature-space { height: 80px; }
        .name { font-weight: bold; text-decoration: underline; }
        @media print { .no-print { display: none !important; } }
        .btn-print { position: fixed; top: 20px; right: 20px; background: #198754; color: white; padding: 12px 24px; border: none; cursor: pointer; border-radius: 5px; font-weight: bold; box-shadow: 0 2px 5px rgba(0,0,0,0.3); z-index: 9999; }
        .btn-print:hover { background: #157347; }
    </style>
</head>
<body>

    <button onclick="window.print()" class="no-print btn-print">🖨️ Cetak Tanda Terima</button>

    <div class="container">
        <header>
            <table class="kop-surat">
                <tr>
                    <td class="kop-logo">
                        <img src="{{ asset('assets/img/logo_kab_malang.png') }}" width="90" alt="Logo">
                    </td>
                    <td class="kop-text">
                        <h3>Pemerintah Kabupaten Malang</h3>
                        <h2>Sekretariat Daerah</h2>
                        <p>Jalan Raden Panji Nomor 158 Kepanjen, Kabupaten Malang, Jawa Timur</p>
                        <p class="kontak">Telepon/Faksimile (0341) 392024 | Laman: malangkab.go.id</p>
                        <p class="kontak">Pos-el: sekda@malangkab.go.id | Kode Pos: 65163</p>
                    </td>
                </tr>
            </table>
        </header>

        <div class="judul-surat">TANDA TERIMA PRODUK HUKUM</div>

        <div class="content">
            <p>Telah terima dari Bagian Hukum Sekretariat Daerah, dokumen Produk Hukum dengan rincian sebagai berikut:</p>
            
            <table class="data">
                <tr>
                    <td>Nomor Register</td>
                    <td>:</td>
                    <td>{{ $item->nomor_register }}</td>
                </tr>
                <tr>
                    <td>Tanggal Pengambilan</td>
                    <td>:</td>
                    <td>{{ date('d-m-Y', strtotime($item->tanggal_pengambilan)) }}</td>
                </tr>
                <tr>
                    <td>Instansi Pengambil</td>
                    <td>:</td>
                    <td>{{ $item->instansi_pengambil }}</td>
                </tr>
                <tr>
                    <td>Nama Penerima</td>
                    <td>:</td>
                    <td>{{ $item->nama_pengambil ?? '-' }}</td>
                </tr>
                <tr>
                    <td>No. HP Penerima</td>
                    <td>:</td>
                    <td>{{ $item->no_hp_pengambil ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Perihal / Judul</td>
                    <td>:</td>
                    <td>{{ $item->surat->perihal ?? 'Data Surat Tidak Ditemukan' }}</td>
                </tr>
                <tr>
                    <td>Nomor Surat/Agenda</td>
                    <td>:</td>
                    <td>{{ $item->surat->no_agenda ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <p>Yang Menyerahkan,</p>
                <div class="signature-space"></div>
                <p class="name">( Admin Bagian Hukum )</p>
            </div>

            <div class="signature-box">
                <p>........., {{ date('d-m-Y') }}</p>
                <p>Yang Menerima,</p>
                <div class="signature-space"></div>
                <p class="name">( {{ $item->nama_pengambil ?? '.........................' }} )</p>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>