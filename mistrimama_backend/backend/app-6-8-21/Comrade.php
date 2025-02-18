<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Comrade extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'user_id', 'service_provider_id', 'comrade_code', 'name', 'phone', 'alt_phone', 'email', 'address', 'photo', 'nid_no', 'nid_front', 'nid_back', 'services', 'status', 'approve', 'lastStatusUpdateBy'
    ];

    public function serviceProvider()
    {
        return $this->belongsTo('App\ServiceProvider');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function orderSystem()
    {
        return $this->hasMany('App\OrderSystem');
    }

    // total_job_done
    public function getTotalJobDoneAttribute() //area
    {
        return $this->orderSystem->where('state', 4)->count();
    }

    // photo_url
    public function getPhotoUrlAttribute()
    {
        return !empty($this->photo) ? env('APP_URL').'/upload/client/'. $this->photo : env('APP_URL').'/upload/'.'black-thumbnail.png';
    }

    // nid_front_url
    public function getNidFrontUrlAttribute()
    {
        return env('APP_URL').'/upload/sp/'.$this->nid_front;
    }

    // nid_back_url
    public function getNidBackUrlAttribute()
    {
        return env('APP_URL').'/upload/sp/'.$this->nid_back;
    }
}
