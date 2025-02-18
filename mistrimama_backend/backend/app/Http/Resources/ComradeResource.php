<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ComradeResource extends JsonResource
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
            'user_id' => $this->user_id,
            'service_provider_id' => $this->service_provider_id,
            'comrade_code' => $this->comrade_code,
            'name' => $this->name,
            'phone' => $this->phone,
            'alt_phone' => $this->alt_phone,
            'email' => $this->email,
            'address' => $this->address,
            'photo' => $this->photo,
            'photo_url' => $this->photo_url,
            'nid_no' => $this->nid_no,
            'nid_front_url' => $this->nid_front_url,
            'nid_back_url' => $this->nid_back_url,
            'services' => json_decode($this->services),
            'status' => $this->status,
            'joining_date' => $this->created_at->format('Y-m-d'),
            'approve' => $this->approve,
            'total_job_done' => $this->total_job_done,
            'approveBy' => $this->approveBy,
            'approveBy' => $this->approveBy
        ];
    }
}
