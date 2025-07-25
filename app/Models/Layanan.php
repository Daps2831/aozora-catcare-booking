<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_layanan',
        'harga',
        'estimasi_pengerjaan_per_kucing',
    ];

    public function jadwalGrooming()
    {
        return $this->hasMany(JadwalGrooming::class);
    }
}
