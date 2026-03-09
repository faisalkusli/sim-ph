<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->string('file_pengantar_path')->nullable()->after('file_scan_path');
            $table->string('file_pernyataan_path')->nullable()->after('file_pengantar_path');
            $table->string('file_lampiran_path')->nullable()->after('file_pernyataan_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->dropColumn(['file_pengantar_path', 'file_pernyataan_path', 'file_lampiran_path']);
        });
    }
};
