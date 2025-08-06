<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes, HasFactory;

    protected $fillable = [
        'name', 'username', 'email', 'password', 'role', 'kontak', 'alamat', 
    ];

    protected $guarded = [];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['deleted_at'];

    // Boot method untuk handle cascade delete dan cleanup files
    protected static function boot()
    {
        parent::boot();

        // Sync username ketika user diupdate
        static::updated(function ($user) {
            if ($user->customer && $user->wasChanged('username')) {
                // Log untuk debugging
                \Log::info('Syncing username from user to customer', [
                    'user_id' => $user->id,
                    'old_username' => $user->getOriginal('username'),
                    'new_username' => $user->username
                ]);
                
                $user->customer->update([
                    'username' => $user->username
                ]);
            }
        });

        static::deleting(function ($user) {
            // Hapus semua foto kucing sebelum user dihapus
            if ($user->customer && $user->customer->kucings) {
                foreach ($user->customer->kucings as $kucing) {
                    if ($kucing->gambar && Storage::disk('public')->exists($kucing->gambar)) {
                        Storage::disk('public')->delete($kucing->gambar);
                    }
                }
            }

            // Manual delete customer dan kucing jika belum ada foreign key constraint
            if ($user->customer) {
                $user->customer->kucings()->delete();
                $user->customer->delete();
            }
        });
    }

    // Relasi dengan tabel Customer
    public function customer() 
    {
        return $this->hasOne(Customer::class, 'user_id');
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
            Auth::login($user);
        }
        return $user;
    }

    // Relasi dengan Booking
    public function bookings()
    {
        return $this->hasMany(\App\Models\Booking::class, 'customer_id', 'id');
    }

    public function kucings()
    {
        return $this->hasMany(\App\Models\Kucing::class, 'user_id');
    }
}