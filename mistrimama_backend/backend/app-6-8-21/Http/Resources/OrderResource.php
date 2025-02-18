<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_no' => $this->order_no,
            'category_id' => $this->category_id,
            'category_name' => $this->category_name,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'phone' => $this->phone,
            'area' => $this->area,
            'address' => $this->address,
            'location' => $this->location,
            'date' => $this->date,
            'time' => $this->time,
            'discount' => $this->discount,
            'status' => $this->status,
            'pay_type' => $this->pay_type,
            'comments' => $this->comments,
            'comrade_id' => (!empty($this->orderSystem)) ? $this->orderSystem->comrade_id : NULL,
            'comrade' => (!empty($this->orderSystem)) ? (!empty($this->orderSystem->comrade)) ? $this->orderSystem->comrade : $this->orderSystem->serviceProvider : NULL,
            'items' => OrderItemResource::collection($this->orderItems),
            'emergency_charge' => $this->emergency_charge,
            'outside_charge' => $this->outside_charge,
            'total_price' => $this->orderItems->sum('total_price'),
            'service_image' => $this->category->thumb,
            'reduce_type' => $this->reduce_type,
            'reduce_amount' => $this->reduce_amount,
            'grant_total' => $this->grant_total,
            'created_at' => (!empty($this->created_at)) ? $this->created_at->format('Y-m-d h:m:s a') : $this->orders_place_time,
        ];
    }
}
