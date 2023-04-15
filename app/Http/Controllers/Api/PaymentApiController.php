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
            $now = Carbon::now('Asia/Kathmandu')->format('Y-m-d H:i');
            $currentDateTime = Carbon::parse($now);
            $givenDateTime = Carbon::parse($request->show_date . ' ' . $request->show_time);
            if ($givenDateTime->lt($currentDateTime)) {
                $diff = $givenDateTime->diffInMinutes($currentDateTime);

                if ($diff <= 60) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Booking time is over.'
                    ]);
                }
            }
            $query = [
                'vendor_id' => $request->vendor_id,
                'theater_id' => $request->theater_id,
                'movie_id' => $request->movie_id,
                'show_time_id' => $request->show_time_id,
                'show_time' => $request->show_date . ' ' . $request->show_time,
            ];
            $oldBookings = Booking::where($query)->get();

            $reserveSeats = [];

            foreach ($oldBookings ?? [] as $oldBooking) {
                foreach ($oldBooking->seats ?? [] as $seat) {
                    if ($seat->pivot->status == "Reserve" || $seat->pivot->status == "Sold Out" || $seat->pivot->status == "Unavailable") {
                        $reserveSeats[] = $seat->id;
                    }
                }
            }
            $invalidSeats = [];
            foreach ($request->seats ?? [] as $seat) {
                if (in_array($seat, $reserveSeats)) {
                    $invalidSeats[] = $seat;
                }
            }
            if (!empty($invalidSeats)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some seats are either already reserved, sold out or unavailable.',
                    'data' => ['already_reserved_seats' => $invalidSeats]
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

            foreach ($request->seats ?? [] as $seat) {
                $bookingSeat = new BookingSeat([
                    'booking_id' => $booking->id,
                    'seat_id' => $seat,
                    'status' => 'Reserve',
                ]);

                $bookingSeat->save();
            }

            $bookingDetails = Booking::where('id', $booking->id)->with('vendor', 'theater', 'movie')->first();

            $bookingSeatsDetails = BookingSeat::where('booking_id', $booking->id)->get();
            $seats = [];

            foreach ($bookingSeatsDetails ?? [] as $seatData) {
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
                'data' => ['booking' => $bookingDetails, 'seats' => $seats, 'type' => $request->type]
            ]);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function cancelBooking($id)
    {
        try {
            $booking = Booking::where('id', $id)->first();
            if (!$booking) {
                return response()->json([
                    "success" => false,
                    "message" => "Invalid Booking."
                ]);
            }

            $booking->delete();

//            $bookingSeats = BookingSeat::where('booking_id', $booking->id)->get();
//
//            foreach ($bookingSeats ?? [] as $bookingSeat)
//            {
//                $bookingSeat->status = "Available";
//                $bookingSeat->ticket_number = null;
//
//                $bookingSeat->update();
//            }

            return response()->json([
                "success" => true,
                "message" => "Booking cancelled successfully."
            ]);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function bookingList()
    {
        try {
            $customer = auth('customer-api')->user();

            $bookings = Booking::where('customer_id', $customer->id)->with('seats', 'vendor', 'theater', 'movie')->get();

            return response()->json([
                'success' => true,
                'data' => $bookings
            ]);

        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function bookingDetails($id)
    {
        try {
            $customer = auth('customer-api')->user();

            $booking = Booking::where('id', $id)->with('vendor', 'theater', 'movie')->first();
            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => "Invalid Booking"
                ]);
            }

            $bookingSeats = BookingSeat::where('booking_id', $booking->id)->get();
            $seats = [];

            foreach ($bookingSeats ?? [] as $bookingSeat) {
                $seat = Seat::where('id', $bookingSeat->seat_id)->first();

                $seats[] = [
                    'id' => $seat->id,
                    'seat_name' => $seat->seat_name,
                    'ticket_number' => $bookingSeat->ticket_number
                ];
            }

            return response()->json([
                'success' => true,
                'data' => ['booking' => $booking, 'seats' => $seats]
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

            $booking = Booking::where('id', $request->booking_id)->with('vendor', 'theater', 'movie')->first();

            if (!$booking) {
                return response()->json([
                    "success" => false,
                    "message" => "Invalid Booking."
                ]);
            }


            $now = Carbon::now('Asia/Kathmandu')->format('Y-m-d H:i');
            $currentDateTime = Carbon::parse($now);
            $givenDateTime = Carbon::parse($booking->show_date . ' ' . $booking->show_time);
            if ($givenDateTime->lt($currentDateTime)) {
                $diff = $givenDateTime->diffInMinutes($currentDateTime);

                if ($diff <= 60) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Booking time is over.'
                    ]);
                }
            }


//            $verify_url = "https://a.khalti.com/api/v2/epayment/lookup/";
//
//            $response = Http::withHeaders([
//
//                'Authorization' => 'Key test_secret_key_e3dae072d0ee46a48e5d08b713119810',
//
//                'Content-Type' => 'application/json',
//
//            ])->post($verify_url, [
//
//                "amount" => $request->amount * 100,
//                "token" => $request->token,
//                "transaction_id" => 23
//
//            ]);


            $args = http_build_query(array(
                'token' => $request->token,
                'amount' => $request->amount
            ));

            $url = "https://khalti.com/api/v2/payment/verify/";

# Make the call using API.
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $headers = ['Authorization: Key test_secret_key_e3dae072d0ee46a48e5d08b713119810'];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Response
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
            } else {
                $error_msg = null;
            }
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

//            $response_data = json_decode($response->body(), TRUE);

//            error_log($response->status());
//
//            error_log(json_encode($response->body()));

            if ($status_code == 400) {
                return response()->json([
                    "success" => false,
                    "message" => "Payment failed."
                ]);
            }

            $booking->status = "Paid";
            $booking->update();

            $bookingSeats = BookingSeat::where('booking_id', $booking->id)->get();
            $seats = [];

            foreach ($bookingSeats ?? [] as $bookingSeat) {
                $bookingSeat->status = "Sold Out";
                $bookingSeat->ticket_number = Str::random(5);
                $bookingSeat->update();

                $seat = Seat::where('id', $bookingSeat->seat_id)->first();

                $seats[] = [
                    'id' => $seat->id,
                    'seat_name' => $seat->seat_name,
                    'ticket_number' => $bookingSeat->ticket_number
                ];
            }


            $payment = new Payment([
                'booking_id' => $request->booking_id,
                'vendor_id' => $booking->vendor_id,
                'payment_method' => "Khalti",
                'payment_verify_at' => Carbon::now('Asia/Kathmandu')->toDateTimeString()
            ]);
            $payment->save();

            return response()->json([
                "success" => true,
                "message" => "Payment Success.",
                'data' => ['booking' => $booking, 'seats' => $seats, 'type' => $request->type]
            ]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
