<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Theater;
use App\Models\Vendor;
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
}
