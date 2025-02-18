<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class BlogArticleResource extends JsonResource
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
            'title' => $this->title,
            'title_bn' => $this->title_bn,
            'content' => $this->content,
            'content_bn' => $this->content_bn,
            'url' => env('FRONT_APP_URL').'/blog/'.$this->id,
            'short_description' => $this->short_description,
            'short_description_bn' => $this->short_description_bn,
            'image' => (!empty($this->image)) ? env('APP_URL').'/upload/blogs/'. $this->image : env('APP_URL').'/upload/'.'black-thumbnail.png',
            'post_by' => 'Mistrimama',
            'published_date' => Carbon::parse($this->published_date)->format('d F Y'),
            'post_datetime' => $this->created_at->format('d F Y h:m:s a'),
            'comments' => BlogCommentsResource::collection($this->relComments),
            'total_comments' => $this->relComments->count(),
            'total_like' => $this->total_like,
            'is_liked' => $this->is_liked,
        ];
    }
}
