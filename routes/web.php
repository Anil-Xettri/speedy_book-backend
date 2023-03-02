<?php

use App\Http\Controllers\Auth\LoginController;
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

Auth::routes(['register' => false, 'reset' => false, 'password.reset' => false]);

Route::get('/home', [App\Http\Controllers\superadmin\HomeController::class, 'dashboard'])->name('superadmin.home');

Route::group(['prefix' => 'superadmin', 'middleware' => 'auth'], function () {

});

Route::group(['prefix' => 'vendor', 'middleware' => 'auth:vendor'], function () {
    Route::get('home', [\App\Http\Controllers\vendor\VendorController::class, 'dashboard'])->name('vendor.home');
    Route::get('/logout', [LoginController::class, 'logout']);

});
