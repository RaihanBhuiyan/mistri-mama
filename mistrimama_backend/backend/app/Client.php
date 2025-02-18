<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Http\Controllers\Account\AccountController;
use App\OrderDetail;
use App\RewardPoint;

class Client extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'area',
        'address',
        'location',
        'photo',
        'mfs_type',
        'mfs_no',
        'type',
        'remarks',
        'company_name',
        'company_logo',
        'rating'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function cluster()
    {
        return $this->belongsTo('App\Cluster', 'area', 'id');
    }

    public function order()
    {
        return $this->hasMany('App\Order', 'user_id', 'user_id');
    }

    public function account()
    {
        return $this->hasMany('App\Account', 'user_id', 'user_id')->where('trx_for', 'client');
    }

    public function relRewardPoint()
    {
        return $this->hasMany('App\RewardPoint', 'user_id', 'user_id');
    }

    public function orderCancel()
    {
        return $this->hasMany('App\Order', 'user_id', 'user_id')->where(['status' => 6]);
    }

    public function mfsNumberHistory()
    {
        return $this->hasMany('App\MfsNumber', 'user_id', 'user_id');
    }

    public function relWithdrawRequest()
    {
        return $this->hasMany('App\WithdrawRequest', 'user_id', 'user_id');
    }
    
    public function popularservice()
    {
        return $this->hasMany('App\Order', 'user_id', 'user_id')->selectRaw('count(*) as popularservice, services.name')
        ->join('order_items','order_items.order_id', 'orders.id')->join('services','order_items.service_id', 'services.id')
        ->groupBy('services.name')
        ->orderBy('popularservice', 'desc');
    }

    // ratings
    public function getRatingsAttribute()
    {
        if($this->rating > 0 &&   $this->total_rating_order ){
            return round(($this->rating / $this->total_rating_order), 2);
        }else{
            return 0 ;
        }
       
    }
    
    // client_total_spent
    public function getClientTotalSpentAttribute()
    {
        return $this->account->where('status', 'debit')->where('ref', 'order')->sum('amount');
    }
    
    // average_client_spent
    public function getAverageClientSpentAttribute()
    {
        $total_complete_order = $this->order->where('status', 5)->count();
        if($this->client_total_spent > 0 && $total_complete_order > 0 )
        {
            return round(($this->client_total_spent/ $total_complete_order), 2);
        }
        return 0;
    }
    
    // total_cashout
    public function getTotalCashoutAttribute()
    {
        return round($this->relWithdrawRequest->where('approve', 1)->sum('amount'));
    }
    
    // total_earn_point
    public function getTotalEarnPointAttribute()
    {
        return $this->relRewardPoint->where('status', 'add')->sum('rp');
    }
    
    // available_reward_point
    public function getAvailableRewardPointAttribute()
    {
        $rp_add = $this->relRewardPoint->where('status', 'add')->sum('rp');
        $rp_remove = $this->relRewardPoint->where('status', 'remove')->sum('rp');
        return ($rp_add - $rp_remove);
    }

    // available_reward_balance
    public function getAvailableRewardBalanceAttribute()
    {
        return round(($this->available_reward_point/3), 0);
    }

    // total_ref_order
    public function getTotalRefOrderAttribute()
    {
        return OrderDetail::where('ref_code', $this->user->ref_code)->count();
    }

    // photo_url
    public function getPhotoUrlAttribute()
    {
       
        return  !empty($this->photo) ? env('APP_URL').'/upload/client/'. $this->photo : env('APP_URL').'/upload/'.'black-thumbnail.png';
    }

    // company_logo
    public function getCompanyLogoAttribute($val)
    {
        return asset('/') . $val;
    }

    // balance
    public function getBalanceAttribute()
    {
        return AccountController::balance($this->user_id);
    }
}

/*guest user */
