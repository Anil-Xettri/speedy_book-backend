<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Theater;
use App\Models\Movie;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function dashboard()
    {
        $info['theaters'] = Theater::where('vendor_id', auth('vendor')->user()->id)->count();
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
        $nWeekSDate = Carbon::parse('this monday')->toDateString();
        $nWeekEDate = Carbon::parse($nWeekSDate)->addDays(6)->toDateString();


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
                            $theater = Theater::where('id', $movie->theater_id)->first();
                            $nowShowing[$movie->id] = [
                                'id' => $movie->id,
                                'title' => $movie->title,
                                'theater' => $theater->name,
                                'start_time' => $startingTime,
                                'end_time' => $endingTime,
                                'image' => $movie->image_url
                            ];
                        }
                    }
                    if ($showDate->between($nWeekSDate, $nWeekEDate)) {
                        $theater = Theater::where('id', $movie->theater_id)->first();
                        $comingSoon[$movie->id] = [
                            'id' => $movie->id,
                            'title' => $movie->title,
                            'theater' => $theater->name,
                            'start_time' => $startingTime,
                            'end_time' => $endingTime,
                            'image' => $movie->image_url
                        ];
                    }

                }
            }
        }
        $info['nowShowings'] = $nowShowing;
        $info['newMovies'] = $comingSoon;
        return view('vendors.home', $info);
    }

    public function profile($id)
    {
        $info['item'] = Vendor::findOrFail($id);
        return view('vendors.profile', $info);
    }
}
