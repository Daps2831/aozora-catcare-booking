<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('booking_kucing', function (Blueprint $table) {
            $table->unsignedBigInteger('layanan_id')->nullable()->after('kucing_id');
            $table->foreign('layanan_id')->references('id')->on('layanans');
        });
    }

    public function down()
    {
        Schema::table('booking_kucing', function (Blueprint $table) {
            $table->dropForeign(['layanan_id']);
            $table->dropColumn('layanan_id');
        });
    }
};