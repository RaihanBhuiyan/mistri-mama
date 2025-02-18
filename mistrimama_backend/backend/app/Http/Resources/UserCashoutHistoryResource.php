<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCashoutHistoryResource extends JsonResource
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
            'request_date' => $this->created_at,
            'receive_date' => $this->accountCashOut->date,
            'mfs_number' => $this->mfs_number,
            'trxno' => $this->accountCashOut->trxno,
            'amount' => $this->accountCashOut->amount,
            'available_reward_point' => $this->client->available_reward_point,
            'available_reward_balance' => $this->client->available_reward_balance,
        ];
    }
}
