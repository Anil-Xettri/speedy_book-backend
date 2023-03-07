<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CinemaHall;
use App\Models\Movie;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function dashboard()
    {
        $info['cinemaHalls'] = CinemaHall::where('vendor_id', auth('vendor')->user()->id)->count();
        $info['movies'] = Movie::where('vendor_id', auth('vendor')->user()->id)->count();
        $info['bookings'] = Booking::where('vendor_id', auth('vendor')->user()->id)->count();
        return view('vendors.home', $info);
    }
}
