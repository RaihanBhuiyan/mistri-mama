<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceBitsFeaturesHistory extends Model
{
    use SoftDeletes;
    protected $table = "service_bits_features_history";
    protected $fillable = [
        'category_id', 'service_bit_id', 'features_image'
    ];

    public function serviceBits()
    {
        return $this->belongsTo('App\ServiceBit', 'service_bit_id', 'id');
    }

    // features_image_url
    public function getFeaturesImageUrlAttribute()
    {
        return !empty($this->features_image) ? env('APP_URL').'/upload/services/'. $this->features_image : env('APP_URL').'/upload/'.'black-thumbnail.png';
    }
}
