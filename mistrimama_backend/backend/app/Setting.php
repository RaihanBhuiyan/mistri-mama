<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['name', 'value'];

    // schedule_charge
    public function getScheduleChargeAttribute()
    {
        return $this->where('name', 'schedule_charge')->first()->value;
    }

    // area_charge
    public function getAreaChargeAttribute()
    {
        return $this->where('name', 'area_charge')->first()->value;
    }

    // office_start_time
    public function getOfficeStartTimeAttribute()
    {
        return $this->where('name', 'office_start_time')->first()->value;
    }

    // office_end_time
    public function getOfficeEndTimeAttribute()
    {
        return $this->where('name', 'office_end_time')->first()->value;
    }

    // out_side_are_id
    public function getOutSideAreaIdAttribute()
    {
        return $this->where('name', 'outside_area_id')->first()->value;
    }
    
    // withdrawable_limit
    public function getWithdrawableLimitAttribute()
    {
        return $this->where('name', 'service_provider_withdrawable_limit')->first()->value;
    }
    // refer
    public function getReferAttribute()
    {
        return $this->where('name', 'refer')->first()->value;
    }
}
