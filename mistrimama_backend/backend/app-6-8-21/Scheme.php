<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scheme extends Model
{
    protected $fillable = [
        'service_provider_id', 'count_jobs', 'income', 'target', 'bonus', 'start_date', 'end_date', 'updated_at', 'created_at'
    ];
}
