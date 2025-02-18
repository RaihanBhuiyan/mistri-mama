<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'order_id' => $this->order_id,
            'category_id' => (!empty($this->service)) ? $this->service->category->id : NULL,
            'category_name' => (!empty($this->service)) ? $this->service->category->name : "Custom Services ",
            'service_id' => $this->service_id,
            'service_name' => $this->service_name,
            'service_bit_id' => $this->service_bit_id,
            'service_bit_name' => $this->service_bit_name,
            'price' => $this->price,
            'additional_price' => $this->additional_price,
            'commission' => $this->commission,
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'status' => $this->status,
        ];
    }
}
