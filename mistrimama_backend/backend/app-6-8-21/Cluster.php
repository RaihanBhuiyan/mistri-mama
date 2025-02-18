<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Cluster extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['name', 'division_id', 'code'];

    public function division()
    {
        return $this->belongsTo('App\Division');
    }

    public function zone()
    {
        return $this->hasMany('App\Zone')->orderBy('name', 'asc');
    }
}
