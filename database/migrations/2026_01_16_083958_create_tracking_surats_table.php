<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_surats', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('surat_masuk_id')->constrained('surat_masuks')->onDelete('cascade');
            
            $table->string('status_log');
            $table->dateTime('tgl_status');
            
            $table->foreignId('user_id')->constrained('users');
            $table->text('catatan')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_surats');
    }
};