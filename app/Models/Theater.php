<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theater extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'theater_id');
    }

    public function seats()
    {
        return $this->hasMany(Seat::class, 'theater_id');
    }

    public function movies()
    {
        return $this->hasMany(Movie::class, 'theater_id');
    }

    public function showTimes()
    {
        return $this->hasMany(ShowTime::class, 'theater_id');
    }
}
