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
