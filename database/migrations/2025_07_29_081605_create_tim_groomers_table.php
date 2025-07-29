<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimGroomersTable extends Migration
{
    public function up()
    {
        Schema::create('tim_groomers', function (Blueprint $table) {
            $table->id('id_tim');
            $table->unsignedBigInteger('anggota_1');
            $table->unsignedBigInteger('anggota_2');
            $table->timestamps();

            // Foreign key ke tabel groomers
            $table->foreign('anggota_1')->references('id_groomer')->on('groomers')->onDelete('cascade');
            $table->foreign('anggota_2')->references('id_groomer')->on('groomers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tim_groomers');
    }
}