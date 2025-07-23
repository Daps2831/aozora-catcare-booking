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
        Schema::table('bookings', function (Blueprint $table) {
            // Tambahkan semua kolom yang diperlukan ini
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('kucing_id')->constrained('kucings')->onDelete('cascade');
            $table->foreignId('layanan_id')->constrained('layanans')->onDelete('cascade');
            $table->date('tanggalBooking');
            $table->string('statusBooking')->default('Pending'); // contoh: Pending, Dikonfirmasi, Selesai
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Ini untuk proses rollback jika diperlukan
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['kucing_id']);
            $table->dropForeign(['layanan_id']);
            $table->dropColumn(['customer_id', 'kucing_id', 'layanan_id', 'tanggalBooking', 'statusBooking']);
        });
    }
};