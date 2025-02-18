<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PromoUser extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'user_id', 'phone', 'promocode', 'uses_count', 'uses_status',
    ];


    public function promo()
    {
        return $this->belongsTo('App\Promocode', 'promocode', 'promocode');
    }

    public static function promoCodeOrderDiscount( $phone, $code, $amount )
    {
        $discount = 0;
        $promo_user = PromoUser::where(['phone' => $phone, 'promocode' => $code, 'uses_status' => 0])->first();
        if(!empty($promo_user))
        {
            $promo = $promo_user->promo;
            if(!empty($promo))
            {
                $discount = (($amount * $promo->percent / 100) + $promo->cash);

                if($promo->percent > 0)
                {
                    if ($promo->up_to < $discount) {
                        $discount = $promo->up_to;
                    }
                }
                
            }
        }
        return $discount;
    }
}
