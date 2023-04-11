<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSeat extends Model
{
    use HasFactory;
    protected $table = 'booking_seats';
    protected $fillable = [
      'id',
      'booking_id',
      'seat_id',
      'status',
      'ticket_number'
    ];
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
}
