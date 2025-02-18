<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;


class Order extends Model implements Auditable
{

    // 0 => Order Placed , 
    // 1 => Order accepted , 
    // 2 => Order allowcated to SP, 
    // 3 => Comrade start working on service 
    // 4 => Order Finished (Served) wait for payment , 
    // 5 => Order Payment done (order completely finished) ,
    // 6 => Order Cancel by user (orderar)
    // 7 => Fraud Order

    use \OwenIt\Auditing\Auditable;

    use SoftDeletes;

    protected $fillable = [
        'order_no',
        'category_id',
        'category_name',
        'user_id',
        'date',
        'time',
        'name',
        'phone',
        'area',
        'address',
        'location',
        'emergency_charge',
        'outside_charge',
        'discount',
        'status',
        'pay_type',
        'accept_time',
        'allowcate_time',
        'finish_time',
        'cancel_time',
        'pay_status',
        'comments',
        'cancel_note',
        'order_for',
        'order_from',
        'ref_code',
        'promocode',
    ];

    public function orderItems()
    {
        return $this->hasMany('App\OrderItem');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function orderSystem()
    {
        return $this->hasOne('App\OrderSystem');
    }

    public function orderReject()
    {
        return $this->hasMany('App\OrderReject');
    }

    // status_txt
    public function getStatusTxtAttribute()
    {
        $array = [
            'Order Placed',
            'Order accepted',
            'Order allowcated to Service Provider',
            'Comrade start working on service',
            'Order Finished (Served) wait for payment',
            'Order Payment done (order completely finished) ',
            'Order Cancel',
            'Fraud Order'
        ];
        return $array[$this->status];
    }

    // total_unit
    public function getTotalUnitAttribute()
    {
        return $this->orderItems->sum('quantity');
    }

    // total_unit_price
    public function getTotalUnitPriceAttribute()
    {
        return $this->orderItems->sum('price');
    }

    // total_aditional_unit
    public function getTotalAditionalUnitAttribute()
    {
        return $this->orderItems->sum('total_aditional_unit');
    }

    // total_aditional_unit_price
    public function getTotalAditionalUnitPriceAttribute()
    {
        return $this->orderItems->sum('total_aditional_unit_price');
    }

    // total_price
    public function getTotalPriceAttribute()
    {
        if($this->status <=3)
        {
            return round($this->orderItems->sum('total_price'), 0);
        }
        return round($this->orderItems->where('status', 1)->sum('total_price'), 0);
    }

    // grant_total
    public function getGrantTotalAttribute()
    {
        $grant_total = (($this->total_price + ($this->emergency_charge + $this->outside_charge)) - $this->discount);
        if($this->customize_charge > 0)
        {
            if($grant_total > $this->customize_charge)
            {
                $grant_total = $this->customize_charge;
            }
            else
            {
                $grant_total = ($grant_total + ($this->customize_charge - $grant_total));
            }
        }
        return round($grant_total, 0);
    }

    // reduce_amount
    public function getReduceAmountAttribute()
    {
        $grant_total = (($this->total_price + ($this->emergency_charge + $this->outside_charge)) - $this->discount);
        $amount = 0;
        if($this->customize_charge > 0)
        {
            if($grant_total > $this->customize_charge)
            {
                $amount = ($grant_total - $this->customize_charge);
            }
            else
            {
                $amount = ($this->customize_charge - $grant_total);
            }
        }
        return round($amount, 0);
    }

    // reduce_type
    public function getReduceTypeAttribute()
    {
        $grant_total = (($this->total_price + ($this->emergency_charge + $this->outside_charge)) - $this->discount);
        $type = NULL;
        if($this->customize_charge > 0)
        {
            if($grant_total > $this->customize_charge)
            {
                $type = "Special Amount";
            }
            else
            {
                $type = "Supplementary Service Charge";
            }
        }
        return $type;
    }
}
