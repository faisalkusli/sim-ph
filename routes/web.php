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

// Protected Routes (Authentication Required)
Route::middleware(['auth'])->group(function () {

    // ===== MASTER PRODUK HUKUM =====
    Route::get('/produk-hukum', [ProdukHukumController::class, 'index'])->name('produk-hukum.index');
    Route::get('/produk-hukum/create', [ProdukHukumController::class, 'create'])->name('produk-hukum.create');
    Route::post('/produk-hukum', [ProdukHukumController::class, 'store'])->name('produk-hukum.store');
    
    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // ===== SURAT MASUK (Incoming Letters) =====
    Route::resource('surat-masuk', SuratMasukController::class);
    
    // Surat Masuk - Workflow Actions
    Route::patch('/surat-masuk/{id}/validasi', [SuratMasukController::class, 'validasiAwal'])
        ->name('surat-masuk.validasi');
    Route::post('/surat-masuk/{id}/verifikasi', [SuratMasukController::class, 'verifikasi'])
        ->name('surat-masuk.verifikasi');
    Route::post('/surat-masuk/{id}/upload-hasil', [SuratMasukController::class, 'uploadHasilKerja'])
        ->name('surat.upload-hasil');
    Route::post('/surat-masuk/{id}/status', [SuratMasukController::class, 'updateStatus'])
        ->name('surat-masuk.status');
    
    // Surat Masuk - Escalation (Naik/Turun Bupati)
    Route::post('/surat-masuk/{id}/naik-bupati', [SuratMasukController::class, 'naikBupati'])
        ->name('surat.naik_bupati');
    Route::post('/surat-masuk/{id}/turun-bupati', [SuratMasukController::class, 'turunBupati'])
        ->name('surat.turun_bupati');
    
    // Surat Masuk - Tracking
    Route::get('/surat-masuk/{id}/tracking', [SuratMasukController::class, 'tracking'])
        ->name('surat-masuk.tracking');
    
    // Surat Masuk - Printing & Reporting
    Route::get('/surat-masuk/{id}/cetak', [SuratMasukController::class, 'cetak'])
        ->name('surat-masuk.cetak');
    Route::get('/surat-masuk/{id}/cetak-disposisi', [SuratMasukController::class, 'cetakDisposisi'])
        ->name('surat.cetak');
    Route::get('/surat-masuk/{id}/monitor', [SuratMasukController::class, 'monitor'])
        ->name('surat-masuk.monitor');
    
    // Surat Masuk - Import/Export
    Route::get('/surat-masuk-export', [SuratMasukController::class, 'export'])
        ->name('surat-masuk.export');
    Route::post('/surat-masuk-import', [SuratMasukController::class, 'import'])
        ->name('surat-masuk.import');
    
    // ===== SURAT KELUAR (Outgoing Letters) =====
    Route::resource('surat-keluar', SuratKeluarController::class);
    
    // ===== DISPOSISI (Task Assignments) =====
    Route::get('/inbox', [DisposisiController::class, 'inbox'])
        ->name('inbox');
    // Monitoring harus didefinisikan sebelum route parameter '/disposisi/{id}'
    Route::get('/disposisi/monitoring', [DisposisiController::class, 'monitoring'])
        ->name('disposisi.monitoring');
    Route::get('/disposisi/{id}', [DisposisiController::class, 'show'])
        ->name('disposisi.show');
    
    // Disposisi - CRUD Operations
    Route::post('/disposisi', [DisposisiController::class, 'store'])
        ->name('disposisi.store');
    Route::delete('/disposisi/{id}', [DisposisiController::class, 'destroy'])
        ->name('disposisi.destroy');
    
    // Disposisi - Status Updates
    Route::post('/disposisi/{id}/terima', [DisposisiController::class, 'terima'])
        ->name('disposisi.terima');
    Route::post('/disposisi/{id}/selesai', [DisposisiController::class, 'selesai'])
        ->name('disposisi.selesai');
    Route::post('/disposisi/{id}/verifikasi', [DisposisiController::class, 'verifikasi'])
        ->name('disposisi.verifikasi');
    Route::post('/disposisi/{id}/selesai', [DisposisiController::class, 'selesai'])
        ->name('disposisi.selesai');
    Route::patch('/disposisi/{id}/reply', [DisposisiController::class, 'reply'])
        ->name('disposisi.reply');
    Route::patch('/disposisi/{id}/complete', [DisposisiController::class, 'complete'])
        ->name('disposisi.complete');
    
    // ===== PENGAMBILAN PRODUK HUKUM (Legal Product Pickup) =====
    Route::resource('pengambilan', PengambilanProdukController::class);
    Route::get('/pengambilan/{id}/cetak', [PengambilanProdukController::class, 'cetak'])
        ->name('pengambilan.cetak');
    
    // ===== LAPORAN (Reports) =====
    Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');
    Route::post('/laporan/cetak', [LaporanController::class, 'cetak'])
        ->name('laporan.cetak');
    
    // ===== SEARCH (Dihapus) =====
    // Route pencarian dihapus
    
    // ===== SYSTEM - Backup & Restore =====
    Route::get('/system/backup', [BackupController::class, 'index'])
        ->name('backup.index');
    Route::get('/system/backup/process', [BackupController::class, 'backup'])
        ->name('backup.process');
    Route::post('/system/backup/restore', [BackupController::class, 'restore'])
        ->name('backup.restore');
    
    // ===== USER MANAGEMENT (Admin Only) =====
    Route::resource('users', UserController::class);
});