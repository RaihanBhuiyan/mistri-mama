<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RiskFactorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $json_decode_particulars = json_decode($this->particulars);
        $string = "<ul><li>";
        $string .= str_replace(",", "</li><li>", implode(",", $json_decode_particulars));
        $string .= "</li></ul>";
        // dd($string);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'particulars' => $json_decode_particulars,
        ];
    }
}
