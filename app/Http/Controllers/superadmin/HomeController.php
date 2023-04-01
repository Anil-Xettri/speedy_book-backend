<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Movie;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        $info['vendors'] = Vendor::all()->count();
        $info['movies'] = Movie::all()->count();
        $info['customers'] = Customer::all()->count();
        return view('superadmin.home', $info);
    }

    public function profile($id)
    {
        $info['item'] = User::findOrFail($id);
        return view('superadmin.profile',$info);
    }
}
