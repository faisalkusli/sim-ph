<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->id();
            
            $table->string('no_surat')->unique(); // No surat yang kita keluarkan
            $table->string('tujuan_surat');       // Kepada siapa/instansi mana
            $table->date('tgl_surat');            // Tanggal surat dibuat
            $table->date('tgl_kirim')->nullable(); // Tanggal dikirim (opsional)
            $table->string('perihal');
            
            $table->string('file_arsip_path')->nullable(); // Scan surat keluar untuk arsip
            
            $table->foreignId('created_by')->constrained('users'); // Siapa yang input
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};