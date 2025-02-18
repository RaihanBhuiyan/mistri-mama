<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Slider extends Model
{
    protected $guarded = [];
    
    // image_url
    public function getImageUrlAttribute()
    {
        return (!empty($this->image)) ? env('APP_URL').'/upload/web/'. $this->image : env('APP_URL').'/upload/'.'black-thumbnail.png';
    }
}
