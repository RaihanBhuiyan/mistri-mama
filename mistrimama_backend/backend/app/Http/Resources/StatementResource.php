<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatementResource extends JsonResource
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
            'date' => $this->date,
            'details' => ($this->ref == 'order') ? $this->details[0] : $this->details,
            'trxno' => $this->trxno,
            'status' => $this->status,
            'amount' => $this->amount,
            'balance' => $this->balance,
        ];
    }
}
