<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class Admin extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'admin';

    protected $fillable = [
        'user_id', 'name', 'phone', 'email', 'address', 'photo', 'type', 'remarks',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
    // photo_url
    public function getPhotoUrlAttribute()
    {
        return (!empty($this->photo)) ? env('APP_URL').'/upload/sp/'. $this->photo : env('APP_URL').'/upload/'.'black-thumbnail.png';
    }
}
