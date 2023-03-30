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
    protected $appends = ['image_url', 'banner_url'];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function adminlte_profile_url()
    {
        return route('vendor.profile',$this->id);
    }

    function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset($this->image);
        } else {
            return null;
        }
    }

    function getBannerUrlAttribute()
    {
        if ($this->banner_image) {
            return asset($this->banner_image);
        } else {
            return null;
        }
    }

    public function theaters()
    {
        return $this->hasMany(Theater::class, 'vendor_id');
    }

    public function seats()
    {
        return $this->hasMany(Seat::class, 'vendor_id');
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
