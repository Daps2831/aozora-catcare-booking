<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'tanggalBooking',
        'jamBooking',
        'statusBooking',
        'estimasi',
        'alamatBooking' // <-- TAMBAHKAN BARIS INI
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function kucings() // Ganti nama menjadi jamak (plural)
    {
        // Tambahkan ->withPivot('layanan_id') untuk mengambil kolom layanan_id dari tabel pivot
        return $this->belongsToMany(Kucing::class, 'booking_kucing')->withPivot('layanan_id');
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }
}
