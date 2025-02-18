<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserOrderHistory extends JsonResource
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
            'date' => $this->date,
            'time' => $this->time,
            'emergency_charge' => round($this->emergency_charge, 0),
            'outside_charge' => round($this->outside_charge, 0),
            'discount' => round($this->discount, 0),
            'ref_code' => $this->ref_code,
            'status' => $this->status,
            'pay_type' => $this->pay_type,
            'comments' => $this->comments,
            'cancel_note' => $this->cancel_note,
            'pay_status' => $this->pay_status,
            'order_for' => $this->order_for,
            'order_from' => $this->order_from,
            'orders_place_time' => $this->created_at->format('d M Y h:m:s a'),
            'accept_time' => $this->accept_time,
            'allowcate_time' => $this->allowcate_time,
            'finish_time' => $this->finish_time,
            'cancel_time' => $this->cancel_time,
            'service_provider_name' => (!empty($this->orderSystem->service_provider_id)) ? $this->orderSystem->serviceProvider->name : '',
            'comrade_code' => (!empty($this->orderSystem->comrade_id)) ? $this->orderSystem->comrade->comrade_code : '',
            'comrade_name' => (!empty($this->orderSystem->comrade_id)) ? $this->orderSystem->comrade->name : '',
            'sp_rating' => $this->orderSystem ? $this->orderSystem->sp_rating : '',
            'total_unit' => $this->total_unit,
            'total_unit_price' => $this->total_unit_price,
            'total_aditional_unit' => $this->total_aditional_unit,
            'total_aditional_unit_price' => $this->total_aditional_unit_price,
            'total_price' => round($this->total_price, 0),
            'grant_total' => round((($this->total_price + ($this->emergency_charge + $this->outside_charge)) - $this->discount), 0),
            'order_items' => OrderItemResource::collection($this->orderItems)
        ];
    }
}
