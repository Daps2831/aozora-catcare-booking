<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'namaLayanan', 'deskripsi', 'harga', 'groomer',
    ];

    public function jadwalGrooming()
    {
        return $this->hasMany(JadwalGrooming::class);
    }
}
