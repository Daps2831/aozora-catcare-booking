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
        Schema::table('kucings', function (Blueprint $table) {
            // Tambahkan kolom-kolom ini
            $table->string('nama_kucing');
            $table->string('jenis');
            $table->integer('umur');
            $table->text('riwayat_kesehatan')->nullable();
            $table->string('gambar')->nullable(); // Opsional untuk foto kucing
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kucings', function (Blueprint $table) {
            // Ini untuk proses rollback jika diperlukan
            $table->dropColumn(['nama_kucing', 'jenis', 'umur', 'riwayat_kesehatan', 'gambar']);
        });
    }
};