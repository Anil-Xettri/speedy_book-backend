<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends BaseModel
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['image_url', 'trailer_url'];

    function getImageUrlAttribute()
    {
        return $this->getFirstMediaUrl();
    }

    function getTrailerUrlAttribute()
    {
        return $this->getFirstMediaUrl('trailer');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function theater()
    {
        return $this->belongsTo(Theater::class, 'theater_id');
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
