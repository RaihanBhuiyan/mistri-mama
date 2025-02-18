<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Promocode extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'type', 'promocode', 'cash', 'percent', 'up_to', 'validity_date', 'uses_count', 'details', 'status',
    ];

    public function promouser()
    {
        return $this->hasMany('App\PromoUser', 'promocode');
    }
}
