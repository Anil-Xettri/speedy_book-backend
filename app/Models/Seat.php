<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;
    protected $fillable = [
      'id',
      'vendor_id',
      'theater_id',
      'row_no',
      'column_no',
      'seat_name',
      'status'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function theater()
    {
        return $this->belongsTo(Theater::class, 'theater_id');
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_seats')->withPivot('status', 'ticket_number')->withTimestamps();
    }
}
