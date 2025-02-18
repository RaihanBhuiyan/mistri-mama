<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public $preserveKeys = true;
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
            'total_service_taken' => $this->total_service_taken,
            'date' => $this->date,
            'time' => $this->time,
            'status' => $this->status,
            'orders_place_time' => $this->orders_place_time,
            'order_for' => $this->order_for,
            'order_from' => $this->order_from,
            'pay_type' => $this->pay_type,
            'comments' => $this->comments,
            'cancel_note' => $this->cancel_note,
            'items' => OrderItemResource::collection($this->orderItems),
            'service_provider_id' => $this->service_provider_id,
            'service_provider_name' => $this->serviceProvider->name,
            'service_provider_phone' => $this->serviceProvider->phone,
            'service_provider_address' => $this->serviceProvider->address,
            'comrade_id' => $this->comrade_id,
            'comrade' => (!empty($this->comrade)) ? $this->comrade : $this->serviceProvider,
            'state' => $this->state,
            'total_unit' => $this->total_unit,
            'total_unit_price' => $this->total_unit_price,
            'total_aditional_unit' => $this->total_aditional_unit,
            'total_aditional_unit_price' => $this->total_aditional_unit_price,
            'total_price' => $this->total_price,
            'emergency_charge' => round($this->emergency_charge, 0),
            'outside_charge' => round($this->outside_charge, 0),
            'discount' => round($this->discount, 0),
            'commission' => $this->commission,
            'client_rating' => (int) $this->client_rating,
            'grant_total' => $this->grant_total,
            'service_provider_income' => $this->service_provider_income,
            'reduce_type' => $this->reduce_type,
            'reduce_amount' => $this->reduce_amount,
        ];
    }
}
