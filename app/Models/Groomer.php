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
}
