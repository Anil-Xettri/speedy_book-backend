<?php


namespace App\Helpers;


class GuardHelper
{
   static function check(){
        $guards = array_keys(config('auth.guards'));

        return collect($guards)->first(function($guard){
            return auth()->guard($guard)->check();
        });
    }
}
