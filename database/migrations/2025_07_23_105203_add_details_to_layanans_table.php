<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            // Hapus kolom lama yang tidak terstruktur
            if (Schema::hasColumn('layanans', 'layanan')) {
                $table->dropColumn('layanan');
            }

            // Tambahkan kolom-kolom baru yang lebih terstruktur
            $table->string('nama_layanan'); // Untuk nama seperti "Grooming Sehat"
            $table->decimal('harga', 10, 2); // Untuk harga, misal 150000.00
            $table->integer('estimasi_pengerjaan_per_kucing'); // Dalam menit, misal 60
        });
    }

    /**
     * Kembalikan migrasi.
     */
    public function down(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            // Buat kembali kolom lama
            $table->string('layanan');

            // Hapus kolom-kolom baru
            $table->dropColumn(['nama_layanan', 'harga', 'estimasi_pengerjaan_per_kucing']);
        });
    }
};  