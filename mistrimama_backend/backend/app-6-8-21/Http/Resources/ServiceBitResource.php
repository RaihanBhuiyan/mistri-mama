<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceBitResource extends JsonResource
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
            'id'               => $this->id,
            'service_id'       => $this->service_id,
            'service_name'     => $this->service->name,
            'categgory_name'   => $this->category->name,
            'name'             => $this->name,
            'service_name_bn'  => $this->service->name_bn,
            'categgory_name_bn'=> $this->category->name_bn,
            'name_bn'          => $this->name_bn,
            'mrp_price'        => $this->mrp_price,
            'price'            => $this->price,
            'additional_price' => $this->additional_price,
            'unit_type'        => $this->unit_type,
            'brief'            => $this->brief,
            'brief_bn'         => $this->brief_bn,
            'qty'              => 1,
        ];
    }
}
