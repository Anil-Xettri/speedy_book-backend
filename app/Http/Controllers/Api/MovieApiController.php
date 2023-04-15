<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Movie;
use App\Models\Seat;
use App\Models\ShowTime;
use App\Models\Theater;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovieApiController extends BaseApiController
{
    public function getCinemaHalls(Request $request)
    {
        try {
            $cinemaHalls = Vendor::with('theaters', 'theaters.seats')->get();
            return response()->json([
                'success' => true,
                'data' => $cinemaHalls
            ]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function getTheaters(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'vendor_id' => 'required',
            ]);
            if ($validator->fails()) {
                $response['success'] = false;
                $response['message'] = $validator->messages()->first();
                return $response;
            }
            $theaters = Theater::where('vendor_id', $request->vendor_id)->with('seats', 'movies', 'movies.showTimes')->get();

            return response()->json([
                'success' => true,
                'data' => $theaters
            ]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function getMovies(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'vendor_id' => 'required',
                'theater_id' => 'required'
            ]);
            if ($validator->fails()) {
                $response['success'] = false;
                $response['message'] = $validator->messages()->first();
                return $response;
            }
            $movies = Movie::where(['vendor_id' => $request->vendor_id, 'theater_id' => $request->theater_id])->with('showTimes')->get();

            return response()->json([
                'success' => true,
                'data' => $movies
            ]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function movieDetails(Request $request, $id)
    {
        try {
            $movie = Movie::where('id', $id)->first();
            if (!$movie) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Movie.'
                ]);
            }

            $today = new Carbon(Carbon::now('Asia/Kathmandu')->format('Y-m-d'));
            $tomorrow = new Carbon(Carbon::tomorrow('Asia/Kathmandu')->format('Y-m-d'));
            $todayShows = [];
            $tomorrowShows = [];

            foreach ($movie->showTimes as $showTime) {
                foreach (json_decode($showTime->show_details, true) as $showDetail) {
                    if (new Carbon($showDetail['show_date']) == $today) {
                        $todayShows[] = [
                            'show_time_id' => $showTime->id,
                            'show_date' => $showDetail['show_date'],
                            'show_time' => $showDetail['show_time'],
                            'ticket_price' => $showDetail['ticket_price'],
                        ];
                    }

                    if (new Carbon($showDetail['show_date']) == $tomorrow) {
                        $tomorrowShows[] = [
                            'show_time_id' => $showTime->id,
                            'show_date' => $showDetail['show_date'],
                            'show_time' => $showDetail['show_time'],
                            'ticket_price' => $showDetail['ticket_price'],
                        ];
                    }
                }
            }
            return response()->json([
                'success' => true,
                'data' => ['movie' => $movie, '' . Carbon::parse($today)->format('Y-m-d') . '' => $todayShows, '' . Carbon::parse($tomorrow)->format('Y-m-d') . '' => $tomorrowShows]
            ]);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function showDetails(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'show_id' => 'required',
                'show_date' => 'required',
                'show_time' => 'required',
                'show_price' => 'required',
                'theater_id' => 'required',
            ]);
            if ($validator->fails()) {
                $response['success'] = false;
                $response['message'] = $validator->messages()->first();
                return $response;
            }

            $showTime = ShowTime::where('id', $request->show_id)->first();
            $theater = Theater::where('id', $request->theater_id)->first();
            if (!$showTime) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid show time.'
                ]);
            }

            $seatDetails = [];
            $booking = [];

            $showDateTime = $request->show_date . ' ' . $request->show_time;
            $booking[] = Booking::where(["theater_id" => $request->theater_id, "show_time_id" => $request->show_id, 'show_time' => $showDateTime])->pluck('id')->toArray();
            $emptySeats = Seat::where(['theater_id' => $request->theater_id, 'seat_name' => ""])->get();

            foreach ($emptySeats ?? [] as $emptySeat) {
                $seatDetails[] = [
                    'seat_id' => $emptySeat->id,
                    'seat_name' => "",
                    'row_no' => $emptySeat->row_no,
                    'column_no' => $emptySeat->column_no,
                    'status' => "Unavailable",
                ];
            }
            $seatData = [];
            if (!empty($booking[0])) {
                $seatData = BookingSeat::whereIn('booking_id', $booking[0])->pluck('seat_id')->toArray();
            }
            $AvailSeats = Seat::where('theater_id', $request->theater_id)->where('seat_name', '!=', "")->whereNotIn('id', $seatData)->get();
            foreach ($AvailSeats ?? [] as $availSeat) {
                $seatDetails[] = [
                    'seat_id' => $availSeat->id,
                    'seat_name' => $availSeat->seat_name,
                    'row_no' => $availSeat->row_no,
                    'column_no' => $availSeat->column_no,
                    'status' => "Available",
                ];
            }
            $b = [];
            if (!empty($booking[0])) {
                $b = BookingSeat::whereIn('booking_id', $booking[0])->get();
            }
            foreach ($b ?? [] as $f) {
                $seatDetails[] = [
                    'seat_id' => $f->seat_id,
                    'seat_name' => $f->seat->seat_name,
                    'row_no' => $f->seat->row_no,
                    'column_no' => $f->seat->column_no,
                    'status' => $f->status,
                ];
            }

            $arrays = collect($seatDetails)->sortBy('seat_id');
            $finalDetails = [];

            foreach ($arrays ?? [] as $array) {
                $finalDetails[] = $array;
            }

            return response()->json([
                'success' => true,
                'data' => ["theater" => $theater, "seats" => $finalDetails]
            ]);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function showings(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'vendor_id' => 'required',
            ]);
            if ($validator->fails()) {
                $response['success'] = false;
                $response['message'] = $validator->messages()->first();
                return $response;
            }

            $currentDate = new Carbon(Carbon::now('Asia/Kathmandu')->toDateString());
