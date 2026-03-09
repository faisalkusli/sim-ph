<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    SuratMasukController,
    SuratKeluarController,
    DisposisiController,
    BackupController,
    PengambilanProdukController,
    LaporanController,
    UserController
    , ProdukHukumController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Sistem Persuratan Bagian Hukum Setda
| Document Management System with Workflow
|
*/

// Public Routes
Route::get('/', function () {
    return Auth::check() ? redirect()->route('home') : redirect()->route('login');
});

Auth::routes(['register' => false]);

// =============================================================================
// KELOMPOK ROUTE BERDASARKAN ROLE
// Roles: admin, operator, kabag, kasubag, staf, tamu
// =============================================================================

// -------------------------------------------------------------------------
// GROUP 1 - Semua pengguna terautentikasi (termasuk tamu)
// Dashboard + Surat Masuk dasar
// -------------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // ===== SURAT MASUK - Akses dasar =====
    Route::get('/surat-masuk', [SuratMasukController::class, 'index'])->name('surat-masuk.index');
    Route::get('/surat-masuk/create', [SuratMasukController::class, 'create'])->name('surat-masuk.create');
    Route::post('/surat-masuk', [SuratMasukController::class, 'store'])->name('surat-masuk.store');
    Route::get('/surat-masuk/{id}', [SuratMasukController::class, 'show'])->name('surat-masuk.show');
    Route::get('/surat-masuk/{id}/tracking', [SuratMasukController::class, 'tracking'])->name('surat-masuk.tracking');
});

// -------------------------------------------------------------------------
// GROUP 2 - Admin, Operator, Kabag, Kasubag, Staf (bukan Tamu)
// Inbox + Disposisi + Workflow Surat Masuk
// -------------------------------------------------------------------------
Route::middleware(['auth', 'role:admin,operator,kabag,kasubag,staf'])->group(function () {

    // ===== INBOX =====
    Route::get('/inbox', [DisposisiController::class, 'inbox'])->name('inbox');

    // ===== DISPOSISI =====
    Route::get('/disposisi/monitoring', [DisposisiController::class, 'monitoring'])->name('disposisi.monitoring');
    Route::get('/disposisi/{id}', [DisposisiController::class, 'show'])->name('disposisi.show');
    Route::post('/disposisi', [DisposisiController::class, 'store'])->name('disposisi.store');
    Route::delete('/disposisi/{id}', [DisposisiController::class, 'destroy'])->name('disposisi.destroy');
    Route::post('/disposisi/{id}/terima', [DisposisiController::class, 'terima'])->name('disposisi.terima');
    Route::post('/disposisi/{id}/selesai', [DisposisiController::class, 'selesai'])->name('disposisi.selesai');
    Route::post('/disposisi/{id}/verifikasi', [DisposisiController::class, 'verifikasi'])->name('disposisi.verifikasi');
    Route::patch('/disposisi/{id}/reply', [DisposisiController::class, 'reply'])->name('disposisi.reply');
    Route::patch('/disposisi/{id}/complete', [DisposisiController::class, 'complete'])->name('disposisi.complete');

    // ===== SURAT MASUK - Workflow & Cetak =====
    Route::patch('/surat-masuk/{id}/validasi', [SuratMasukController::class, 'validasiAwal'])->name('surat-masuk.validasi');
    Route::post('/surat-masuk/{id}/verifikasi', [SuratMasukController::class, 'verifikasi'])->name('surat-masuk.verifikasi');
    Route::post('/surat-masuk/{id}/upload-hasil', [SuratMasukController::class, 'uploadHasilKerja'])->name('surat.upload-hasil');
    Route::post('/surat-masuk/{id}/status', [SuratMasukController::class, 'updateStatus'])->name('surat-masuk.status');
    Route::post('/surat-masuk/{id}/naik-bupati', [SuratMasukController::class, 'naikBupati'])->name('surat.naik_bupati');
    Route::post('/surat-masuk/{id}/turun-bupati', [SuratMasukController::class, 'turunBupati'])->name('surat.turun_bupati');
    Route::get('/surat-masuk/{id}/cetak', [SuratMasukController::class, 'cetak'])->name('surat-masuk.cetak');
    Route::get('/surat-masuk/{id}/cetak-disposisi', [SuratMasukController::class, 'cetakDisposisi'])->name('surat.cetak');
    Route::get('/surat-masuk/{id}/monitor', [SuratMasukController::class, 'monitor'])->name('surat-masuk.monitor');

    // Surat Masuk Update/Delete
    Route::put('/surat-masuk/{id}', [SuratMasukController::class, 'update'])->name('surat-masuk.update');
    Route::delete('/surat-masuk/{id}', [SuratMasukController::class, 'destroy'])->name('surat-masuk.destroy');
    Route::get('/surat-masuk/{id}/edit', [SuratMasukController::class, 'edit'])->name('surat-masuk.edit');
});

// -------------------------------------------------------------------------
// GROUP 3 - Admin + Operator saja
// Surat Keluar, Produk Hukum, Pengambilan
// -------------------------------------------------------------------------
Route::middleware(['auth', 'role:admin,operator'])->group(function () {

    // ===== SURAT KELUAR =====
    Route::resource('surat-keluar', SuratKeluarController::class);

    // ===== MASTER PRODUK HUKUM =====
    Route::get('/produk-hukum', [ProdukHukumController::class, 'index'])->name('produk-hukum.index');
    Route::get('/produk-hukum/create', [ProdukHukumController::class, 'create'])->name('produk-hukum.create');
    Route::post('/produk-hukum', [ProdukHukumController::class, 'store'])->name('produk-hukum.store');

    // ===== PENGAMBILAN PRODUK HUKUM =====
    Route::resource('pengambilan', PengambilanProdukController::class);
    Route::get('/pengambilan/{id}/cetak', [PengambilanProdukController::class, 'cetak'])->name('pengambilan.cetak');
});

// -------------------------------------------------------------------------
// GROUP 4 - Admin saja
// Manajemen User, Laporan, Backup, Import/Export
// -------------------------------------------------------------------------
Route::middleware(['auth', 'role:admin'])->group(function () {

    // ===== USER MANAGEMENT =====
    Route::resource('users', UserController::class);

    // ===== LAPORAN =====
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');

    // ===== SYSTEM - Backup & Restore =====
    Route::get('/system/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::get('/system/backup/process', [BackupController::class, 'backup'])->name('backup.process');
    Route::post('/system/backup/restore', [BackupController::class, 'restore'])->name('backup.restore');

    // ===== SURAT MASUK - Import/Export =====
    Route::get('/surat-masuk-export', [SuratMasukController::class, 'export'])->name('surat-masuk.export');
    Route::post('/surat-masuk-import', [SuratMasukController::class, 'import'])->name('surat-masuk.import');
});