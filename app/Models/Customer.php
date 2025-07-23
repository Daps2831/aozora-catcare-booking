<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    // Pastikan kolom timestamps diaktifkan
    public $timestamps = true;

    protected $fillable = [
        'user_id', 'customerSince', 'username', 'name', 'kontak', 'alamat', 'email'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kucings()
    {
        return $this->hasMany(Kucing::class);
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
