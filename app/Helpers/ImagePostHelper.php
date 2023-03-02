<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Storage;

class ImagePostHelper
{
    public static  function saveImage($file, $url, $filename)
    {
        $file_url = $file->storeAs($url, $filename, 'public');

        return 'storage/'.$file_url;
    }

    public static function deleteImage($ImageUrl)
    {
        if ($ImageUrl) {
            unlink($ImageUrl);
            return true;
        } else {
            return false;
        }
    }
}
