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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('username')->unique(); 
            $table->string('email')->unique();
            $table->string('kontak')->nullable();  // Kolom kontak
            $table->string('alamat')->nullable();  // Kolom alamat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) { 
            // Buat rollback ygy
            $table->dropColumn('kontak');
            $table->dropColumn('alamat');
            $table->dropColumn('username'); 
            $table->dropColumn('email');
        });
    }
};
