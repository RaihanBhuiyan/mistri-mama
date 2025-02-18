<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RewardPoint extends Model
{
    protected $fillable = [
        'user_id', 'rp', 'status', 'details', 'created_at', 'updated_at'
    ];
    
    // total_earn_point
    public function getTotalEarnPointAttribute()
    {
        return $this->where(['status' => 'add'])->sum('rp');
    }
    
    // available_reward_point
    public function getAvailableRewardPointAttribute()
    {
        $rp_add = $this->where(['user_id' => $this->user_id, 'status' => 'add'])->sum('rp');
        $rp_remove = $this->where(['user_id' => $this->user_id, 'status' => 'remove'])->sum('rp');
        return ($rp_add - $rp_remove);
    }

    // available_reward_balance
    public function getAvailableRewardBalanceAttribute()
    {
        return round(($this->available_reward_point/3), 2);
    }
}
