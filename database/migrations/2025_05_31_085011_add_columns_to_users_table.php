<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['user', 'admin'])->default('user');  // Kolom role
            $table->string('kontak')->nullable();  // Kolom kontak
            $table->string('alamat')->nullable();  // Kolom alamat
            $table->rememberToken();
            $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
        Schema::dropIfExists('users');  // Jika rollback, hapus tabel `users`
}
};
