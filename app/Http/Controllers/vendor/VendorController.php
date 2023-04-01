<?php

namespace App\Http\Controllers\vendor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\ShowTime;
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
        $payments = Payment::where('vendor_id', auth('vendor')->user()->id)->with('booking')->get();
        $collections = 0;
        foreach ($payments as $payment)
        {
            $collections += $payment->booking->sum('total');
        }
        $info['collection'] = $collections;
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
        $nextShowing = null;
        $diffSec = [];
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
                    $theater = Theater::where('id', $movie->theater_id)->first();
                    //nowShowing
                    if ($currentDate->eq($showDate)) {
                        if (strtotime($currentTime) >= strtotime($startingTime) && strtotime($currentTime) <= strtotime($endingTime)) {
                            $nowShowing[$movie->id] = [
                                'id' => $movie->id,
                                'title' => $movie->title,
                                'theater_id' => $theater->id,
                                'theater' => $theater->name,
                                'start_time' => $startingTime,
                                'end_time' => $endingTime,
                                'image' => $movie->image_url
                            ];
                        }
                    }

                    //nextShowing
                    if ($nowShowing || !empty($nowShowing)) {
                        foreach ($nowShowing as $showing) {
                            if ($theater->id == $showing['theater_id'] && $startingTime > $showing['end_time']) {
                                $difference = Carbon::parse($showing['end_time'])->diffInSeconds(Carbon::parse($startingTime));
                                if (empty($diffSec)) {
                                    $diffSec[$theater->id][$showTime->id] = $difference;
//                                            $diff[$showTime->id] = Carbon::parse($showing['end_time'])->diffInSeconds(Carbon::parse($startingTime));
                                } else {
                                    if (!isset($diffSec[$theater->id])) {
                                        $diffSec[$theater->id][$showTime->id] = $difference;
                                    } else {
                                        foreach ($diffSec[$theater->id] as $oldShow) {
                                            if ($oldShow > $difference) {
                                                $diffSec[$theater->id] = [];
                                                $diffSec[$theater->id][$showTime->id] = $difference;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    //comingSoon
                    if ($showDate->between($nWeekSDate, $nWeekEDate)) {
                        $theater = Theater::where('id', $movie->theater_id)->first();
                        $comingSoon[$movie->id] = [
                            'id' => $movie->id,
                            'title' => $movie->title,
                            'theater_id' => $theater->id,
                            'theater' => $theater->name,
                            'start_time' => $startingTime,
                            'end_time' => $endingTime,
                            'image' => $movie->image_url
                        ];
                    }

                }
            }
        }

        //nextShowing
        if (!empty($diffSec)) {
            foreach ($diffSec as $theaterHall) {
                foreach ($theaterHall as $showTimeId => $value) {
                    $nextShowTime = ShowTime::where('id', $showTimeId)->first();

                    foreach (json_decode($nextShowTime->show_details, true) as $showDetails) {
                        $nextStartingTime = date("H:i:s", strtotime($showDetails['show_time']));
                        $nextDuration = $nextShowTime->movie->duration;
                        $nextSecs = strtotime($nextDuration) - strtotime("00:00:00");
                        $nextResult = date("H:i:s", strtotime($nextStartingTime) + $nextSecs);
                        $nextEndingTime = $nextResult;

                        $nextShowing[] = [
                            'id' => $nextShowTime->movie->id,
                            'title' => $nextShowTime->movie->title,
                            'theater_id' => $nextShowTime->theater->id,
                            'theater' => $nextShowTime->theater->name,
                            'start_time' => $nextStartingTime,
                            'end_time' => $nextEndingTime,
                            'description' => $nextShowTime->movie->description,
                            'image' => $nextShowTime->movie->image_url
                        ];
                    }
                }
            }
        }


        $info['nowShowings'] = $nowShowing;
        $info['nextShowings'] = $nextShowing;
        $info['newMovies'] = $comingSoon;
        return view('vendors.home', $info);
    }

    public function profile($id)
    {
        $info['item'] = Vendor::findOrFail($id);
        return view('vendors.profile', $info);
    }
}
