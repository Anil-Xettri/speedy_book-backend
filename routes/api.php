<?php

use App\Http\Controllers\Api\CustomerAuthApiController;
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


    Route::group(['prefix' => 'customer', 'middleware' => 'auth:customer-api'], function () {
        Route::post('update-profile', [CustomerAuthApiController::class, 'updateProfile']);
        Route::get('complete-profile', [CustomerAuthApiController::class, 'completeProfile']);
        Route::post('change-password', [CustomerAuthApiController::class, 'changePassword']);
        Route::post('logout', [CustomerAuthApiController::class, 'logout']);
    });
});
