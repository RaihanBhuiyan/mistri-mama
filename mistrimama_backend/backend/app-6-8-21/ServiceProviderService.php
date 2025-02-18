<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ServiceProviderService extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'service_provider_id', 'category_id',
    ];

    public function serviceProvider()
    {
        return $this->belongsTo('App\ServiceProvider', 'service_provider_id', 'id');
    }

    public function service() //category
    {
        return $this->belongsTo('App\Category', 'category_id');
    }

    public function name() //service name from category table
    {
        return $this->service->name;
    }
}
