<?php

namespace Database\Factories;

use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DisposisiFactory extends Factory
{
    protected $model = Disposisi::class;

    public function definition(): array
    {
        return [
            'surat_masuk_id' => SuratMasuk::factory(),
            'dari_user_id' => User::factory()->kabag(),
            'tujuan_user_id' => User::factory()->staff(),
            'instruksi' => $this->faker->sentence(5),
            'status' => 0,
            'sifat' => $this->faker->randomElement(['Biasa', 'Penting', 'Segera', 'Rahasia']),
            'jenis_surat' => $this->faker->randomElement(['Undangan', 'Laporan', 'Surat Keputusan']),
            'catatan_staff' => null,
            'file_laporan' => null,
            'catatan_revisi' => null,
        ];
    }

    public function received(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => 1, // Sedang Dikerjakan
        ]);
    }

    public function submitted(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => 2, // Menunggu Verifikasi
            'catatan_staff' => 'Pekerjaan selesai',
            'file_laporan' => 'laporan/test.pdf',
        ]);
    }

    public function needsRevision(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => 3, // Perlu Revisi
            'catatan_revisi' => 'Kurang detil, mohon dilengkapi',
        ]);
    }

    public function completed(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => 4, // Selesai Final
        ]);
    }
}