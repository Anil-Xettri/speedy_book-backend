<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CinemaHall extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['image_url'];
    protected $casts = ['seat_details' => 'array'];

    function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset($this->image);
        } else {
            return null;
        }
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function movies()
    {
        return $this->hasMany(Movie::class, 'cinema_hall_id');
    }

    public function showTimes()
    {
        return $this->hasMany(ShowTime::class, 'cinema_hall_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'cinema_hall_id');
    }
}
