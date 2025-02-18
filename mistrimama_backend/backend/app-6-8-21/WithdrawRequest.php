<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Carbon\Carbon;

class WithdrawRequest extends Model implements Auditable
{
    protected $fillable = ['user_id', 'mfs', 'mfs_number', 'amount', 'status', 'type', 'remarks'];
    protected $dates = ['created_at', 'updated_at'];

    use \OwenIt\Auditing\Auditable;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function client()
    {
        return $this->hasOne('App\Client', 'user_id', 'user_id');
    }

    public function accountWithdraw()
    {
        return $this->hasOne('App\Account', 'ref_key', 'id')->where('ref', 'withdraw');
    }

    public function accountCashOut()
    {
        return $this->hasOne('App\Account', 'ref_key', 'id')->where('ref', 'cash_out');;
    }

    // created_at
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    // updated_at
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
}
