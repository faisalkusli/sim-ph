<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('no_agenda'); 
            $table->string('no_surat_pengirim');
            $table->string('asal_instansi');
            $table->string('jenis_surat')->nullable();
            $table->text('perihal');
            $table->date('tgl_surat');
            $table->date('tgl_diterima');
            $table->string('file_scan_path')->nullable(); 
            $table->string('file_draft_path')->nullable();
            $table->string('status')->default('Menunggu Validasi'); 
            $table->text('alasan_tolak')->nullable(); 
            $table->text('catatan_verifikasi')->nullable();
            $table->text('catatan_revisi')->nullable();
            $table->text('catatan_staff')->nullable();          
            $table->string('no_npknd')->nullable();
            $table->dateTime('tgl_naik_bupati')->nullable();
            $table->dateTime('tgl_turun_bupati')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};