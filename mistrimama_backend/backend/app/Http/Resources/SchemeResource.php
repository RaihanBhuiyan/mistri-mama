<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SchemeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->count_jobs <= 15)
        {
            $data['a'] = round(($this->count_jobs * 100) / 15);
            $data['b'] = 0;
            $data['c'] = 0;
        }else if($this->count_jobs > 15 && $this->count_jobs <= 20)
        {
            $data['a'] = round((15 * 100) / 15);
            $data['b'] = round((($orders-15) * 100) / 5);
            $data['c'] = 0;
        }else if($this->count_jobs > 20 && $this->count_jobs <= 30)
        {
            $data['a'] = round((15 * 100) / 15);
            $data['b'] = round((20 * 100) / 20);
            $data['c'] = round((($this->count_jobs-20) * 100) / 10);
        }
        else
        {
            $data['a'] = round((15 * 100) / 15);
            $data['b'] = round((20 * 100) / 20);
            $data['c'] = round((30 * 100) / 30);
        }
        return [
            'id' => $this->id,
            'timeline' => $this->start_date.' '.$this->end_date,
            'total_job' => $this->count_jobs,
            'income' => $this->income,
            'target' => $this->target,
            'bonus' => $this->bonus,
            'received' => $this->created_at->format('d M Y h:m:s a'),
            'scheme' => $data,
        ];

    }
}
