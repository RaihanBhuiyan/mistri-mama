<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PromoCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'phone' => $this->phone,
            'code' => $this->promocode,
            'have_used' => $this->uses_count,
            'discount' => $this->promoCodeOrderDiscount( $this->phone, $this->promocode, $this->amount ),
            'promo_code' => [
                'cash' => (!empty($this->promo->cash)) ? $this->promo->cash : 0,
                'up_to' => (!empty($this->promo->up_to)) ? $this->promo->up_to : 0,
                'percentage' => (!empty($this->promo->percent)) ? $this->promo->percent : 0,
                'validity_date' => $this->promo->validity_date,
                'be_used' => $this->promo->uses_count,
                'details' => $this->promo->details,
            ],
            'is_expired' => (($this->promo->validity_date < Carbon::now()->toDateString()) || ($this->promo->uses_count == $this->uses_count)) ? true : false,
        ];
    }
}
