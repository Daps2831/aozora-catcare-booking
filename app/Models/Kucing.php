<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Kucing extends Model
{
    use HasFactory;

    protected $fillable = [
         'customer_id', 'nama_kucing', 'jenis', 'umur', 'riwayat_kesehatan', 'gambar'
    ];

    // Boot method untuk cleanup gambar saat kucing dihapus
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($kucing) {
            // Hapus file gambar saat kucing dihapus
            if ($kucing->gambar && Storage::disk('public')->exists($kucing->gambar)) {
                Storage::disk('public')->delete($kucing->gambar);
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi dengan User melalui Customer
    public function user()
    {
        return $this->hasOneThrough(User::class, Customer::class, 'id', 'id', 'customer_id', 'user_id');
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_kucing');
    }
}