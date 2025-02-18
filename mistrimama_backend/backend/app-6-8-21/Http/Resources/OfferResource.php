<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = [
            'id' => $this->id,
            'title' => $this->title,
            'offers_for' => $this->offers_for,
            'description' => $this->description,
            'expire_date' => $this->expire_date,
            'offer_image' => $this->offer_image_url,
            'offers_type' => $this->offers_type,
        ];
        if($this->offers_type == 'quick_order_offer')
        {
            $array['find_services'] = $this->alt_description;
        }
        if($this->offers_type == 'discount_offer')
        {
            $array['discount_offer'] = json_decode($this->alt_description);
        }

        return $array;
    }
}
