<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = ['seat_no' => 'array'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function theater()
    {
        return $this->belongsTo(Theater::class, 'theater_id');
    }

    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'booking_seats')->withPivot('status', 'ticket_number')->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'booking_id');
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }

    public function showTime()
    {
        return $this->belongsTo(ShowTime::class, 'show_time_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
