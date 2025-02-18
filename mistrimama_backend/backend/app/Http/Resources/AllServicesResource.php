<?php

namespace App\Http\Resources;

use App\Category;
use App\Service;
use Illuminate\Http\Resources\Json\JsonResource;

class AllServicesResource extends JsonResource
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
            'category_id' => $this->category_id,
            'category_name' => Category::find($this->category_id)->name,
            'category_name_bn' => Category::find($this->category_id)->name_bn,
            'service_id' => $this->service_id,
            'service_name' => Service::find($this->service_id)->name,
            'service_name_bn' => Service::find($this->service_id)->name_bn,
            'name' => $this->name,
            'name_bn' => $this->name_bn,
            'price' => $this->price,
            'service_provider_income' => $this->commission,
            'mistrimama_commission' => ($this->price - $this->commission),
            'additional_price' => $this->additional_price,
            'unit_remarks' => $this->unit_remarks ,
            'unit_remarks' => $this->unit_remarks ,
            'additional_unit_remarks' => $this->additional_unit_remarks,
            'unit_type' => $this->unit_type ,
            'brief' => $this->brief ,
            'created_at' => $this->created_at ,
            'updated_at' => $this->updated_at
        ];
    }
}
