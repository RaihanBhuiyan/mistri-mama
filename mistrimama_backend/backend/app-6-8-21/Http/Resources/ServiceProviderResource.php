<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Setting;

class ServiceProviderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        $nid_front = $this->media->where('type', 'nid_front')->first();
        $nid_back = $this->media->where('type', 'nid_back')->first();
        $trade_lic_image = $this->media->where('type', 'trade_lic_image')->first();
        $tin_image = $this->media->where('type', 'tin_image')->first();
        
        return [
            'id'                => $this->id,
            'user_id'           => $this->user_id,
            'sp_code'           => $this->sp_code,
            'shop_name'         => $this->shop_name,
            'name'              => $this->name,
            'phone'             => $this->phone,
            'email'             => $this->email,
            'address'           => $this->address,
            'mfs_type'          => $this->mfs_type,
            'mfs_no'            => $this->mfs_no,
            'photo'             => $this->photo,
            'nid_no'            => $this->nid_no,
            'trade_lic_no'      => $this->trade_lic_no,
            'tin_no'            => $this->tin_no,
            'media'             => [
                'nid_front' => (!empty($nid_front)) ? new MediaResource($nid_front) : ['photo_url' => env('APP_URL').'/public/upload/'.'black-thumbnail.png', 'status' => 'Not Upload'],
                'nid_back' => (!empty($nid_back)) ? new MediaResource($nid_back) : ['photo_url' => env('APP_URL').'/public/upload/'.'black-thumbnail.png', 'status' => 'Not Upload'],
                'trade_lic_image' => (!empty($trade_lic_image)) ? new MediaResource($trade_lic_image) : ['photo_url' => env('APP_URL').'/public/upload/'.'black-thumbnail.png', 'status' => 'Not Upload'],
                'tin_image' => (!empty($tin_image)) ? new MediaResource($tin_image) : ['photo_url' => env('APP_URL').'/public/upload/'.'black-thumbnail.png', 'status' => 'Not Upload'],
            ],
            'alt_phone'         => $this->alt_phone,
            'status'            => $this->status,
            'type'              => $this->type,
            'category'          => $this->category,
            'category_upgrade_request'  => (!empty($this->relCategoryUpgradeRequst)) ? $this->relCategoryUpgradeRequst : false,
            'created_at'        => $this->created_at,
            'avail_services'    => ServiceProviderServiceResource::collection($this->services),
            'total_avail_service' =>  $this->services ? $this->services->count() : 0,
            'total_avail_sub_service' => $this->total_avail_sub_service,
            
            'comrades'          => $this->comrades ? $this->comrades : 0,
            'total_comrades'    => $this->comrades ? $this->comrades->count() : 0,
            'active_comrades'   => $this->comrades ? $this->comrades->where('status', 1)->where('approve', 1)->count() : 0,

            'balance'           => $this->balance,
            'withdrawable_balance' => $this->withdrawable_balance,
            'withdrawable_limit' => (int) Setting::first()->withdrawable_limit,
            
            'rating'            => $this->ratings,

            'total_job_done'    => $this->total_job_done,
            'total_job_running' => $this->total_job_running,
            'total_job_waiting' => $this->total_job_waiting,

            'commission'        => $this->commission,
            'last_recharge'     => (!empty($this->last_recharge)) ? $this->last_recharge->amount : 0,
            'last_withdraw'     => (!empty($this->last_withdraw)) ? $this->last_withdraw->amount : 0,
            'last_order'        => (!empty($this->last_order)) ? new OrderDetailsResource($this->last_order) : ['grant_total' => 0],

            'todays_income'     => $this->todays_income,
            'yesterdays_income' => $this->yesterdays_income,
            'this_month_income' => $this->this_month_income,
            'last_month_income' => $this->last_month_income, 

            'mistrimama_todays_income' => $this->mistrimama_todays_income,
            'mistrimama_todays_job'   => $this->mistrimama_todays_job,
            'self_todays_income' => $this->self_todays_income,
            'self_todays_job'   => $this->self_todays_job,
            
            'total_self_order'  => $this->total_self_order,
            'total_self_order_income' => $this->total_self_order_income,
            'total_mistrimama_order'  => $this->total_mistrimama_order,
            'total_mistrimama_order_price' => $this->total_mistrimama_order_price,
            'total_mistrimama_order_income'  => $this->total_mistrimama_order_income,
            
            'this_month_self_job'  => $this->this_month_self_job,
            'this_month_self_income'  => $this->this_month_self_income,
            'this_month_mistrimama_job'  => $this->this_month_mistrimama_job,
            'this_month_mistrimama_income'  => $this->this_month_mistrimama_income,

            'this_mistrimama_emergency_hour_job' => $this->this_mistrimama_emergency_hour_job,
            'this_self_emergency_hour_job' =>  $this->this_self_emergency_hour_job,
            'this_mistrimama_emergency_hour_job_charge' => $this->this_mistrimama_emergency_hour_job_charge,
            'this_self_emergency_hour_job_charge' =>  $this->this_self_emergency_hour_job_charge, 

            'this_mistrimama_outsidedmc_job' => $this->this_mistrimama_outsidedmc_job,
            'this_self_outsidedmc_job' =>  $this->this_self_outsidedmc_job,
            'this_mistrimama_outsidedmc_job_charge' => $this->this_mistrimama_outsidedmc_job_charge,
            'this_self_outsidedmc_job_charge' =>  $this->this_self_outsidedmc_job_charge, 

            'this_mistrimama_cash_collection_job' => $this->this_mistrimama_cash_collection_job,
            'this_self_cash_collection_job' =>  $this->this_self_cash_collection_job,

            'this_mistrimama_digital_payment_job' => $this->this_mistrimama_digital_payment_job,
            'this_self_digital_payment_job' => $this->this_self_digital_payment_job,
            // Current Week Total Job 

            'mistrimama_current_week_total_job' => $this->mistrimama_current_week_total_job,
            'mistrimama_current_week_total_income' => $this->mistrimama_current_week_total_income,
            'self_current_week_total_job' => $this->self_current_week_total_job,
            'self_current_week_total_income' => $this->self_current_week_total_income,
        ];
    }
}
