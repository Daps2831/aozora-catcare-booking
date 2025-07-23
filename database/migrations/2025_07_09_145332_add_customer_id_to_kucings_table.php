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
            // Tambahkan baris ini
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kucings', function (Blueprint $table) {
            // Tambahkan baris ini
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
        });
    }
};