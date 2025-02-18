<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WaitingJobsResource extends JsonResource
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
            'user_id' => $this->user_id,
            'area' => $this->area,
            'category_id' => $this->category_id,
            'category_name' => $this->category_name,
            'time' => $this->time,
            'date' => $this->date,
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'photo' => "https://www.pngitem.com/pimgs/m/78-786501_black-avatar-png-user-icon-png-transparent-png.png",
            'ref_code' => $this->ref_code,
            'status' => $this->status,
            'pay_type' => $this->pay_type,
            'pay_status' => $this->pay_status,
            'order_for' => $this->order_for,
            'order_from' => $this->order_from,
            'orders_place_time' => $this->orders_place_time,
            'accept_time' => $this->accept_time,
            'allowcate_time' => $this->allowcate_time,
            'finish_time' => $this->finish_time,
            'cancel_time' => $this->cancel_time,
            'service_provider_id' => $this->service_provider_id,
            'comrade_id' => $this->comrade_id,
            'comrade' => (!empty($this->comrade)) ? $this->comrade : $this->serviceProvider,
            'state' => $this->state,
            'client_rating' => (int) $this->client_rating,
            'sp_rating' => (int) $this->sp_rating,
            'total_service_taken' => $this->total_service_taken,
            'sp_accept_time' => $this->sp_accept_time,
            'total_price' => $this->total_price,
            'emergency_charge' => $this->emergency_charge,
            'outside_charge' => $this->outside_charge,
            'discount' => $this->discount,
            'items' => OrderItemResource::collection($this->orderItems),
            'reduce_type' => $this->reduce_type,
            'reduce_amount' => $this->reduce_amount,
            'grant_total' => $this->grant_total,
        ];
    }
}
