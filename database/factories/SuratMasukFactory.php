<?php

namespace Database\Factories;

use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SuratMasukFactory extends Factory
{
    protected $model = SuratMasuk::class;

    public function definition(): array
    {
        $tahun = date('Y');
        static $counter = 0;
        $counter++;

        return [
            'user_id' => User::factory(),
            'no_agenda' => $counter . '/HK/' . $tahun,
            'no_surat_pengirim' => $this->faker->numerify('###/###'),
            'asal_instansi' => $this->faker->company(),
            'jenis_surat' => $this->faker->randomElement(['Undangan', 'Laporan', 'Surat Keputusan', 'Peraturan']),
            'perihal' => $this->faker->sentence(4),
            'tgl_surat' => $this->faker->dateTime(),
            'tgl_diterima' => now(),
            'file_scan_path' => null,
            'file_draft_path' => null,
            'status' => 'Menunggu Validasi',
            'alasan_tolak' => null,
            'catatan_verifikasi' => null,
            'catatan_revisi' => null,
            'catatan_staff' => null,
            'no_npknd' => null,
            'tgl_naik_bupati' => null,
            'tgl_turun_bupati' => null,
        ];
    }

    public function validated(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Siap Disposisi',
            'catatan_verifikasi' => 'Validated by system',
        ]);
    }

    public function rejected(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Ditolak',
            'alasan_tolak' => 'Data tidak lengkap',
        ]);
    }

    public function processed(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Sedang Diproses',
        ]);
    }

    public function completed(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Selesai',
        ]);
    }

    public function escalated(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Naik ke Bupati',
            'no_npknd' => $this->faker->numerify('###'),
            'tgl_naik_bupati' => now(),
        ]);
    }
}