<?php

namespace App\Http\Controllers\vendor;

use App\Helpers\NotifyHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    function changePassword()
    {
        $info['title'] = 'Change Password';
        return view('vendors.change_password', $info);
    }

    function changePasswordSave(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ]);
        $vendor = User::findOrFail(auth('vendor')->user()->id);
        if (Hash::check($request->old_password, $vendor->password)) {
            $vendor->password = Hash::make($request->new_password);
            $vendor->save();
            NotifyHelper::customSuccess('Password Changed Successfully.');
            return redirect()->back();
        } else {
            NotifyHelper::customError('Old Password Mismatched.');
            return redirect()->back()->withInput($request->input());
        }
    }
}
