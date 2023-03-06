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
        return asset($this->image);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function cinemaHall()
    {
        return $this->belongsTo(CinemaHall::class, 'cinema_hall_id');
    }
}
