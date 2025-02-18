<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use SoftDeletes;
    protected $table = "media";
    protected $fillable = [
        'user_id', 'type', 'dir', 'filename', 'status', 'updated_by', 'comments'
    ];

    // photo_url
    public function getPhotoUrlAttribute()
    {
        return (!empty($this->filename)) ? env('APP_URL').'/'.$this->dir.'/'. $this->filename : env('APP_URL').'/public/upload/'.'black-thumbnail.png';
    }

    public function serviceProvider()
    {
        return $this->belongsTo('App\ServiceProvider', 'user_id', 'user_id');
    }

    // replaced_type
    public function getReplacedTypeAttribute()
    {
        return strtoupper(str_replace("_", " ", $this->type));
    }
}
