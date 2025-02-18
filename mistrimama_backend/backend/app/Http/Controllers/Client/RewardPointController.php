<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Account\AccountController;
use App\RewardPoint;
use App\Account;
use App\Client;
use App\WithdrawRequest;
use Carbon\Carbon;
use Hash;

class RewardPointController extends Controller
{
    public function currentRewardPointCount()
    {
        $reward_point = RewardPoint::where(['user_id' => auth()->user()->id])->first();
        $client = Client::where(['user_id' => auth()->user()->id])->first();
        if(!empty($reward_point))
        {
            return [
                'available_reward_point' => round($reward_point->available_reward_point, 2),
                'available_reward_balance' => round($reward_point->available_reward_balance, 2)
            ];
        }
        return [
            'available_reward_point' => round(0, 2),
            'available_reward_balance' => round(0, 2)
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reward_point = RewardPoint::where(['user_id' => auth()->user()->id])->orderBy('id', 'asc')->get();
        return response()->json($reward_point);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function redeemPoint($redeem_point)
    {
        $reward_point_table = self::rewardPointRemove($redeem_point, auth()->user()->id);
        //AccountController::rewardCredit($reward_point_table, auth()->user()->id);
    }
    
    public static function rewardPointRemove($redeem_point, $user_id)
    {
        $amount = round(($redeem_point/3), 2);

        $rp = new RewardPoint();
        
        $rp->user_id = $user_id;
        $rp->rp = $redeem_point;
        $rp->status = 'remove';
        $rp->details = "Reward Ponit ". $redeem_point ." Convert to BDT ".$amount."";
        $rp->save();
        $rp->amount = $amount;
        return $rp;
    }
}
