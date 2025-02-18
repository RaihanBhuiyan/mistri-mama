<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ServiceProviderCluster extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['service_provider_id', 'cluster_id'];

    public function cluster() //area
    {
        return $this->belongsTo('App\Cluster');
    }

    public function name() //cluster name
    {
        return $this->cluster->name;
    }
}
