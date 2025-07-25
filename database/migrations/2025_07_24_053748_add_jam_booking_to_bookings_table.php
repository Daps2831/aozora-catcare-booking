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
        Schema::table('booking_kucing', function (Blueprint $table) {
            $table->unsignedBigInteger('layanan_id')->nullable()->after('kucing_id');
        });
    }
    public function down()
    {
        Schema::table('booking_kucing', function (Blueprint $table) {
            $table->dropColumn('layanan_id');
        });
    }
};
