<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chatbot extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'pertanyaan', 'jawaban', 'waktu_interaksi',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
