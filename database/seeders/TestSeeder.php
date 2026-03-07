<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SuratMasuk;
use App\Models\Disposisi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        // Create test users with different roles
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'no_hp' => '08123456789',
        ]);

        $kabag = User::create([
            'name' => 'Kabag Test',
            'email' => 'kabag@test.com',
            'password' => Hash::make('password123'),
            'role' => 'kabag',
            'no_hp' => '08123456790',
        ]);

        $kasubag = User::create([
            'name' => 'Kasubag Test',
            'email' => 'kasubag@test.com',
            'password' => Hash::make('password123'),
            'role' => 'kasubag',
            'no_hp' => '08123456791',
        ]);

        $staff = User::create([
            'name' => 'Staff Test',
            'email' => 'staff@test.com',
            'password' => Hash::make('password123'),
            'role' => 'staf',
            'no_hp' => '08123456792',
        ]);

        $guest = User::create([
            'name' => 'Guest Test',
            'email' => 'guest@test.com',
            'password' => Hash::make('password123'),
            'role' => 'tamu',
            'no_hp' => '08123456793',
        ]);
    }
}