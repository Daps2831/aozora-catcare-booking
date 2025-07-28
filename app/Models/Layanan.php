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

    public function bookings()
    {
        return $this->belongsToMany(
            \App\Models\Booking::class,
            'booking_kucing',      // nama tabel pivot
            'layanan_id',          // foreign key di tabel pivot untuk layanan
            'booking_id'           // foreign key di tabel pivot untuk booking
        );
    }

    
}
