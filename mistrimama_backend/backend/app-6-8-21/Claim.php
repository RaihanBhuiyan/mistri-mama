<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = ['claim_by', 'claims', 'type'];

    public function relUser()
    {
        return $this->belongsTo('App\User', 'claim_by', 'id');
    }
}
