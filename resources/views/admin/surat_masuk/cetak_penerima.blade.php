<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Terima Surat - {{ $item->no_agenda }}</title>
    <style>
        /* SETTING HALAMAN */
        @page { size: A4; margin: 2cm; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            margin: 0;
            padding: 0;
            color: #000;
        }

        /* CONTAINER UTAMA */
        .container {
            width: 100%;
            max-width: 100%;
        }

        /* KOP SURAT */
        table.kop-surat {
            width: 100%;
            border-bottom: 5px double #000; /* Garis ganda tebal */
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .kop-logo {
            width: 100px;
            text-align: center;
            vertical-align: middle;
        }
        .kop-text {
            text-align: center;
            vertical-align: middle;
            padding-right: 20px;
        }
        .kop-text h3 { font-size: 14pt; font-weight: normal; margin: 0; text-transform: uppercase; letter-spacing: 1px; }
        .kop-text h2 { font-size: 18pt; font-weight: bold; margin: 5px 0; text-transform: uppercase; }
        .kop-text p { font-size: 10pt; margin: 0; line-height: 1.3; }
        .kop-text .kontak { font-size: 9pt; font-style: italic; }

        /* JUDUL HALAMAN */
        .judul {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            font-size: 14pt;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        /* TABEL DATA */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data tr td {
            padding: 8px 5px;
            vertical-align: top;
            font-size: 12pt;
        }
        table.data td:first-child {
            width: 25%;
            font-weight: bold;
        }
        table.data td:nth-child(2) {
            width: 2%;
            text-align: center;
        }

        /* --- PERBAIKAN AREA TANDA TANGAN --- */
        .ttd-wrapper {
            display: flex;
            justify-content: space-between; /* Memisahkan kiri dan kanan */
            margin-top: 50px;
            padding: 0 20px; /* Memberi jarak dari pinggir kertas */
        }
        .ttd-box {
            width: 40%; /* Lebar box yang seimbang */
            text-align: center; /* Semua teks di dalamnya rata tengah */
            display: flex;
            flex-direction: column; /* Menumpuk elemen secara vertikal */
        }
        /* Elemen paragraf di dalam box tanda tangan */
        .ttd-box p {
            margin: 5px 0; /* Jarak antar baris teks */
        }
        /* Ruang kosong untuk tanda tangan */
        .ttd-space {
            height: 80px; /* Tinggi ruang tanda tangan yang pasti */
            width: 100%;
        }
        /* Nama terang */
        .nama-terang {
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin-top: auto; /* Memastikan nama selalu di bagian bawah box */
        }
        /* ------------------------------------ */

        /* CATATAN KAKI */
        .note {
            font-size: 9pt;
            font-style: italic;
            color: #444;
            margin-top: 60px;
            border-top: 1px dashed #999;
            padding-top: 10px;
            text-align: justify;
        }

        /* TOMBOL PRINT */
        @media print { .no-print { display: none !important; } }
        .btn-print {
            position: fixed; top: 20px; right: 20px; background: #198754; color: white;
            padding: 12px 24px; border: none; cursor: pointer; border-radius: 5px;
            font-weight: bold; box-shadow: 0 2px 5px rgba(0,0,0,0.3); z-index: 9999;
        }
        .btn-print:hover { background: #157347; }
    </style>
</head>
<body>

    <button onclick="window.print()" class="no-print btn-print">🖨️ Cetak Bukti</button>

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
                        <p class="kontak">Telepon/Faksimile (0341) 392024 | Laman: jdih.malangkab.go.id</p>
                        <p class="kontak">Pos-el: sekda@malangkab.go.id | Kode Pos: 65163</p>
                    </td>
                </tr>
            </table>
        </header>

        <div class="judul">TANDA TERIMA SURAT MASUK</div>
        <p style="margin-bottom: 20px;">
            Telah diterima dokumen/surat masuk ke dalam sistem <strong>SimPH (Sistem Informasi Manajemen Produk Hukum)</strong> dengan rincian sebagai berikut:
        </p>
        <table class="data">
            <tr>
                <td>Nomor Agenda</td>
                <td>:</td>
                <td style="font-size: 14pt;"><strong>{{ $item->no_agenda }}</strong></td>
            </tr>
            <tr>
                <td>Tanggal Terima</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('l, d F Y - H:i') }} WIB</td>
            </tr>
            <tr><td colspan="3"><hr style="border: 0; border-top: 1px solid #ccc;"></td></tr>
            <tr>
                <td>Asal Surat</td>
                <td>:</td>
                <td>{{ $item->asal_instansi ?? '-' }}
                </td>
            </tr>
            <tr>
                <td>Nomor Surat</td>
                <td>:</td>
                <td>{{ $item->no_surat_pengirim }}</td>
            </tr>
            <tr>
                <td>Tanggal Surat</td>
                <td>:</td>
                <td>
                    @if(!empty($item->tgl_surat))
                        {{ \Carbon\Carbon::parse($item->tgl_surat)->translatedFormat('d F Y') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td style="text-align: justify;">{{ $item->perihal }}</td>
            </tr>
        </table>

        <div class="ttd-wrapper">
            <div class="ttd-box">
                <p>&nbsp;</p> <p>Admin Bagian Hukum,</p>
                <div class="ttd-space"></div> <p class="nama-terang">( .................................... ) </p>
            </div>

            <div class="ttd-box">
                <p>Kepanjen, {{ date('d-m-Y') }}</p> <p>Penerima,</p>
                <div class="ttd-space">/div> <p class="nama-terang">( .................................... )</p>
            </div>
        </div>

        <div class="note">
            <strong>Catatan:</strong><br>
            1. Dokumen ini dicetak secara otomatis oleh sistem sebagai bukti sah penerimaan surat.<br>
            2. Simpan tanda terima ini untuk keperluan pengecekan status surat di kemudian hari.
        </div>
    </div>

</body>
</html>