<?php

namespace App\Http\Controllers\Promo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Promocode;
use App\PromoUser;
use App\User;
use Carbon\Carbon;
use App\Http\Resources\PromoCodeResource;

class PromoUserController extends Controller
{
    public function index()
    {
        $auth_user = auth()->user();
        $promo_codes = PromoUser::where(['user_id' => $auth_user->id, 'uses_status' => 0])->orderBy('id', 'desc')->get();
        if(!empty($promo_codes))
        {
            return response()->json(PromoCodeResource::collection($promo_codes));
        }
        return response()->json(NULL);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'promo_code' => 'required',
        ]);

        $code = $data['promo_code'];
        $auth_user = auth()->user();
        
        if($this->check_exists_promo_code( $code ))
        {
            $promocode = $this->check_exists_user_promocode( $auth_user->phone, $code );
            if(!empty($promocode))
            {
                return response()->json(['message' => 'You have successfully save this code']);
            }
            return response()->json(['message' => 'Promo code save successfull']);
        }
        return response()->json(['message' => 'Promo code is not valid']);
    }

    public function apply(Request $request)
    {
        $data = $request->validate([
            'promo_code' => 'required',
            'phone' => 'required',
            'amount' => 'required',
        ]);

        $code = $data['promo_code'];
        $phone = $data['phone'];
        $amount = $data['amount'];

        if(self::check_exists_promo_code( $code ))
        {
            $promocode = self::check_exists_user_promocode( $phone, $code );
            if($promocode->uses_count >= $promocode->promo->uses_count)
            {
                return response()->json(['message' => 'You have already use this code'], 400);
            }
            $promocode['amount'] = $amount;
            return response()->json(new PromoCodeResource($promocode));
        }
        return response()->json(['message' => 'Promo code is not valid'], 400);
    }

    public static function applyPromocode($code, $phone, $amount)
    {
        if(self::check_exists_promo_code( $code ))
        {
            $promocode = self::check_exists_user_promocode( $phone, $code );
            if($promocode->uses_count >= $promocode->promo->uses_count)
            {
                return 0;
            }
            return $promocode->promoCodeOrderDiscount( $phone, $code, $amount );
        }
        return 0;
    }

    public static function check_exists_promo_code( $code )
    {
        return Promocode::where(['promocode' => $code, 'status' => 1])->where('validity_date', '>=', Carbon::now()->toDateString())->exists();
    }

    public static function check_exists_user_promocode( $phone, $code )
    {
        $find_user_id = User::where(['phone' => $phone])->first();
        $user_id = (!empty($find_user_id)) ? $find_user_id->id : 0;
        $promocode = PromoUser::where(['user_id' => $user_id, 'phone' => $phone, 'promocode' => $code])->first();
        if(empty($promocode))
        {
            return PromoUser::create([
                'user_id' => $user_id,
                'phone' => $phone,
                'promocode' => $code,
                'uses_count' => 0,
                'uses_status' => 0,
            ]);
        }
        return $promocode;
    }
}
