<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource; 

class BaboharbidhiResource extends JsonResource
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
            'id'        => $this->id,
            'title'     => $this->title,
            'discription' => $this->discription,
            'file'        => env('APP_PATH').'/public/upload/sp/'.$this->file,
            'file_name'   => $this->file,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
            'deleted_at'  => $this->deleted_at,
        ];
    }
}
