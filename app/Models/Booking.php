<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'layanan_id', 'tanggalBooking', 'statusBooking','estimasi'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function kucings() // Ganti nama menjadi jamak (plural)
    {
        return $this->belongsToMany(Kucing::class, 'booking_kucing');
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }
}
