<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
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
            'id'                => $this->id,
            'user_id'           => $this->user_id,
            'type'              => $this->type,
            'replaced_type'     => $this->replaced_type,
            'status'            => $this->status,
            'photo_url'         => $this->photo_url,
            'filename'          => $this->filename,
            'comments'          => $this->comments
        ];
    }
}
