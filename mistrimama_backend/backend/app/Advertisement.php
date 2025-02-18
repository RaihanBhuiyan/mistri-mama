<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        "place_name", "url", "image"
    ];

    // advertisement_image
    public function getAdvertisementImageAttribute()
    {
        return (!empty($this->image)) ? env('APP_URL').'/upload/advertisement/'. $this->image : env('APP_URL').'/upload/'.'black-thumbnail.png';
    }
}
