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
        Schema::table('users', function (Blueprint $table) {
        $table->enum('role', ['user', 'admin'])->default('user');  // Menambahkan kolom 'role'
        $table->string('kontak')->nullable();  // Menambahkan kolom 'kontak'
        $table->string('alamat')->nullable();  // Menambahkan kolom 'alamat'
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
        $table->enum('role', ['user', 'admin'])->default('user');  // Menambahkan kolom 'role'
        $table->string('kontak')->nullable();  // Menambahkan kolom 'kontak'
        $table->string('alamat')->nullable();  // Menambahkan kolom 'alamat'
    });
    }
};
