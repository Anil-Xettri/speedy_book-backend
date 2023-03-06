<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Models\CinemaHall;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function dashboard()
    {
        $info['cinemaHalls'] = CinemaHall::where('vendor_id', auth('vendor')->user()->id)->count();
        return view('vendors.home', $info);
    }
}
