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
            //$table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            // kucing_id sudah kita hapus di migrasi lain
            //$table->foreignId('layanan_id')->constrained('layanans')->onDelete('cascade');
            //$table->date('tanggalBooking');
            //$table->string('statusBooking')->default('Pending');
            $table->integer('estimasi')->nullable(); // Dalam menit, nullable() karena dihitung sistem
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            //
        });
    }
};
