<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;  // Pastikan ini ditambahkan
use Illuminate\Support\Facades\Auth;  // Pastikan ini ditambahkan
use Illuminate\Foundation\Auth\User as Authenticatable;  // Gunakan class User dari Auth


class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes, HasFactory;

    // Menentukan kolom yang bisa diisi secara massal
    protected $fillable = [
        'name', 'username', 'email', 'password', 'role', 'kontak', 'alamat', 
    ];

    // Menentukan kolom yang tidak bisa diisi
    protected $guarded = [];

    // Menyembunyikan kolom sensitif
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Kolom yang akan diterima oleh Laravel untuk SoftDeletes
    protected $dates = ['deleted_at'];

    // Relasi dengan tabel Customer
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    // Relasi dengan tabel Admin
    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

        // Relasi dengan Kucing (One-to-Many melalui Customer)
    public function kucing()
    {
        return $this->hasManyThrough(Kucing::class, Customer::class);
    }

    // Scope untuk mendapatkan pengguna dengan role admin
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    // Validasi password
    public function validatePassword($password)
    {
        return Hash::check($password, $this->password);
    }

    // Akses tambahan untuk API, membuat token autentikasi
    public function createToken($name)
    {
        return $this->createToken($name)->plainTextToken;
    }

    // Mengatur data untuk login dengan email dan password
    public static function loginWithEmailAndPassword($email, $password)
    {
        return self::where('email', $email)
            ->where('password', Hash::make($password))
            ->first();
    }

    // Mengatur User untuk login otomatis dengan email dan password yang diberikan
    public static function autoLogin($email, $password)
    {
        $user = self::loginWithEmailAndPassword($email, $password);
        if ($user) {
            Auth::login($user);  // Login otomatis jika user ditemukan
        }
        return $user;
    }

    // Relasi dengan Booking
    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class, 'customer_id', 'id');
    }
}

