<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Pastikan tidak ada data orphan sebelum menambah foreign key
        // Hapus customers yang tidak memiliki user_id yang valid
        DB::statement('DELETE FROM customers WHERE user_id NOT IN (SELECT id FROM users)');
        
        // Hapus kucings yang tidak memiliki customer_id yang valid
        DB::statement('DELETE FROM kucings WHERE customer_id NOT IN (SELECT id FROM customers)');
        
        // Cek dan tambah foreign key constraint untuk customers table
        Schema::table('customers', function (Blueprint $table) {
            // Cek apakah foreign key sudah ada
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'customers' 
                AND COLUMN_NAME = 'user_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            // Jika belum ada foreign key, baru ditambahkan
            if (empty($foreignKeys)) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });
        
        // Cek dan tambah foreign key constraint untuk kucings table
        Schema::table('kucings', function (Blueprint $table) {
            // Cek apakah foreign key sudah ada
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'kucings' 
                AND COLUMN_NAME = 'customer_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            // Jika belum ada foreign key, baru ditambahkan
            if (empty($foreignKeys)) {
                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            // Cek apakah foreign key ada sebelum dihapus
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'customers' 
                AND COLUMN_NAME = 'user_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            if (!empty($foreignKeys)) {
                $table->dropForeign(['user_id']);
            }
        });
        
        Schema::table('kucings', function (Blueprint $table) {
            // Cek apakah foreign key ada sebelum dihapus
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'kucings' 
                AND COLUMN_NAME = 'customer_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            
            if (!empty($foreignKeys)) {
                $table->dropForeign(['customer_id']);
            }
        });
    }
};