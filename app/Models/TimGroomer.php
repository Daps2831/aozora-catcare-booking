<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimGroomer extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_tim';

    protected $fillable = [
        'anggota_1',
        'anggota_2',
    ];

    public function anggota1()
    {
        return $this->belongsTo(Groomer::class, 'anggota_1', 'id_groomer');
    }

    public function anggota2()
    {
        return $this->belongsTo(Groomer::class, 'anggota_2', 'id_groomer');
    }
}
