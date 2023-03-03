<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;

class  NotifyHelper
{
    public static function updateSuccess(){
        Session::flash('message', 'Updated successfully');
        Session::flash('type', 'success');
    }

    public static function addSuccess(){
        Session::flash('message', 'Added successfully');
        Session::flash('type', 'success');
    }
    public static function deleteSuccess(){
        Session::flash('message', 'Deleted successfully');
        Session::flash('type', 'success');
    }

    public static function addError(){
        Session::flash('message', 'Failed to add data');
        Session::flash('type', 'danger');
    }
    public static function deleteError(){
        Session::flash('message', 'Couldn\'t delete the data');
        Session::flash('type', 'danger');
    }

    public static function updateError(){
        Session::flash('message', 'Failed to update data');
        Session::flash('type', 'danger');
    }
    public static function unauthorizedError(){
        Session::flash('message', 'You don\'t have permissions for this operation');
        Session::flash('type', 'danger');
    }
    public static function customSuccess($message){
        Session::flash('message', $message);
        Session::flash('type', 'success');
    }
    public static function customInfo($message){
        Session::flash('message', $message);
        Session::flash('type', 'info');
    }
    public static function customError($message){
        Session::flash('message', $message);
        Session::flash('type', 'danger');
    }
    public static function customWarning($message){
        Session::flash('message', $message);
        Session::flash('type', 'warning');
    }

}

?>
