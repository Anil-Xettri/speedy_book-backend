<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Payment;
use App\Models\Seat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PaymentApiController extends BaseApiController
{
    public function booking(Request $request)
    {
        try {
            $customer = auth('customer-api')->user();
            //validation
            $validator = Validator::make($request->all(), [
                'vendor_id' => 'required',
                'theater_id' => 'required',
                'movie_id' => 'required',
                'show_time_id' => 'required',
                'show_date' => 'required',
                'show_time' => 'required',
                'show_price' => 'required',
                'seats' => 'required',
            ]);

            if ($validator->fails()) {
                $response['data'] = $validator->messages();
                $response['success'] = false;
                return $response;
            }

            if (empty($request->seats)) {
                return response()->json([
                    "success" => false,
                    "message" => "At least one seat must be selected."
                ]);
            }

            $quantity = count($request->seats);
            $subTotal = $quantity * $request->show_price;

            $booking = new Booking([
                'customer_id' => $customer->id,
                'vendor_id' => $request->vendor_id,
                'theater_id' => $request->theater_id,
                'movie_id' => $request->movie_id,
                'show_time_id' => $request->show_time_id,
                'show_time' => $request->show_date . ' ' . $request->show_time,
                'quantity' => $quantity,
                'price' => $request->show_price,
                'sub_total' => $subTotal,
                'total' => $subTotal,
            ]);

            $booking->save();

            foreach ($request->seats ?? [] as $seat)
            {
                $bookingSeat = new BookingSeat([
                   'booking_id' => $booking->id,
                   'seat_id' => $seat,
                   'status' => 'Reserve',
                   'ticket_number' => Str::random(5),
                ]);

                $bookingSeat->save();
            }

            $bookingDetails = $booking->with('vendor', 'theater', 'movie')->first();

            $bookingSeatsDetails = BookingSeat::where('booking_id', $booking->id)->get();
            $seats = [];

            foreach ($bookingSeatsDetails ?? [] as $seatData)
            {
                $seat = Seat::where('id', $seatData->seat_id)->first();

                $seats[] = [
                    'id' => $seat->id,
                    'seat_name' => $seat->seat_name,
                    'ticket_number' => $seatData->ticket_number
                ];
            }


            return response()->json([
               'success' => true,
               'message' => 'Booked Successfully.',
               'data' => ['booking' => $bookingDetails, 'seats' => $seats, 'type' => 'reserve']
            ]);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function cancelBooking($id)
    {
        try {
            $booking = Booking::where('id', $id)->first();
            if (!$booking)
            {
                return response()->json([
                    "success" => false,
                    "message" => "Invalid Booking."
                ]);
            }

            $bookingSeats = BookingSeat::where('booking_id', $booking->id)->get();

            foreach ($bookingSeats ?? [] as $bookingSeat)
            {
                $bookingSeat->status = "Available";
                $bookingSeat->ticket_number = null;

                $bookingSeat->update();
            }

            return response()->json([
                "success" => true,
                "message" => "Booking cancelled successfully."
            ]);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function paymentVerification(Request $request)
    {
        try {
            $customer = auth('customer-api')->user();
            //validation
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'booking_id' => 'required',
                'amount' => 'required',
            ]);

            //amount should be in paisa amount * 100

            if ($validator->fails()) {
                $response['data'] = $validator->messages();
                $response['success'] = false;
                return $response;
            }

            $verify_url = "https://a.khalti.com/api/v2/epayment/lookup/";

            $response = Http::withHeaders([

                'Authorization' => 'Key live_secret_key_5ade38ac08764d34b124bbaec8956cc2',

                'Content-Type' => 'application/json',

            ])->post($verify_url, [

                "amount" => $request->amount * 100,
                "token" => $request->token

            ]);

            $response_data = json_decode($response->body(), TRUE);

//            error_log($response->status());
//
//            error_log(json_encode($response->body()));

            if ($response->failed() || !$response_data['idx']) {
                return response()->json([
                    "success" => false,
                    "message" => "Payment failed."
                ]);
            }

            $booking = Booking::where('id', $request->booking_id)->first();

            if (!$booking) {
                return response()->json([
                    "success" => false,
                    "message" => "Invalid Booking."
                ]);
            }

            $booking->status = "Paid";
            $booking->update();

            $bookingSeats = BookingSeat::where('booking_id', $booking->id)->get();

            foreach ($bookingSeats ?? [] as $bookingSeat) {
                $bookingSeat->status = "Sold Out";
                $bookingSeat->update();
            }

            $payment = new Payment([
                'booking_id' => $request->booking_id,
                'payment_method' => "Khalti",
                'payment_verify_at' => Carbon::now('Asia/Kathmandu')->toDateTimeString()
            ]);
            $payment->save();

            return response()->json([
                "success" => true,
                "message" => "Payment Success."
            ]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
