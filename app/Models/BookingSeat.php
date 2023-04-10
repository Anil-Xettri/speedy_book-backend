<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSeat extends Model
{
    use HasFactory;
    protected $table = 'booking_seats';

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
}
