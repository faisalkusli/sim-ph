<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disposisis', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Surat Masuk
            $table->foreignId('surat_masuk_id')->constrained('surat_masuks')->onDelete('cascade');
            
            // Relasi PENGIRIM (Dari Siapa) -> Sesuai Controller 'dari_user_id'
            $table->foreignId('dari_user_id')->constrained('users')->onDelete('cascade');
            
            // Relasi PENERIMA (Ke Siapa) -> Sesuai Controller 'tujuan_user_id'
            $table->foreignId('tujuan_user_id')->constrained('users')->onDelete('cascade');
            
            // Isi Disposisi -> Sesuai Controller 'instruksi'
            $table->text('instruksi');
            
            // Status & Logatika Staff
            $table->integer('status')->default(0); // 0=Belum, 1=Proses, 2=Selesai
            $table->dateTime('tanggal_diterima')->nullable();
            $table->dateTime('tanggal_selesai')->nullable();
            
            // Laporan Balik dari Staff
            $table->text('catatan_staff')->nullable();
            $table->string('file_hasil')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disposisis');
    }
};