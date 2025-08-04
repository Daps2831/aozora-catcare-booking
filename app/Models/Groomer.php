<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Groomer extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_groomer';

    protected $fillable = [
        'nama',
        'no_hp',
    ];

    public function anggota1() {
        return $this->belongsTo(Groomer::class, 'anggota_1', 'id_groomer');
    }
    public function anggota2() {
        return $this->belongsTo(Groomer::class, 'anggota_2', 'id_groomer');
    }

    public function tim()
    {
        return $this->belongsTo(\App\Models\TimGroomer::class, 'tim_id', 'id_tim');
    }

}
