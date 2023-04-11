<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowTime extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = ['show_details' => 'array'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function theater()
    {
        return $this->belongsTo(Theater::class, 'theater_id');
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'show_time_id');
    }
}
