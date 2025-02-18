<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = "order_details";

    public function serviceProvider()
    {
        return $this->belongsTo('App\ServiceProvider', 'service_provider_id', 'id');
    }
    public function comrade()
    {
        return $this->belongsTo('App\Comrade', 'comrade_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    public function client()
    {
        return $this->belongsTo('App\Client', 'user_id', 'user_id');
    }
    public function orderItems()
    {
        return $this->hasMany('App\OrderItem', 'order_id');
    }
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function relUserFeedbacks()
    {
        return $this->hasMany('App\FeedbackQuestion', 'category_id', 'category_id')->where('type', 'user')->take(5);
    }

    public function relServiceProviderFeedbacks()
    {
        return $this->hasMany('App\FeedbackQuestion', 'category_id', 'category_id')->where('type', 'sp')->take(5);
    }

    // total_commission
    public function getTotalCommissionAttribute()
    {
        return round($this->orderItems->where('status', 1)->sum('total_commission'), 0);        
    }

    // client_rating
    public function getClientRatingAttribute()
    {
        if( ($this->user_id != 0) && (!empty($this->user->client)) )
        { 
            return round(($this->user->client->rating / $this->user->client->total_rating_order), 2);
        }
        return 0.00;
    }

    // service_proviter_rating
    public function getServiceProviderRatingAttribute()
    {
        return round(($this->user->serviceProvider->rating / $this->user->serviceProvider->total_rating_order), 2);
    }

    // total_unit
    public function getTotalUnitAttribute()
    {
        if($this->status <=3)
        {
            return $this->orderItems->sum('quantity');
        }
        return $this->orderItems->where('status', 1)->sum('quantity');
    }

    // total_unit_price
    public function getTotalUnitPriceAttribute()
    {
        if($this->status <=3)
        {
            return $this->orderItems->sum('price');
        }
        return $this->orderItems->where('status', 1)->sum('price');
    }

    // total_aditional_unit
    public function getTotalAditionalUnitAttribute()
    {
        if($this->status <=3)
        {
            return $this->orderItems->sum('total_aditional_unit');
        }
        return $this->orderItems->where('status', 1)->sum('total_aditional_unit');
    }

    // total_aditional_unit_price
    public function getTotalAditionalUnitPriceAttribute()
    {
        if($this->status <=3)
        {
            return $this->orderItems->sum('total_aditional_unit_price');
        }
        return $this->orderItems->where('status', 1)->sum('total_aditional_unit_price');
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
                $type = "Discount Amount";
            }
            else
            {
                $type = "Supplementary Service Charge";
            }
        }
        return $type;
    }

    // service_provider_income
    public function getServiceProviderIncomeAttribute()
    {
        $total_order_amount = 0;
        $extra_charge = 0;
        if($this->customize_charge == 0.00)
        {
            $extra_charge = ($this->emergency_charge + $this->outside_charge);
            $total_order_amount = $this->total_commission;
        }
        else
        {
            $total_order_amount = $this->customize_charge;
        }
        // $commission_amount = $this->total_commission;

        return round(($total_order_amount + $extra_charge), 0);
    }

    // total_service_taken
    public function getTotalServiceTakenAttribute()
    {
        return $this->where(['status' => 5, 'user_id' => $this->user_id])->count();
    }

}
