<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuickOrder extends Model
{
    protected $guarded = [];

    protected $fillable = [
        'request_service', 'name', 'phone', 'address', 'comments', 'date', 'time'
    ];
}
