<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    // migration: alter_anggota_nullable_on_tim_groomers_table.php
    public function up()
    {
        Schema::table('tim_groomers', function (Blueprint $table) {
            $table->unsignedBigInteger('anggota_1')->nullable()->change();
            $table->unsignedBigInteger('anggota_2')->nullable()->change();
        });
    }
    public function down()
    {
        Schema::table('tim_groomers', function (Blueprint $table) {
            $table->unsignedBigInteger('anggota_1')->nullable(false)->change();
            $table->unsignedBigInteger('anggota_2')->nullable(false)->change();
        });
    }
};
