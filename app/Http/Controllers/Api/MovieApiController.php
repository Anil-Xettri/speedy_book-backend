<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
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
            $currentTime = new Carbon(Carbon::now('Asia/Kathmandu')->format('H:i:s'));
            $nowShowing = null;
            $nextShowing = null;
            $comingSoon = [];
            $diffSec = [];
//            $diff = [];
            $movies = Movie::where(['vendor_id' => $request->vendor_id, 'status' => 'Active'])->get();

            foreach ($movies as $i => $movie) {
                foreach ($movie->showTimes as $showTime) {
                    foreach (json_decode($showTime->show_details, true) as $showDetails) {
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
                                $nowShowing[] = [
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


                            //comingSoon
                            if ($releaseDate->gt($currentDate)) {
                                $comingSoon[] = [
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
            return response()->json([
                'success' => true,
                'data' => [
                    'nowShowing' => $nowShowing,
//                    'nextShowing' => $nextShowing,
                'comingSoon' => $comingSoon
                ]
            ]);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
