<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Customer extends Model
{
    use HasFactory;

    // Pastikan kolom timestamps diaktifkan
    public $timestamps = true;

    protected $fillable = [
        'user_id', 'customerSince', 'username', 'name', 'kontak', 'alamat', 'email'
    ];

    // Boot method untuk handle cascade delete
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($customer) {
            // Hapus semua foto kucing sebelum customer dihapus
            if ($customer->kucings) {
                foreach ($customer->kucings as $kucing) {
                    if ($kucing->gambar && Storage::disk('public')->exists($kucing->gambar)) {
                        Storage::disk('public')->delete($kucing->gambar);
                    }
                }
            }
            
            // Hapus semua kucing yang terkait dengan customer ini
            // (Database cascade akan handle ini jika foreign key sudah diset)
            $customer->kucings()->delete();
        });
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function kucings()
    {
        return $this->hasMany(\App\Models\Kucing::class, 'customer_id');
    }

    public function chatbot()
    {
        return $this->hasMany(Chatbot::class);
    }

    public function booking()
    {
        return $this->hasMany(Booking::class);
    }
}