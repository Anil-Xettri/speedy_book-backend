<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Vendor extends Authenticatable
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['image_url'];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset($this->image);
        } else {
            return null;
        }
    }

    public function cinemaHalls()
    {
        return $this->hasMany(CinemaHall::class, 'vendor_id');
    }

    public function movies()
    {
        return $this->hasMany(Movie::class, 'vendor_id');
    }
    public function showTimes()
    {
        return $this->hasMany(ShowTime::class, 'vendor_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'vendor_id');
    }
}
