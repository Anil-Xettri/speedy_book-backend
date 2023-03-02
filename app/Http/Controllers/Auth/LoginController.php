<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:vendor')->except('logout');
        \auth()->guard('vendor')->logout();
    }

    public function vendorLogin(Request $request)
    {
        $this->validate($request, [
            'vendor_email' => 'required|email',
            'vendor_password' => 'required|string|min:8'
        ]);

        $vendor = Vendor::where('email', $request->vendor_email)->first();
        $remember_me  = ( !empty( $request->vendor_remember ) ) ? TRUE : FALSE;

        if ($vendor){
            try {

                if (Hash::check($request->vendor_password, $vendor->password)) {

                    auth()->guard('vendor')->login($vendor, $remember_me);
                    return redirect()->route('vendor.home');

                }else {
                    return back()->with('passwordError', 'Oops! You have entered an invalid password for vendor. Please try again.');
                }
            }
            catch (DecryptException $e)
            {
                dd($e->getMessage());
            }
        } else {
            return back()->with('emailError', 'Oops! You have entered an invalid email for vendor. Please try again.');
        }
        return back()->withInput($request->only('email', 'remember'));
    }
}
