<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class BlogCommentsResource extends JsonResource
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
            'post_id' => $this->post_id,
            'message' => $this->message,
            'comment_on' => $this->created_at->format('d F Y h:m:s a'),
            'replies' => BlogReplyResource::collection($this->relReply->where('status', 1)),
        ];
    }
}
