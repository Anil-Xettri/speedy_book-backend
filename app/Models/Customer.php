<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Customer extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $guarded = ['id'];
    protected $appends = ['profile_image_url'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset($this->profile_image);
        } else {
            return null;
        }
    }
}
