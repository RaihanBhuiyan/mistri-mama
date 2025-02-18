<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Zone extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['name', 'code', 'cluster_id','status'];

    public function cluster()
    {
        return $this->belongsTo('App\Cluster');
    }
}
