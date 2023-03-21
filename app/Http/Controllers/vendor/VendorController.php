<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CinemaHall;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function dashboard()
    {
        $info['cinemaHalls'] = CinemaHall::where('vendor_id', auth('vendor')->user()->id)->count();
        $info['movies'] = Movie::where('vendor_id', auth('vendor')->user()->id)->count();
        $info['bookings'] = Booking::where('vendor_id', auth('vendor')->user()->id)->count();
        $allMovies = Movie::where('vendor_id', auth('vendor')->user()->id)->get();
        $currentDate = null;
        $currentTime = null;
        try {
            $currentDate = new Carbon(Carbon::now('Asia/Kathmandu')->toDateString());
            $currentTime = new Carbon(Carbon::now('Asia/Kathmandu')->format('H:i:s'));
        } catch (\Exception $e) {
            error_log($e);
        }
        $nowShowing = [];
        $comingSoon = [];

        foreach ($allMovies as $movie) {
            foreach ($movie->showTimes as $showTime) {
                foreach (json_decode($showTime->show_details, true) as $showDetails) {
                    try {
                        $showDate = new Carbon($showDetails['show_date']);
                    } catch (\Exception $e) {
                        error_log($e);
                    }
                    $startingTime = date("H:i:s", strtotime($showDetails['show_time']));
                    $duration = $movie->duration;
                    $secs = strtotime($duration) - strtotime("00:00:00");
                    $result = date("H:i:s", strtotime($startingTime) + $secs);
                    $endingTime = $result;
                    if ($currentDate->eq($showDate)) {
                        if (strtotime($currentTime) >= strtotime($startingTime) && strtotime($currentTime) <= strtotime($endingTime)) {
                            $cinemaHall = CinemaHall::where('id', $movie->cinema_hall_id)->first();
                            $nowShowing[] = [
                                'id' => $movie->id,
                                'title' => $movie->title,
                                'cinema_hall' => $cinemaHall->name,
                                'start_time' => $startingTime,
                                'end_time' => $endingTime,
                                'image' => $movie->image_url
                            ];
                        }
                    }

                }
            }
        }
        $info['nowShowings'] = $nowShowing;
        return view('vendors.home', $info);
    }
}
