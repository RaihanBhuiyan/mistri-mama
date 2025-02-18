<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = ['risk_factor_id', 'comment','order_id', 'claim_by', 'claims', 'type'];

    public function relUser()
    {
        return $this->belongsTo('App\User', 'claim_by', 'id');
    }

    public function relOrder()
    {
        return $this->belongsTo('App\Order', 'order_id', 'id');
    }

    public function relRiskFactor()
    {
        return $this->belongsTo('App\RiskFactor', 'risk_factor_id', 'id');
    }

    public function getClaimsListAttribute()
    {
        $json_decode_claims = json_decode($this->claims); 
        if($json_decode_claims){
            $string = "<ul><li>";
            $string .= str_replace(",", "</li><li>", implode(",", $json_decode_claims));
            $string .= "</li></ul>";
            return $string;
        }else{
            $string='';
            return $string;
        }
    }
}
