<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BecomeServiceProvider extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name', 'phone', 'email', 'area', 'service_categoris', 'other_service'
    ];

    public function zone()
    {
        return $this->belongsTo('App\Cluster', 'area', 'id');
    }

}
