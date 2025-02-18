<?php

namespace App\Http\Controllers;

use App\Scheme;
use App\ServiceProvider;
use Illuminate\Http\Request;
use App\OrderDetail;
use App\Account;
use Carbon\Carbon;
use Notification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Account\AccountController;
use App\SMS; 
use App\User; 

class SchemeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function schemeGenerate(){
        $week_start = Carbon::now('Asia/Dhaka')->subDays(7)->toDateTimeString();
        $service_providers = ServiceProvider::where('last_scheme_update','<=', $week_start)->get(); 
        $today = Carbon::now('Asia/Dhaka')->toDateTimeString();
        $date = Carbon::now('Asia/Dhaka')->toDateString();
        try{
            DB::beginTransaction();

            if(count($service_providers) > 0)
            {
                foreach ($service_providers as $key => $service_provider) {
                    $get_last_week_orders = OrderDetail::where(['service_provider_id' => $service_provider->id, 'status' => 5])->whereIn('order_from', ['esp',  'fsp', 'comrade'])->whereBetween('sp_accept_time', [$week_start, $today])->count();
                    $get_last_week_income = Account::where('user_id', $service_provider->id)->whereBetween('created_at', [$week_start, $today])->where('ref', 'order')->whereIn('status', ['income', 'credit'])->sum('amount');
                    
                    
                    $bonus = 0;
                    $target = NULL;
                    if($get_last_week_orders > 14){
                        
                        if($get_last_week_orders >= 15 && $get_last_week_orders < 20)
                        {
                            $bonus = 50.00;
                            $target = "C";
                            $heading = 'Scheme Bonus Category(C)';
                            $mistrimama_details = 'Mistrimama give BDT ' . $bonus . ' scheme bonus to '.$service_provider->name.' ('.$service_provider->sp_code.')';
                            $service_provider_details = 'You got BDT ' . $bonus . ' scheme bonus from mistrimama.';
                        }
        
                        if($get_last_week_orders >= 20 && $get_last_week_orders < 30)
                        {
                            $bonus = 80.00;
                            $target = "B";
                            $heading = 'Scheme Bonus Category(B)';
                            $mistrimama_details = 'Mistrimama give BDT ' . $bonus . ' scheme bonus to '.$service_provider->name.' ('.$service_provider->sp_code.')';
                            $service_provider_details = 'You got BDT ' . $bonus . ' scheme bonus bonus from mistrimama.';
                        }
        
                        if($get_last_week_orders >= 30)
                        {
                            $bonus = 150.00;
                            $target = "A";
                            $heading = 'Scheme Bonus Category(A)';
                            $mistrimama_details = 'Mistrimama give BDT ' . $bonus . ' scheme bonus to '.$service_provider->name.' ('.$service_provider->sp_code.')';
                            $service_provider_details = 'You got BDT ' . $bonus . ' scheme bonus bonus from mistrimama.';
                        }
                        
                        $schemeArray = [
                            'service_provider_id' => $service_provider->id,
                            'count_jobs' =>  $get_last_week_orders,
                            'income' =>  $get_last_week_income,
                            'target' =>  $target,
                            'bonus' =>  $bonus,
                            'start_date' => $week_start,
                            'end_date' => $today,
                        ];
                        $scheme = Scheme::create($schemeArray);
    
                        $serviceProviderCredit['trx_for'] = 'service_provider';
                        $serviceProviderCredit['user_id'] = $service_provider->id;
                        $serviceProviderCredit['amount'] = $bonus;
                        $serviceProviderCredit['heading'] = $heading;
                        $serviceProviderCredit['details'] = $service_provider_details;
                        $serviceProviderCredit['ref'] = 'schemes';
                        // $serviceProviderCredit['ref_key'] = $scheme->id;
    
                        $mistriMamaDebit['trx_for'] = 'mistrimama';
                        $mistriMamaDebit['user_id'] = 1;
                        $mistriMamaDebit['amount'] = $bonus;
                        $mistriMamaDebit['heading'] = $heading;
                        $mistriMamaDebit['details'] = $mistrimama_details;
                        $mistriMamaDebit['ref'] = 'schemes';
                        // $mistriMamaDebit['ref_key'] = $scheme->id;
    
                        // AccountController::WithdrawRequestCredit($serviceProviderCredit);
                        // AccountController::WithdrawRequestDebit($mistriMamaDebit);
                        // ServiceProvider::where('id', $service_provider->id)->update(['last_scheme_update' =>  $today]);
    
                        // SMS::send($service_provider->phone, $service_provider_details);
                        // NotificationController::schemeNotification($service_provider);
                    }
                }
                // dd($schemeArray);
            }

            DB::commit();
        }catch(\Exception $e){
            dd($e);
            DB::rollback();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Scheme  $scheme
     * @return \Illuminate\Http\Response
     */
    public function show(Scheme $scheme)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Scheme  $scheme
     * @return \Illuminate\Http\Response
     */
    public function edit(Scheme $scheme)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Scheme  $scheme
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Scheme $scheme)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Scheme  $scheme
     * @return \Illuminate\Http\Response
     */
    public function destroy(Scheme $scheme)
    {
        //
    }
}
