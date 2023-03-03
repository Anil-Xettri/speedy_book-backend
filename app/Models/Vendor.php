<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Vendor extends Authenticatable
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['image_url'];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    function getImageUrlAttribute()
    {
        return asset($this->image);
    }
}
