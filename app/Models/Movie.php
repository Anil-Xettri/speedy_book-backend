<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['image_url'];

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

    public function cinemaHall()
    {
        return $this->belongsTo(CinemaHall::class, 'cinema_hall_id');
    }

    public function showTimes()
    {
        return $this->hasMany(ShowTime::class, 'movie_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'movie_id');
    }
}
