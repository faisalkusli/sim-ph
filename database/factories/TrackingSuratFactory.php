<?php

namespace Database\Factories;

use App\Models\TrackingSurat;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrackingSuratFactory extends Factory
{
    protected $model = TrackingSurat::class;

    public function definition(): array
    {
        return [
            'surat_masuk_id' => SuratMasuk::factory(),
            'status_log' => $this->faker->randomElement(['Baru', 'Validasi', 'Disposisi', 'Proses', 'Selesai']),
            'tgl_status' => now(),
            'user_id' => User::factory(),
            'catatan' => $this->faker->sentence(3),
        ];
    }
}