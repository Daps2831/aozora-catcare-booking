<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'kucing_id', 'layanan_id', 'tanggalBooking', 'statusBooking',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function kucing()
    {
        return $this->belongsTo(Kucing::class);
    }

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }
}
