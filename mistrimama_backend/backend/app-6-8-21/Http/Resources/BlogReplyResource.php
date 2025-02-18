<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class BlogReplyResource extends JsonResource
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
            'name' => $this->name,
            'phone' => $this->phone,
            'author_thumb' => (!empty($this->author_thumb)) ? $this->author_thumb : env('APP_URL').'/frontend/image/icons/author-thumb.png',
            'comment_id' => $this->comment_id,
            'message' => $this->message,
            'reply_on' => $this->created_at->format('d F Y h:m:s a'),
        ];
    }
}