//            $tomorrowDate = new Carbon(Carbon::tomorrow('Asia/Kathmandu')->toDateString());
            $currentTime = new Carbon(Carbon::now('Asia/Kathmandu')->format('H:i:s'));
            $nowShowing = null;
            $nextShowing = null;
            $comingSoon = [];
            $diffSec = [];
//            $diff = [];
            $movies = Movie::where(['vendor_id' => $request->vendor_id, 'status' => 'Active'])->get();

            foreach ($movies as $i => $movie) {
                foreach ($movie->showTimes as $showTime) {
                    foreach (json_decode($showTime->show_details, true) ?? [] as $showDetails) {
                        $showDate = new Carbon($showDetails['show_date']);
                        $startingTime = date("H:i:s", strtotime($showDetails['show_time']));
                        $duration = $movie->duration;
                        $secs = strtotime($duration) - strtotime("00:00:00");
                        $result = date("H:i:s", strtotime($startingTime) + $secs);
                        $endingTime = $result;
                        $theater = Theater::where('id', $movie->theater_id)->first();
                        $releaseDate = $movie->release_date;
                        if ($currentDate->eq($showDate)) {
//                            if (strtotime($currentTime) >= strtotime($startingTime) && strtotime($currentTime) <= strtotime($endingTime)) {
                            $nowShowing[$movie->id] = [
                                'id' => $movie->id,
                                'title' => $movie->title,
                                'release_date' => $movie->release_date,
                                'duration' => $movie->duration,
                                'theater_id' => $movie->theater->id,
                                'theater' => $movie->theater->name,
                                'start_time' => $startingTime,
                                'end_time' => $endingTime,
                                'description' => $movie->description,
                                'image' => $movie->image_url
                            ];
//                            }

//                            if ($nowShowing || !empty($nowShowing)) {
//                                foreach ($nowShowing as $showing) {
//                                    if ($theater->id == $showing['theater_id'] && $startingTime > $showing['end_time']) {
//                                        $difference = Carbon::parse($showing['end_time'])->diffInSeconds(Carbon::parse($startingTime));
//                                        if (empty($diffSec)) {
//                                            $diffSec[$theater->id][$showTime->id] = $difference;
//                                        } else {
//                                            if (!isset($diffSec[$theater->id])) {
//                                                $diffSec[$theater->id][$showTime->id] = $difference;
//                                            } else {
//                                                foreach ($diffSec[$theater->id] as $oldShow) {
//                                                    if ($oldShow > $difference) {
//                                                        $diffSec[$theater->id] = [];
//                                                        $diffSec[$theater->id][$showTime->id] = $difference;
//                                                    }
//                                                }
//                                            }
//                                        }
//                                    }
//                                }
//                            }

                        }

                        //comingSoon
                        if ($releaseDate > $currentDate) {
                            $comingSoon[$movie->id] = [
                                'id' => $movie->id,
                                'title' => $movie->title,
                                'release_date' => $movie->release_date,
                                'duration' => $movie->duration,
                                'theater_id' => $movie->theater->id,
                                'theater' => $movie->theater->name,
                                'start_time' => $startingTime,
                                'end_time' => $endingTime,
                                'description' => $movie->description,
                                'image' => $movie->image_url
                            ];
                        }
                    }
                }
            }

//            if (!empty($diffSec)) {
//                foreach ($diffSec as $theaterHall) {
//                    foreach ($theaterHall as $showTimeId => $value) {
//                        $nextShowTime = ShowTime::where('id', $showTimeId)->first();
//
//                        foreach (json_decode($nextShowTime->show_details, true) as $showDetails) {
//                            $nextStartingTime = date("H:i:s", strtotime($showDetails['show_time']));
//                            $nextDuration = $nextShowTime->movie->duration;
//                            $nextSecs = strtotime($nextDuration) - strtotime("00:00:00");
//                            $nextResult = date("H:i:s", strtotime($nextStartingTime) + $nextSecs);
//                            $nextEndingTime = $nextResult;
//
//                            $nextShowing[] = [
//                                'id' => $nextShowTime->movie->id,
//                                'title' => $nextShowTime->movie->title,
//                                'theater_id' => $nextShowTime->theater->id,
//                                'theater' => $nextShowTime->theater->name,
//                                'release_date' => $nextShowTime->movie->release_date,
//                                'duration' => $nextShowTime->movie->duration,
//                                'start_time' => $nextStartingTime,
//                                'end_time' => $nextEndingTime,
//                                'description' => $nextShowTime->movie->description,
//                                'image' => $nextShowTime->movie->image_url
//                            ];
//                        }
//                    }
//                }
//            }

            $finalNowShowing = [];
            $finalComingSoon = [];

            foreach ($nowShowing ?? [] as $now) {
                $finalNowShowing[] = $now;
            }

            foreach ($comingSoon ?? [] as $soon) {
                $finalComingSoon[] = $soon;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'nowShowing' => $finalNowShowing,
//                    'nextShowing' => $nextShowing,
                    'comingSoon' => $finalComingSoon
                ]
            ]);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
