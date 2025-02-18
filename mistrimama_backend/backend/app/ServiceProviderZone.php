<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ServiceProviderZone extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['service_provider_id', 'zone_id'];

    public function zone() //zone
    {
        return $this->belongsTo('App\Zone');
    }

    public function name() //zone name from zone table
    {
        return $this->cluster->zone;
    }
}
