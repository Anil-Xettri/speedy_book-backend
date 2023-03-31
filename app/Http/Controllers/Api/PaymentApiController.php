<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class PaymentApiController extends BaseApiController
{
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

                "amount" => $validator['amount'] * 100,
                "token" => $validator['token']

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

            $payment = new Payment([
                'booking_id' => $validator['amount'],
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
