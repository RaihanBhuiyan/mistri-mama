<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class OrderSystem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['order_id', 'service_provider_id', 'comrade_id', 'state', 'user_rating', 'sp_rating', 'sp_cat', 'commission'];


    public function serviceProvider()
    {
        return $this->belongsTo('App\ServiceProvider');
    }

    public function comrade()
    {
        return $this->belongsTo('App\Comrade');
    }

    public function order()
    {
        return $this->belongsTo('App\Order');
    }
}
