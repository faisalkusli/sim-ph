<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('pengambilan_produk_hukums', function (Blueprint $table) {
        $table->id();
        // 1. INTEGRASI DENGAN SURAT MASUK
        $table->unsignedBigInteger('surat_masuk_id');
        // 2. DATA PENGAMBILAN
        $table->date('tanggal_pengambilan');
        $table->string('instansi_pengambil'); // Instansi/lembaga
        $table->string('nama_pengambil'); // Nama orang yang mengambil
        $table->string('no_hp_pengambil')->nullable(); // Nomor telepon orang yang mengambil
        $table->string('nomor_register');
        // 3. FILE PRODUK HUKUM (PDF/DOC)
        $table->string('file_produk')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengambilan_produk_hukums');
    }
};
