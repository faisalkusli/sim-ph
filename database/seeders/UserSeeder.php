<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed test users for workflow testing
     * 
     * Test Accounts:
     * - Admin: hukum@malangkab.go.id | password: admin123
     * - Kabag: kabag@malangkab.go.id | password: kabag123
     * - Kasubag: kasubag@malangkab.go.id | password: kasubag123
     * - Staff 1-3: staff1/2/3@malangkab.go.id | password: staff123
     * - External: external@gmail.com | password: user123
     */
    public function run()
    {
        // === ADMIN ===
        if (!User::where('email', 'hukum@malangkab.go.id')->exists()) {
            User::create([
                'name' => 'Administrator Hukum',
                'email' => 'hukum@malangkab.go.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'no_hp' => '08123456789',
            ]);
        }

        // === KABAG (Kepala Bagian) ===
        if (!User::where('email', 'kabag@malangkab.go.id')->exists()) {
            User::create([
                'name' => 'Dra. Siti Nurjanah, M.Si',
                'email' => 'kabag@malangkab.go.id',
                'password' => Hash::make('kabag123'),
                'role' => 'kabag',
                'no_hp' => '08234567890',
            ]);
        }

        // === KASUBAG (Kepala Sub Bagian) ===
        if (!User::where('email', 'kasubag@malangkab.go.id')->exists()) {
            User::create([
                'name' => 'H. Muhammad Rianto, S.H.',
                'email' => 'kasubag@malangkab.go.id',
                'password' => Hash::make('kasubag123'),
                'role' => 'kasubag',
                'no_hp' => '08345678901',
            ]);
        }

        // === STAFF 1 (Di bawah Kasubag) ===
        if (!User::where('email', 'staff1@malangkab.go.id')->exists()) {
            User::create([
                'name' => 'Rina Wijaya, A.Md',
                'email' => 'staff1@malangkab.go.id',
                'password' => Hash::make('staff123'),
                'role' => 'staf',
                'no_hp' => '08456789012',
            ]);
        }

        // === STAFF 2 ===
        if (!User::where('email', 'staff2@malangkab.go.id')->exists()) {
            User::create([
                'name' => 'Budi Santoso, A.Md',
                'email' => 'staff2@malangkab.go.id',
                'password' => Hash::make('staff123'),
                'role' => 'staf',
                'no_hp' => '08567890123',
            ]);
        }

        // === STAFF 3 ===
        if (!User::where('email', 'staff3@malangkab.go.id')->exists()) {
            User::create([
                'name' => 'Dewi Lestari, A.Md',
                'email' => 'staff3@malangkab.go.id',
                'password' => Hash::make('staff123'),
                'role' => 'staf',
                'no_hp' => '08678901234',
            ]);
        }

        // === EXTERNAL USER / TAMU (Pihak Eksternal) ===
        if (!User::where('email', 'external@gmail.com')->exists()) {
            User::create([
                'name' => 'PT. Konsultan Hukum Sejahtera',
                'email' => 'external@gmail.com',
                'password' => Hash::make('user123'),
                'role' => 'tamu',
                'no_hp' => '08789012345',
            ]);
        }

        // === OPERATOR ===
        if (!User::where('email', 'operator@malangkab.go.id')->exists()) {
            User::create([
                'name' => 'Operator Surat',
                'email' => 'operator@malangkab.go.id',
                'password' => Hash::make('operator123'),
                'role' => 'operator',
                'no_hp' => '08880001111',
            ]);
        }

        // === DEMO ACCOUNTS ===
        if (app()->environment('local')) {
            // Testing dengan username yang mudah diingat
            if (!User::where('email', 'demo.admin@test.com')->exists()) {
                User::create([
                    'name' => 'Demo Admin',
                    'email' => 'demo.admin@test.com',
                    'password' => Hash::make('demo'),
                    'role' => 'admin',
                    'no_hp' => '08999888777',
                ]);
            }

            if (!User::where('email', 'demo.staff@test.com')->exists()) {
                User::create([
                    'name' => 'Demo Staff',
                    'email' => 'demo.staff@test.com',
                    'password' => Hash::make('demo'),
                    'role' => 'staf',
                    'no_hp' => '08999888776',
                ]);
            }
        }
    }
}