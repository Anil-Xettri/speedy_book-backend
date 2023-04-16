<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Movie;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
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

        $weekWiseMovies = [];
        $weeks = [];

        $weeksData = $this->getWeekRange();
        foreach ($weeksData ?? [] as $i => $week)
        {
            $weekDates = explode(',', $week);
            $startingWeekDate = $weekDates[0];
            $endingWeekDate = $weekDates[1];

            $weekWiseMovies[] = Movie::whereMonth('release_date', Carbon::now('Asia/Kathmandu')->month)->whereBetween('release_date', [$startingWeekDate, $endingWeekDate])->count();
            $weeks[] = 'week-'.$i;
        }

        $info['weeks'] = $weeks;
        $info['weekWiseMovies'] = $weekWiseMovies;

        return view('superadmin.home', $info);
    }

    public function profile($id)
    {
        $info['item'] = User::findOrFail($id);
        return view('superadmin.profile',$info);
    }

    function getWeekRange(){

        //format string
        $f = 'Y-m-d';

        //if you want to record time as well, then replace today() with now()
        //and remove startOfDay()
        $today = Carbon::today();
        $date = $today->copy()->firstOfMonth()->startOfDay();
        $eom = $today->copy()->endOfMonth()->startOfDay();

        $dates = [];

        for($i = 1; $date->lte($eom); $i++){

            //record start date
            $startDate = $date->copy();

            //loop to end of the week while not crossing the last date of month
            while($date->dayOfWeek != Carbon::SUNDAY && $date->lte($eom)){
                $date->addDay();
            }

            $dates[$i] = $startDate->format($f) . ',' . $date->format($f);
            $date->addDay();
        }

        return $dates;
    }
}
