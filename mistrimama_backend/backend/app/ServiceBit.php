<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ServiceBit extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function service()
    {
        return $this->belongsTo('App\Service');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function relFeaturesBit()
    {
        return $this->hasOne('App\ServiceBitsFeaturesHistory', 'service_bit_id', 'id');
    }

    // features_thumb
    public function getFeaturesThumbAttribute()
    {
        return (!empty($this->relFeaturesBit)) ? $this->relFeaturesBit->features_image_url : NULL;
    }
    
    
    // checked_features
    public function getCheckedFeaturesAttribute()
    {
        return (!empty($this->relFeaturesBit)) ? true : false;
    }
    
    // tags_values
    public function getTagsValuesAttribute()
    {
        return (!empty($this->tags)) ? explode(",", $this->tags) : NULL;
    }
}
