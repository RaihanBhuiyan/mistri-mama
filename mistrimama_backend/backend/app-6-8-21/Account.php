<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Account extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'trx_for',
        'user_id',
        'amount',
        'trxno',
        'type',
        'heading_type',
        'heading',
        'details',
        'ref',
        'ref_key',
        'status',
        'date'
    ];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function relWithdraw()
    {
        return $this->belongsTo('App\WithdrawRequest', 'ref_key', 'id')->where('type', 'withdraw');
    }

    public function relRechargeRequest()
    {
        return $this->belongsTo('App\RechargeRequest', 'ref_key', 'id');
    }

    // bdt_amount
    public function getBdtAmountAttribute()
    {
        return round($this->amount, 2) . ' BDT';
    }

    // details
    public function getDetailsAttribute($value)
    {
        if($this->ref == 'order')
        {
            return [$value, $this->ref, $this->ref_key];
        }
        return $value;
    }
}
