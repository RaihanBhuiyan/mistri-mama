<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class NotificationResource extends JsonResource
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
            'title' => json_decode($this->data)->title,
            'link' => json_decode($this->data)->path,
            'created_at' => $this->created_at->format('d F y h:i:s a'),
        ];
    }
}
