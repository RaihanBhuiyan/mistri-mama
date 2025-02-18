<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use OwenIt\Auditing\Contracts\Auditable;


class Category extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    //  protected $appends = ['services'];

    protected $fillable = [
        'name', 'name_bn', 'slug', 'description','description_bn', 'benifits','benifits_bn', 'thumb', 'icon', 'opt_image', 'position', 'created_at', 'updated_at',
    ];

    public function services()
    {
        return $this->hasMany('App\Service');
    }

    public function serviceBits()
    {
        return $this->hasMany('App\ServiceBit');
    }

    public function relFeatureBit()
    {
        return $this->hasOne('App\ServiceBitsFeaturesHistory', 'category_id', 'id');
    }

    // icon_url (hover)
    public function getIconUrlAttribute()
    {
        return (!empty($this->icon)) ? env('APP_URL').'/upload/categories/'. $this->icon : env('APP_URL').'/upload/'.'black-thumbnail.png';
    }

    // thumb_url
    public function getThumbUrlAttribute()
    {
        return (!empty($this->thumb)) ? env('APP_URL').'/upload/categories/'. $this->thumb : env('APP_URL').'/upload/'.'black-thumbnail.png';
    }

    // opt_image_url
    public function getOptImageUrlAttribute()
    {
        return (!empty($this->opt_image)) ? env('APP_URL').'/upload/categories/'. $this->opt_image : env('APP_URL').'/upload/'.'black-thumbnail.png';
    }
}
