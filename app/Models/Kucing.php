<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kucing extends Model
{
    use HasFactory;

    protected $fillable = [
         'customer_id', 'nama_kucing', 'jenis', 'umur', 'riwayat_kesehatan', 'gambar'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_kucing');
    }
}
