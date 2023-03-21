<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;
    protected $fillable = [
      'id',
      'vendor_id',
      'cinema_hall_id',
      'row_no',
      'column_no',
      'seat_name',
      'status'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function Theater()
    {
        return $this->belongsTo(CinemaHall::class, 'cinema_hall_id');
    }
}
