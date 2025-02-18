<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $mrp_price = $this->serviceBits->min('mrp_price');
        $price = $this->serviceBits->min('price');

        $discount_percentage = $price;
        if($mrp_price > 0)
        {
            $discount_percentage = round((($mrp_price - $price)/$mrp_price)*100);
        }

        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'name_bn'     => $this->name_bn,
            'slug'        => $this->slug,
            'thumb'       => $this->thumb_url,
            'description' => $this->description,
            'description_bn' => $this->description_bn,
            'serviceBits' => ServiceBitResource::collection($this->serviceBits),
            'minimum_price'    => $price,
            'minimum_mrp_price'    => $mrp_price,
            'discount_percentage'    => $discount_percentage,
        ];

    }
}
