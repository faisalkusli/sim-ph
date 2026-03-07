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
        Schema::table('disposisis', function (Blueprint $table) {
    if (!Schema::hasColumn('disposisis', 'sifat')) {
        $table->string('sifat')->nullable();
    }
    if (!Schema::hasColumn('disposisis', 'jenis_surat')) {
        $table->string('jenis_surat')->nullable();
    }
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disposisis', function (Blueprint $table) {
            //
        });
    }
};
