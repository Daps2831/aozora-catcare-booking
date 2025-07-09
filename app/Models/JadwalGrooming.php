<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalGrooming extends Model
{
    use HasFactory;

    protected $fillable = [
        'layanan_id', 'waktuMulai', 'waktuSelesai', 'tanggal', 'status',
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    public function kucing()
    {
        return $this->belongsTo(Kucing::class);
    }
}
