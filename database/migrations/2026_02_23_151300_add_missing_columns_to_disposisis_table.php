<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disposisis', function (Blueprint $table) {
            if (!Schema::hasColumn('disposisis', 'file_laporan')) {
                $table->string('file_laporan')->nullable();
            }
            if (!Schema::hasColumn('disposisis', 'catatan_revisi')) {
                $table->text('catatan_revisi')->nullable();
            }
            if (!Schema::hasColumn('disposisis', 'sifat')) {
                $table->string('sifat')->nullable();
            }
            if (!Schema::hasColumn('disposisis', 'jenis_surat')) {
                $table->string('jenis_surat')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('disposisis', function (Blueprint $table) {
            $table->dropColumn(['file_laporan', 'catatan_revisi', 'sifat', 'jenis_surat']);
        });
    }
};