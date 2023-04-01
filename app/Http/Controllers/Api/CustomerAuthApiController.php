<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ImagePostHelper;
use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;

class CustomerAuthApiController extends BaseApiController
{
    public function signUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required||unique:customers,email',
            'phone' => 'nullable|min:8|max:11',
            'password' => 'required|min:8|string'
        ]);
        if ($validator->fails()) {
            $response['success'] = false;
            $response['message'] = $validator->messages()->first();
            return $response;
        } else {
            $data = $request->all();
            $data['password'] = bcrypt($request->password);
            $customer = new Customer($data);
            $customer->save();

            $tokenResult = $customer->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addMonths(3);
            $token->save();

            $customer = $customer->only(
                ['id',
                    'name',
                    'email',
                    'phone',
                ]
            );
            $token = [
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()];

            return response()->json(['success' => true,
                'data' => ['customer' => $customer, 'token' => $token,]
            ]);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required|string',
                'remember_me' => 'boolean'
            ]);
            if ($validator->fails()) {
                $response['message'] = $validator->messages()->first();
                $response['success'] = false;
                return $response;
            } else {

                $credentials = ['email' => $request->get('email'), 'password' => $request->get('password')];

                if (!Auth::guard('customer')->attempt($credentials))
                    return response()->json([
                        'success' => false,
                        'message' => 'The email or password is incorrect.'
                    ]);
                $user = $request->user('customer');

                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;
                if ($request->remember_me)
                    $token->expires_at = Carbon::now()->addMonths(3);
                $token->save();
                $user = $user->only('id', 'name', 'email', 'phone', 'profile_image');
                $token = [
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString()];

                return response()->json(['success' => true,
                    'data' => ['user' => $user, 'token' => $token]
                ]);
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function updateProfile(Request $request)
    {
        $customer = auth('customer-api')->user();

        //validation
        $validator = Validator::make($request->all(), [
            'phone' => 'nullable|min:8|max:11'
        ]);

        if ($validator->fails()) {
            $response['message'] = $validator->messages()->first();
            $response['success'] = false;
            return response()->json($response);
        }
        try {
            $customer->Update($request->only('name', 'email', 'phone'));

            if ($request->profile_image) {
                try {
                    $customer->clearMediaCollection();
                    $customer->addMediaFromBase64($request->profile_image)
                        ->toMediaCollection();
                    $customer->save();
                } catch (FileDoesNotExist $e) {
                } catch (\Exception $e) {
                    error_log($e);
                }
            }

        } catch (\Exception $e) {
            return response()->json(
                [
                    $e,
                    'success' => false,
                    'message' => 'Could\'t update the profile'
                ]
            );
        }
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $customer
        ]);
    }

    public function completeProfile(Request $request)
    {
        $customer = auth('customer-api')->user();
        return response()->json([
            'success' => true,
            'data' => $customer
        ]);
    }

    public function changePassword(Request $request)
    {
        try {
            $customer = auth('customer-api')->user();
            //validation
            $validator = Validator::make($request->all(), [
                'old_password' => 'required|string|min:8',
                'new_password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                $response['data'] = $validator->messages();
                $response['success'] = false;
                return $response;
            }

            if (!(Hash::check($request->get('old_password'), $customer->getAuthPassword()))) {
                // The passwords matches
                return response()->json(['success' => false, 'message' => 'Your current password does not match with the password you provided. Please try again.']);
            }

            //update
            if (strcmp($request->get('old_password'), $request->get('new_password')) == 0) {
                //Current password and new password are same
                return response()->json(['success' => false, 'message' => 'New Password cannot be same as your current password. Please choose a different password.']);
            }

            $customer->password = bcrypt($request->get('new_password'));
            $customer->save();

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully',
                'data' => $customer,
            ]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function forgetPassword(Request $request)
    {
        try {
            //validation
            $validator = Validator::make($request->all(), [
                'email' => 'required',
            ]);

            if ($validator->fails()) {
                $response['data'] = $validator->messages();
                $response['success'] = false;
                return $response;
            }

            $customer = Customer::where('email', $request->email)->first();

            $otp = "12345";
            $customer->otp = $otp;
            $customer->update();

            $mail_details = [
                'subject' => 'OTP Verification',
                'body' => $otp
            ];

            Mail::to($request->email)->send(new OtpMail($mail_details));


            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email. Please try again.'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'customer' => $customer->id,
                    'otp' => $otp
                ]
            ]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            //validation
            $validator = Validator::make($request->all(), [
                'customer_id' => 'required',
                'otp' => 'required',
                'new_password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                $response['data'] = $validator->messages();
                $response['success'] = false;
                return $response;
            }

            $customer = Customer::where('id', $request->customer_id)->first();
            if (!$customer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid customer id. Please try again.'
                ]);
            }
            if ($request->otp != "12345") {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid otp.'
                ]);
            }

            $customer->password = bcrypt($request->new_password);
            $customer->update();
            return response()->json([
                'success' => true,
                'message' => 'Password Reset Successfully.'
            ]);
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out'
        ]);
    }
}
