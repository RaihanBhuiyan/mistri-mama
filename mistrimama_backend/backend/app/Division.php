<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Division extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'name', 'code',
    ];

    public function cluster()
    {
        return $this->hasMany('App\Cluster')->orderBy('name', 'asc');
    }
}
