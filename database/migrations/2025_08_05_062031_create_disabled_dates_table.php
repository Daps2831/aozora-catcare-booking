<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('disabled_dates', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('keterangan')->nullable();
            $table->timestamps();
            
            $table->unique('tanggal');
        });
    }

    public function down()
    {
        Schema::dropIfExists('disabled_dates');
    }
};