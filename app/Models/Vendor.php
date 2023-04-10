<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Vendor extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, InteractsWithMedia;

    protected $guarded = ['id'];
    protected $appends = ['image_url', 'banner_url'];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function adminlte_profile_url()
    {
        return route('vendor.profile',$this->id);
    }

    function getImageUrlAttribute()
    {
        return $this->getFirstMediaUrl();
    }

    function getBannerUrlAttribute()
    {
        return $this->getFirstMediaUrl('banner-image');
    }

    public function theaters()
    {
        return $this->hasMany(Theater::class, 'vendor_id');
    }

    public function seats()
    {
        return $this->hasMany(Seat::class, 'vendor_id');
    }

    public function movies()
    {
        return $this->hasMany(Movie::class, 'vendor_id');
    }
    public function showTimes()
    {
        return $this->hasMany(ShowTime::class, 'vendor_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'vendor_id');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        try {
            $this->addMediaConversion('thumb')
                ->width(160)
                ->height(300);

            $this->addMediaConversion('medium')
                ->width(320)
                ->height(320);

        } catch (InvalidManipulation $e) {

        }
    }

    function getImage($collectionName = null)
    {
        if ($collectionName) {
            if (count($this->getMedia($collectionName)) == 0) return null;
            return $this->getFirstMediaUrl($collectionName ?? null);
        } else {
            if (count($this->getMedia()) == 0) return null;
            return $this->getFirstMediaUrl() ?? null;
        }
    }

    function getImageByIndex($index = 0)
    {
        if (count($this->getMedia()) == 0) return null;
//        if ($this->getMedia()->first()->getUrl('large') != null)
        try {
            return $this->getMedia()[$index]->getUrl() ?? null;
        } catch (\Exception $e) {
            return null;
        }
        return $this->getFirstMediaUrl() ?? null;
    }

    function getThumbnail($collectionName = null)
    {
        if ($collectionName) {
            if (count($this->getMedia($collectionName)) == 0) return null;
            return $this->getMedia($collectionName)->first()->getUrl('medium') ?? null;
        } else {
            if (count($this->getMedia()) == 0) return null;
            return $this->getMedia()->first()->getUrl('medium') ?? null;
        }
    }
}
