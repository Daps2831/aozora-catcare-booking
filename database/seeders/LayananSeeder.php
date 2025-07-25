<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Pastikan ini ada

class LayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('layanans')->insert([
            [
                'nama_layanan' => 'Grooming Sehat',
                'harga' => 75000,
                'estimasi_pengerjaan_per_kucing' => 60,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_layanan' => 'Grooming Anti Kutu',
                'harga' => 85000,
                'estimasi_pengerjaan_per_kucing' => 75,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_layanan' => 'Grooming Anti Jamur',
                'harga' => 95000,
                'estimasi_pengerjaan_per_kucing' => 90,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}