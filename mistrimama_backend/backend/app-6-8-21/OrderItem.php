<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class OrderItem extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use SoftDeletes;

    protected $fillable = [
        'order_id', 'service_id', 'service_name', 'service_bit_id', 'service_bit_name', 'price', 'additional_price', 'commission', 'quantity', 'total_price', 'status', 'type'
    ];

    public function service()
    {
        return $this->belongsTo('App\Service', 'service_id', 'id');
    }

    // total_aditional_unit
    public function getTotalAditionalUnitAttribute()
    {
        return $this->quantity;
    }

    // total_aditional_unit_price
    public function getTotalAditionalUnitPriceAttribute()
    {
        return $this->quantity;
    }

    // total_price
    public function getTotalPriceAttribute($value)
    {
        $totalPrice = ($this->price * $this->quantity);
        return round($totalPrice, 2);
    }

    // total_commission
    public function getTotalCommissionAttribute($value)
    {
        $totalCommission = ($this->commission * $this->quantity);
        return round($totalCommission, 2);
    }

}
