<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'id'          => $this->id,
            'name'        => $this->name,
            'name_bn'        => $this->name_bn,
            'slug'        => $this->slug,
            'thumb'       => $this->thumb_url,
            'icon'        => $this->icon_url,
            'opt_image'   => $this->opt_image_url,
            'description' => $this->description,
            'description_bn' => $this->description_bn,
            'benifits'    => $this->benifits,
            'benifits_bn'    => $this->benifits_bn,
            'services'    => ServiceResource::collection($this->services),
            'serviceBits' => $this->benifits,
            'feature_bit' => (!empty($this->relFeatureBit)) ? new ServiceBitResource($this->relFeatureBit->serviceBits) : NULL,
            'feature_image' => (!empty($this->relFeatureBit)) ? $this->relFeatureBit->features_image_url : NULL,
        ];
    }
}
