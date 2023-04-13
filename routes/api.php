<?php

use App\Http\Controllers\Api\CustomerAuthApiController;
use App\Http\Controllers\Api\MovieApiController;
use App\Http\Controllers\Api\PaymentApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('customer-signUp', [CustomerAuthApiController::class, 'signUp']);
    Route::post('customer-login', [CustomerAuthApiController::class, 'login']);
    Route::post('forget-password', [CustomerAuthApiController::class, 'forgetPassword']);
    Route::post('reset-password', [CustomerAuthApiController::class, 'resetPassword']);
});


Route::group(['prefix' => '', 'middleware' => 'auth:customer-api'], function () {

    Route::group(['prefix' => 'auth/customer'], function () {
        Route::post('update-profile', [CustomerAuthApiController::class, 'updateProfile']);
        Route::get('complete-profile', [CustomerAuthApiController::class, 'completeProfile']);
        Route::post('change-password', [CustomerAuthApiController::class, 'changePassword']);
        Route::post('logout', [CustomerAuthApiController::class, 'logout']);
    });

    Route::get('cinema-halls', [MovieApiController::class, 'getCinemaHalls']);
    Route::get('theaters', [MovieApiController::class, 'getTheaters']);
    Route::get('movies', [MovieApiController::class, 'getMovies']);
    Route::get('movie/{id}', [MovieApiController::class, 'movieDetails']);
    Route::post('movie-showing', [MovieApiController::class, 'showings']);
    Route::post('show-details', [MovieApiController::class, 'showDetails']);
    Route::post('booking', [PaymentApiController::class, 'booking']);
    Route::get('booking-list', [PaymentApiController::class, 'bookingList']);
    Route::get('booking-details/{id}', [PaymentApiController::class, 'bookingDetails']);
    Route::get('cancel-booking/{id}', [PaymentApiController::class, 'cancelBooking']);
    Route::post('verify-payment', [PaymentApiController::class, 'paymentVerification']);

});
