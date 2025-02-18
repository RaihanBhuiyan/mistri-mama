<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name'         => $this->name,
            'phone'        => $this->phone,
            'email'        => $this->email,
            'area'         => ($this->getRoleNames()->first() == 'client') ? $this->client->cluster : null,
            'address'      => $this->address(),
            'photo'        => $this->photo(),
            'ref_code'     => $this->ref_code,
            'ref_url'      => str_replace("/backend", "", env('APP_URL')).'/order/refer/'.$this->ref_code.'/electrical',
            'status'       => $this->status,
            'type'         => $this->getRoleNames()->first()  ? $this->getRoleNames()->first() : 'user',
            'client_type'  => $this->client ? $this->client->type : 'client',
            'company_name' => $this->client ? $this->client->company_name : '',
            'company_logo' => $this->client ? $this->client->company_logo : '',
            'mfs_type'     => $this->mfsType(),
            'mfs_no'       => $this->mfsNo(),
            'balance'      => 0,
            'client_id'    => $this->client ? $this->client->id : '',
            'service_provider_id'    => $this->serviceProvider ? $this->serviceProvider->id : '',
            'category'    => $this->serviceProvider ? $this->serviceProvider->category : '',
            'comrade_id'    => $this->comrade ? $this->comrade->id : '',
        ];
    }
}
