<?php

namespace App\Http\Controllers\superadmin;

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
        return view('superadmin.change_password', $info);
    }

    function changePasswordSave(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ]);
        $superadmin = User::findOrFail(auth()->user()->id);
        if (Hash::check($request->old_password, $superadmin->password)) {
            $superadmin->password = Hash::make($request->new_password);
            $superadmin->save();
            NotifyHelper::customSuccess('Password Changed Successfully.');
            return redirect()->back();
        } else {
            NotifyHelper::customError('Old Password Mismatched.');
            return redirect()->back()->withInput($request->input());
        }
    }
}
