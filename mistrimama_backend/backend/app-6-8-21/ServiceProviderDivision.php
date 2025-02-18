<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ServiceProviderDivision extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'service_provider_id', 'division_id',
    ];

    public function division() //area
    {
        return $this->belongsTo('App\Division');
    }

    public function name() //division name from division table
    {
        return $this->division->name;
    }
}
