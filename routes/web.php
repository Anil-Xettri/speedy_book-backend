<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\superadmin\ChangePasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/redirect', function () {
    if (\App\Helpers\GuardHelper::check() === "vendor") {
        return redirect()->route('vendor.home');
    } else {
        return redirect()->route('superadmin.home');
    }
})->name('redirect');

Route::post('/login/vendor', [LoginController::class, 'vendorLogin'])->name('vendor.login-redirect');

Route::get('vendor/forget/password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('vendor.reset-password');
Route::post('vendor/reset-email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('reset.link');

Route::post('vendor/reset-password', [ResetPasswordController::class, 'reset'])->name('vendor.reset');


Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\superadmin\HomeController::class, 'dashboard'])->name('superadmin.home');

Route::group(['prefix' => 'superadmin', 'middleware' => 'auth'], function () {
    Route::get('/profile/{id}', [App\Http\Controllers\superadmin\HomeController::class, 'profile'])->name('superadmin.profile');
    Route::get('/change-password', [ChangePasswordController::class, 'changePassword'])->name('superadmin.change-password');
    Route::post('/change-password/save', [ChangePasswordController::class, 'changePasswordSave'])->name('superadmin-password.store');
    Route::resource('vendors', \App\Http\Controllers\superadmin\VendorController::class);
    Route::get('customers', [\App\Http\Controllers\superadmin\CustomerController::class,'index'])->name('customers.index');
    Route::resource('movie', \App\Http\Controllers\superadmin\MovieController::class);
});

Route::group(['prefix' => 'vendor', 'middleware' => 'auth:vendor'], function () {
    Route::get('/change-password', [\App\Http\Controllers\vendor\ChangePasswordController::class, 'changePassword'])->name('vendor.change-password');
    Route::post('/change-password/save', [\App\Http\Controllers\vendor\ChangePasswordController::class, 'changePasswordSave'])->name('vendor-password.store');
    Route::get('home', [\App\Http\Controllers\vendor\VendorController::class, 'dashboard'])->name('vendor.home');
    Route::get('profile/{id}', [\App\Http\Controllers\vendor\VendorController::class, 'profile'])->name('vendor.profile');
    Route::get('/logout', [LoginController::class, 'logout']);

    Route::resource('theaters', \App\Http\Controllers\vendor\TheaterController::class);
    Route::resource('movies', \App\Http\Controllers\vendor\MovieController::class);
    Route::resource('show-times', \App\Http\Controllers\vendor\ShowTimeController::class);
    Route::resource('bookings', \App\Http\Controllers\vendor\BookingController::class);
    Route::resource('payments', \App\Http\Controllers\vendor\PaymentController::class)->only('index');
    Route::get('collections', [\App\Http\Controllers\vendor\CollectionController::class, 'getCollections'])->name('collections');
});
