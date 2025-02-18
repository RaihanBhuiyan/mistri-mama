<?php

namespace App;

use App\Http\Controllers\Account\AccountController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\DB;
use App\RechargeRequest;
use App\WithdrawRequest;
use App\OrderDetail;
use App\Account;
use App\Service;
use App\Setting;
use Carbon\Carbon;

class ServiceProvider extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use SoftDeletes;

    public $with = ['services', 'comrades', 'user'];

    //
    protected $fillable = [
        'user_id',
        'sp_code',
        'shop_name',
        'name',
        'phone',
        'email',
        'address',
        'mfs_type',
        'mfs_no',
        'photo',
        'others_doc',
        'alt_phone',
        'status',
        'type',
        'category',
        'addedBy',
        'rating'
    ];

    protected  $appends = ['commission'];

    public function services()
    {
        return $this->hasMany('App\ServiceProviderService');
    }

    public function comrades()
    {
        return $this->hasMany('App\Comrade');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function orderSystem()
    {
        return $this->hasMany('App\OrderSystem');
    }

    public function oderDetail()
    {
        return $this->hasMany('App\OrderDetail');
    }

    public function cluster() //zone
    {
        return $this->hasMany('App\ServiceProviderCluster');
    }

    public function zone()
    {
        return $this->hasMany('App\ServiceProviderZone');
    }

    public function division()
    {
        return $this->hasMany('App\ServiceProviderDivision');
    }

    public function service()
    {
        return $this->hasMany('App\ServiceProviderService');
    }

    public function time()
    {
        return $this->hasMany('App\ServiceProviderTime');
    }

    public function media()
    {
        return $this->hasMany('App\Media', 'user_id', 'user_id')->orderBy('type', 'asc');
    }

    public function lastApprovedMedia()
    {
        return $this->hasMany('App\Media', 'user_id', 'user_id')->where('status', 'approve')->onlyTrashed()->orderBy('id', 'desc');
    }

    public function relCategoryUpgradeRequst()
    {
        return $this->hasOne('App\ServiceProviderCategoryUpdateHistory', 'user_id', 'user_id')->orderBy('id', 'desc');
    }

    // photo_url
    public function getPhotoUrlAttribute()
    {
        return !empty($this->photo) ? env('APP_URL').'/upload/sp/'. $this->photo : env('APP_URL').'/upload/'.'black-thumbnail.png';
    }

    // nid_no
    public function getNidNoAttribute()
    {
        return (!empty(json_decode($this->others_doc)->nid_no)) ? json_decode($this->others_doc)->nid_no : NULL;
    }

    // trade_lic_no
    public function getTradeLicNoAttribute()
    {
        return (!empty(json_decode($this->others_doc)->trade_lic_no)) ? json_decode($this->others_doc)->trade_lic_no : NULL;
    }

    // tin_no
    public function getTinNoAttribute()
    {
        return (!empty(json_decode($this->others_doc)->tin_no)) ? json_decode($this->others_doc)->tin_no : NULL;
    }

    // balance
    public function getBalanceAttribute()
    {
        return AccountController::balance($this->user_id);
    }

    // withdrawable_balance
    public function getWithdrawableBalanceAttribute()
    {
        $withdrawable_limit = (int) Setting::first()->withdrawable_limit;
        $balance = AccountController::balance($this->user_id);
        if($balance > $withdrawable_limit)
        {
            return ($balance - $withdrawable_limit);
        }
        return 0;
    }

    //no_of_active_comrade
    public function getNoOfActiveComradeAttribute()
    {
        return $this->comrades->where('status', 1)->where('approve', 1)->count();
    }
    
    // total_job_done
    public function getTotalJobDoneAttribute()
    {
        return $this->oderDetail->where('state', 4)->count();
    }

    // total_job_running
    public function getTotalJobRunningAttribute()
    {
        return $this->oderDetail->where('status', '>=', 3)->where('status', '<=', 4)->count();
    }

    // total_job_waiting
    public function getTotalJobWaitingAttribute()
    {
        return $this->oderDetail->where('status', 2)->count();
    }

    // ratings
    public function getRatingsAttribute()
    {
        return round(($this->rating / $this->total_rating_order), 1);
    }


    // commission
    public function getCommissionAttribute()
    {
        return 0;
    }

    // last_recharge
    public function getLastRechargeAttribute()
    {
        return RechargeRequest::where('user_id', $this->user_id)->where('status', 1)->orderBy('id', 'desc')->first();
    }

    // last_withdraw
    public function getLastWithdrawAttribute()
    {
        return WithdrawRequest::where('user_id', $this->user_id)->where('status', 1)->orderBy('id', 'desc')->first();
    }

    // last_order
    public function getLastOrderAttribute()
    {
        return  OrderDetail::where('service_provider_id', $this->id)->where('state', 4)->orderBy('id', 'desc')->first();
    }

    // todays_income
    public function getTodaysIncomeAttribute()
    {
        $total_price = OrderDetail::where('service_provider_id', $this->id)->where('state', 4)->whereDate('finish_time', Carbon::today()->toDateString())->get()->sum('service_provider_income');
        return $total_price;
    }
 
    // yesterdays_income
    public function getYesterdaysIncomeAttribute()
    {
        return  Account::where('user_id', $this->user_id)->where('created_at', 'like', '%' . date('Y-m-d', strtotime("-1 days")) . '%')->where('ref', 'order')->whereIn('status', ['income', 'credit'])->sum('amount');
    }

    // this_month_income
    public function getThisMonthIncomeAttribute()
    {
        // return  Account::where('user_id', $this->user_id)->whereMonth('created_at', Carbon::now()->month)->where('ref', 'order')->whereIn('status', ['income', 'credit'])->sum('amount');
        $total_price = OrderDetail::where('service_provider_id', $this->id)->where('state', 4)->whereMonth('finish_time', Carbon::now()->month)->get()->sum('service_provider_income');
        return $total_price;
    }

    // this_week_income
    public function getThisWeekIncomeAttribute()
    {
        $this_week_start = Carbon::now()->startOfWeek()->format('Y-m-d');
        $this_week_end = Carbon::now()->endOfWeek()->format('Y-m-d');
        return  Account::where('user_id', $this->user_id)->whereBetween('created_at', [$this_week_start, $this_week_end])->where('ref', 'order')->whereIn('status', ['income', 'credit'])->sum('amount');
    }

    // last_month_income
    public function getLastMonthIncomeAttribute()
    {
        // return  Account::where('user_id', $this->user_id)->whereMonth('created_at', Carbon::now()->subMonth()->month)->where('ref', 'order')->whereIn('status', ['income', 'credit'])->sum('amount');
        $total_price = OrderDetail::where('service_provider_id', $this->id)->where('state', 4)->whereMonth('finish_time', Carbon::now()->subMonth()->month)->get()->sum('service_provider_income');
        return $total_price;
    }

    // total_self_order
    public function getTotalSelfOrderAttribute()
    {
        return OrderDetail::where('service_provider_id', $this->id)->whereIn('order_from', ['esp', 'fsp', 'comrade'])->where('state', 4)->count();
    }

    // total_self_order_income
    public function getTotalSelfOrderIncomeAttribute()
    {
        $total_price = OrderDetail::where('service_provider_id', $this->id)->where('state', 4)->whereIn('order_from', ['esp', 'fsp', 'comrade'])->get()->sum('service_provider_income');
        return $total_price;
    }

    // total_mistrimama_order
    public function getTotalMistrimamaOrderAttribute()
    {
        return OrderDetail::where('service_provider_id', $this->id)->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])->where('state', 4)->count();
    }

    // total_mistrimama_order_income
    public function getTotalMistrimamaOrderIncomeAttribute()
    {
        $total_price = OrderDetail::where('service_provider_id', $this->id)->where('state', 4)->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])->get()->sum('service_provider_income');
        return $total_price;
    }
    //total_mistrimama_order_price
    public function getTotalMistrimamaOrderPriceAttribute()
    {
        $total_price = OrderDetail::where('service_provider_id', $this->id)->where('state', 4)->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])->get()->sum('grant_total');
        return $total_price ;
    }


    // this_month_self_job
    public function getThisMonthSelfJobAttribute()
    {
        return OrderDetail::where('service_provider_id', $this->id)->whereIn('order_from', ['esp', 'fsp', 'comrade'])->where('state', 4)->whereMonth('finish_time', Carbon::now()->month)->count();
    }


    // this_month_self_income
    public function getThisMonthSelfIncomeAttribute()
    {
        $total_price = OrderDetail::where('service_provider_id', $this->id)->whereIn('order_from', ['esp', 'fsp', 'comrade'])->where('state', 4)->whereMonth('finish_time', Carbon::now()->month)->get()->sum('service_provider_income');
        return $total_price;
    }

    // this_month_mistrimama_job
    public function getThisMonthMistrimamaJobAttribute()
    {
        return OrderDetail::where('service_provider_id', $this->id)->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])->where('state', 4)->whereMonth('finish_time', Carbon::now()->month)->count();
    }

    // this_month_mistrimama_income
    public function getThisMonthMistrimamaIncomeAttribute()
    {
        $total_price = OrderDetail::where('service_provider_id', $this->id)->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])->where('state', 4)->whereMonth('finish_time', Carbon::now()->month)->get()->sum('service_provider_income');
        return $total_price;
    }

    // total_avail_sub_service
    public function getTotalAvailSubServiceAttribute()
    {
        $cat = $this->services->pluck('category_id')->toArray();
        return Service::whereIn('category_id', $cat)->count();
    }


        // this_mistrimama_emergency_hour_job
    public function getThisMistrimamaEmergencyHourJobAttribute()
    {
        $job = OrderDetail::where('service_provider_id', $this->id)->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])->where('emergency_charge', '>', '0.00')->where('state', 4)->get()->count();
        return $job;
    }
        // this_self_emergency_hour_job
    public function getThisSelfEmergencyHourJobAttribute()
    {
        $job = OrderDetail::where('service_provider_id', $this->id)->whereIn('order_from', ['esp', 'fsp', 'comrade'])->where('emergency_charge', '>', '0.00')->where('state', 4)->get()->count();
        return $job;
    }

    // this_mistrimama_emergency_hour_job_charge
    public function getThisMistrimamaEmergencyHourJobChargeAttribute()
    {
        $job = OrderDetail::where('service_provider_id', $this->id)->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])->where('emergency_charge', '>', '0.00')->where('state', 4)->get()->sum('emergency_charge');
        return $job;
    }
        // this_self_emergency_hour_job_charge
    public function getThisSelfEmergencyHourJobChargeAttribute()
    {
        $job = OrderDetail::where('service_provider_id', $this->id)->whereIn('order_from', ['esp', 'fsp', 'comrade'])->where('emergency_charge', '>', '0.00')->where('state', 4)->get()->sum('emergency_charge');
        return $job;
    }


    
        // this_mistrimama_outsidedmc_job
    public function getThisMistrimamaOutsidedmcJobAttribute()
    {
        $job = OrderDetail::where('service_provider_id', $this->id)->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])->where('outside_charge', '>', '0.00')->where('state', 4)->get()->count();
        return $job;
    }
        // this_Self_outsidedmc_job
    public function getThisSelfOutsidedmcJobAttribute()
    {
        $job = OrderDetail::where('service_provider_id', $this->id)->whereIn('order_from', ['esp', 'fsp', 'comrade'])->where('outside_charge', '>', '0.00')->where('state', 4)->get()->count();
        return $job;
    }

    // this_mistrimama_outsidedmc_job_charge
    public function getThisMistrimamaOutsidedmcJobChargeAttribute()
    {
        $job = OrderDetail::where('service_provider_id', $this->id)->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])->where('outside_charge', '>', '0.00')->where('state', 4)->get()->sum('outside_charge');
        return $job;
    }
        // this_Self_outsidedmc_job_charge
    public function getThisSelfOutsidedmcJobChargeAttribute()
    {
        $job = OrderDetail::where('service_provider_id', $this->id)->whereIn('order_from', ['esp', 'fsp', 'comrade'])->where('outside_charge', '>', '0.00')->where('state', 4)->get()->sum('outside_charge');
        return $job;
    }
    
    // this_mistrimama_cash_collection_job
    public function getThisMistrimamaCashCollectionJobAttribute()
    {
        $job = OrderDetail::where('service_provider_id', $this->id)->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])->where('pay_type', '<=', 1)->where('state', 4)->get()->count();
        return $job;
    }
    
    // this_self_cash_collection_job
    public function getThisSelfCashCollectionJobAttribute()
    {
        $job = OrderDetail::where('service_provider_id', $this->id)->whereIn('order_from', ['esp', 'fsp', 'comrade'])->where('pay_type', '<=', 1)->where('state', 4)->get()->count();
        return $job;
    }
 
    // this_mistrimama_digital_payment_job
    public function getThisMistrimamaDigitalPaymentJobAttribute()
    {
        $job = OrderDetail::where('service_provider_id', $this->id)->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])->where('pay_type', '>=', 2)->where('state', 4)->get()->count();
        return $job;
    }
    
    // this_self_digital_payment_job
    public function getThisSelfDigitalPaymentJobAttribute()
    {
        $job = OrderDetail::where('service_provider_id', $this->id)->whereIn('order_from', ['esp', 'fsp', 'comrade'])->where('pay_type', '>=', 2)->where('state', 4)->get()->count();
        return $job;
    }


    // mistrimama_todays_job
    public function getMistrimamaTodaysJobAttribute()
    {
        return OrderDetail::where('service_provider_id', $this->id)->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])->where('state', 4)->whereDate('finish_time', Carbon::today()->toDateString())->count();
    }

    // mistrimama_todays_income
    public function getMistrimamaTodaysIncomeAttribute()
    {
        return OrderDetail::where('service_provider_id', $this->id)->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])->where('state', 4)->whereDate('finish_time', Carbon::today()->toDateString())->get()->sum('grant_total');
    }
    
    // self_todays_job
    public function getSelfTodaysJobAttribute()
    {
        return OrderDetail::where('service_provider_id', $this->id)->whereIn('order_from', ['esp', 'fsp', 'comrade'])->where('state', 4)->whereDate('finish_time', Carbon::today()->toDateString())->count();
    }

    // self_todays_income
    public function getSelfTodaysIncomeAttribute()
    {
        return OrderDetail::where('service_provider_id', $this->id)->whereIn('order_from', ['esp', 'fsp', 'comrade'])->where('state', 4)->whereDate('finish_time', Carbon::today()->toDateString())->get()->sum('grant_total');
    }
    
    // mistrimama_current_week_total_job 
    public function getMistrimamaCurrentWeekTotalJobAttribute()
    {
        $this_week_start = Carbon::now()->startOfWeek()->format('Y-m-d');
        $this_week_end = Carbon::now()->endOfWeek()->format('Y-m-d');
        return OrderDetail::where('service_provider_id', $this->id)->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])->where('state', 4)->whereDate('finish_time', '>=', $this_week_start)->whereDate('finish_time', '<=', $this_week_end)->count();
    }
    
    // mistrimama_current_week_total_income 
    public function getMistrimamaCurrentWeekTotalIncomeAttribute()
    { 
        $this_week_start = Carbon::now()->startOfWeek()->format('Y-m-d');
        $this_week_end = Carbon::now()->endOfWeek()->format('Y-m-d');
        return OrderDetail::where('service_provider_id', $this->id)->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])->where('state', 4)->whereDate('finish_time', '>=', $this_week_start)->whereDate('finish_time', '<=', $this_week_end)->get()->sum('service_provider_income');
    }
    
    // self_current_week_total_job 
    public function getSelfCurrentWeekTotalJobAttribute()
    {

        $this_week_start = Carbon::now()->startOfWeek()->format('Y-m-d');
        $this_week_end = Carbon::now()->endOfWeek()->format('Y-m-d');
        return OrderDetail::where('service_provider_id', $this->id)->whereIn('order_from', ['esp', 'fsp', 'comrade'])->where('state', 4)->whereDate('finish_time', '>=', $this_week_start)->whereDate('finish_time', '<=', $this_week_end)->count();
    }
    
    // self_current_week_total_income 
    public function getSelfCurrentWeekTotalIncomeAttribute()
    { 
        $this_week_start = Carbon::now()->startOfWeek()->format('Y-m-d');
        $this_week_end = Carbon::now()->endOfWeek()->format('Y-m-d');
        return OrderDetail::where('service_provider_id', $this->id)->whereIn('order_from', ['esp', 'fsp', 'comrade'])->where('state', 4)->whereDate('finish_time', '>=', $this_week_start)->whereDate('finish_time', '<=', $this_week_end)->get()->sum('service_provider_income');
    }
}
