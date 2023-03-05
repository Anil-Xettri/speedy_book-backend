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
        return asset($this->image);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
