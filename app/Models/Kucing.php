<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kucing extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'namaKucing', 'jenis', 'umur', 'riwayatKesehatan',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
