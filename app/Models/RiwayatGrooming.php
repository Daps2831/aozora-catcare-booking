<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatGrooming extends Model
{
    use HasFactory;

    protected $fillable = [
        'kucing_id', 'layanan_id', 'catatan',
    ];

    public function kucing()
    {
        return $this->belongsTo(Kucing::class);
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }
}
