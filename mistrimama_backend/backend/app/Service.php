<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use OwenIt\Auditing\Contracts\Auditable;

class Service extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

 //   protected $appends = ['serviceBits'];

    protected $fillable = [
        'category_id', 'name','name_bn', 'slug', 'description','description_bn', 'thumb', 'icon', 'opt_image', 'position',
    ];

    protected $with = ['serviceBits'];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function serviceBits()
    {
        return $this->hasMany('App\ServiceBit');
    }

    // thumb_url
    public function getThumbUrlAttribute()
    {
        return (!empty($this->thumb)) ? env('APP_URL').'/upload/services/'. $this->thumb : env('APP_URL').'/upload/'.'black-thumbnail.png';
    }
}
