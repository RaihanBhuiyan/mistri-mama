<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RiskFactor extends Model
{
    protected $fillable = [
        'title', 'particulars', 'type', 'created_at', 'updated_at'
    ];
}
