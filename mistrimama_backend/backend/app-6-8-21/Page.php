<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Page extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name', 'title', 'slug', 'media'
    ];

    // media_url
    public function getMediaUrlAttribute()
    {
        return (!empty($this->media)) ? env('APP_URL').'/upload/web/'. $this->media : env('APP_URL').'/upload/'.'black-thumbnail.png';
    }
}
