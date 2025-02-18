<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'id'           => $this->id,
            'user_id'      => $this->user_id,
            'name'         => $this->name,
            'phone'        => $this->phone,
            'email'        => $this->email,
            'area'         => $this->cluster ? $this->cluster->name : '',
            'address'      => $this->address,
            'photo'        => $this->photo_url,
            'client_type'  => 'client',
            'company_name' => $this->company_name,
            'company_logo' => $this->company_logo,
            'mfs_type'     => $this->mfs_type,
            'mfs_no'       => $this->mfs_no,
            'balance'      => $this->balance,
            'rating'       => $this->ratings,
        ];
    }
}
