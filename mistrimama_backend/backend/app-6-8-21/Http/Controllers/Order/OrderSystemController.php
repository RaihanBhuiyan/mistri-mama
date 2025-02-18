<?php

namespace App\Http\Controllers\Order;

use App\Events\OrderEvent;
use App\Events\OrderPaymentEvent;
use App\Events\OrderFeedBackEvent;
use App\Http\Controllers\Account\AccountController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SslCommerzPaymentController;
use App\Http\Controllers\Promo\PromoUserController;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderDetailsResource;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SiteConfigsController;
use App\OrderSystem;
use App\Order; 
use App\OrderItem; 
use App\OrderDetail;
use App\QuickOrder;
use App\ServiceProvider;
use App\Client;
use App\Setting;
use Carbon\Carbon;
use App\Http\Resources\ComradeJobsResource; 
use App\Http\Resources\WaitingJobsResource;
use Illuminate\Http\Request;
use App\SMS;


class OrderSystemController extends Controller
{
    // allowcating service provider on admin panel
    public function allowcatingServiceProvider($order_id, $service_provider_id)
    {
        $data['order_id'] = $order_id;
        $data['service_provider_id'] = $service_provider_id;
        $data['state'] = 0;
        
        $data['sp_cat'] = ServiceProvider::find($service_provider_id)->category;
        $data['commission'] = ServiceProvider::find($service_provider_id)->commission;

        try{
            DB::beginTransaction();

            OrderSystem::where(['order_id' => $order_id])->delete();
            OrderItem::where(['order_id' => $order_id])->update([
                'status' => 0
            ]);
            if (OrderSystem::create($data)) {
                $order = Order::find($order_id);
                $order->status = 2;
                $order->accept_time = Carbon::now()->toDateTimeString();
                $order->save();
                DB::commit();

                return redirect()->route('order.index');
            }
        }catch(\Exception $e){
            DB::rollback();
        }
        return 'Something worng';
    }


    //** order accept and allowcate by service provider */
    public function AcceptByServiceProvider(Request $request)
    {
        $data['order_id'] = $request->order_id;
        $data['service_provider_id'] = auth()->user()->serviceProvider->id;
        $data['sp_cat'] = auth()->user()->serviceProvider->category;
        $data['commission'] = auth()->user()->serviceProvider->commission;

        try{
            DB::beginTransaction();
            if(auth()->user()->serviceProvider->type == 'fsp'){
                $data['state'] = 1;
            }else{
                $data['state'] = 0;
            }
            $order_system = OrderSystem::create($data);
            $order = Order::find($request->order_id);
            $implode_services = OrderItem::where('order_id', $order->id)->groupBy('service_name')->pluck('service_name')->implode(', ');
            $order->status = 2;
            
            $order->accept_time = Carbon::now()->toDateTimeString();
            $order->updated_at = Carbon::now()->toDateTimeString();
            $order->save();

            if(env('CHECK_SMS_STATUS'))
            {
                SMS::send($order->phone, "Your order ".$order->order_no." has been confirmed for ".$implode_services.". Technician: ".auth()->user()->name." Cell: ".auth()->user()->phone."");
            }

            NotificationController::orderAcceptNotification($order_system);
            //broadcast(new OrderEvent(new OrderResource($order)));
            
            DB::commit();
            return response(['message' => 'Order accpeted successfully']);
        }catch(\Exception $e){
            DB::rollback();
        return response(['message' => 'Order Not accpeted!!']);
        }

    }
    
    public function WaitingOrders()
    {
        $order = OrderDetail::where(['service_provider_id' => auth()->user()->serviceProvider->id, 'status' => 2])->orderBy('updated_at', 'desc')->get();
        return WaitingJobsResource::collection($order);
    }
    
    public function AcceptAndAllowcateComrade(Request $request)
    {
        $data['order_id'] = $request->order_id;
        $data['service_provider_id'] = auth()->user()->serviceProvider->id;
        $data['comrade_id'] = $request->comrade_id;
        $data['state'] = 1;
        $data['sp_cat'] = auth()->user()->serviceProvider->category;
        $data['commission'] = auth()->user()->serviceProvider->commission;

        try{
            DB::beginTransaction();

            $order_system = OrderSystem::create($data);
            $order = Order::find($request->order_id);
            $implode_services = OrderItem::where('order_id', $order->id)->groupBy('service_name')->pluck('service_name')->implode(', ');
            $order->status = 2;
            $order->accept_time = Carbon::now()->toDateTimeString();
            $order->allowcate_time = Carbon::now()->toDateTimeString();
            $order->save();
            
            //broadcast(new OrderEvent(new OrderResource($order)));
            
            if(!empty($order_system->comrade))
            {
                NotificationController::allocatedComradeNotification($order_system);
                
                if(env('CHECK_SMS_STATUS'))
                {
                    SMS::send($order_system->comrade->phone, "Order No #".$order->order_no.". Customer Name : ".$order->name.". Cell: ".$order->phone.". Address: ".$order->area.", ".$order->address.".Service: ".$implode_services."");
                }
            }
            if(env('CHECK_SMS_STATUS'))
            {
                SMS::send($order->phone, "Your order ".$order->order_no." has been confirmed for ".$implode_services.". Technician: ".$order_system->comrade->name." Cell: ".$order_system->comrade->phone."");
            }
            DB::commit();
            
            return response(['message' => 'Comrade assigned successfully']);

        }catch(\Exception $e){
            DB::rollback();
        }
        return response(['message' => 'Comrade assigned failed']);
    }

    public function changeComrade($order_id, $comrade_id)
    {
        $orderSystem = OrderSystem::where('order_id', $order_id)->where('state', '<', 2)->first();
        $orderSystem->comrade_id = $comrade_id;
        $orderSystem->state = 1;
        try{
            DB::beginTransaction();
            
            $orderSystem->save();
            $implode_services = OrderItem::where('order_id', $orderSystem->order->id)->groupBy('service_name')->pluck('service_name')->implode(', ');
            NotificationController::allocatedComradeNotification($orderSystem);
            
            if(env('CHECK_SMS_STATUS'))
            {
                SMS::send($orderSystem->comrade->phone, "Order No #".$orderSystem->order->order_no.". Customer Name : ".$orderSystem->order->name.". Cell: ".$orderSystem->order->phone.". Address: ".$orderSystem->order->area.", ".$orderSystem->order->address.". Service: ".$implode_services."");
                SMS::send($orderSystem->order->phone, "Your assigned technician has been changed. Order No #".$orderSystem->order->order_no.". Assigned technician ".$orderSystem->comrade->name.". Cell: ".$orderSystem->comrade->phone."");
            }
            
            DB::commit();

            return response(['message' => 'Comrade change successfully']);

        }catch(\Exception $e){
            DB::rollback();
        }
        return response(['message' => 'Comrade change failed']);
    }

    public function startServicing(Request $request)
    {
        $order = Order::find($request->id);
        $orderSystem = OrderSystem::where('order_id', $request->id)->first();
        $order->status = 3;
        $orderSystem->state = 2;
        try{
            DB::beginTransaction();

            $order->save();
            $orderSystem->save();

            DB::commit();

            return response()->json(['message' => 'Order Service Start Successfull', 200]);

        }catch(\Exception $e){
            DB::rollback();
        }
        return response()->json(['message' => 'Order Service Start Failed', 400]);
    }
    
    public function finishServicing(Request $request)
    {
        $order = Order::find($request->id);
        $totalPrice = $order->total_price;
        $order->status = 4;
        $order->finish_time = Carbon::now()->toDateTimeString();
        
        $orderSystem = OrderSystem::where('order_id', $request->id)->first();
        $orderSystem->state = 3;

        try{
            DB::beginTransaction();
            
            $order->save();
            $orderSystem->save();
    
            
            $orderDetail = OrderDetail::find($order->id);
                
            $order->discount = 0;
            if(!empty($order->promocode))
            {
                $order->discount = PromoUserController::applyPromocode($order->promocode, $order->phone, $order->total_price);
            }
            $order->save();
            
            $reduce_type = $order->reduce_type;
            $reduce_amount = $order->reduce_amount;

            if($orderSystem->customize_charge == 0)
            {
                $payable = $order->grant_total;
            }
            else
            {
                $payable = $order->customize_charge;
            }

            if(env('CHECK_SMS_STATUS'))
            {
                SMS::send($order->phone, "We are delighted to serve you. Your total payable amount is BDT ".round($payable).".");
            }

            DB::commit();
            broadcast(new OrderPaymentEvent(new OrderResource($orderDetail)));
            return response()->json([
                'message' => 'Order Finished',
                'discount' => $order->discount,
                'total_price' => $order->total_price,
            ], 200);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Your order has not finished'], 400);
        }
        
    }

    // this function should be accessable by only esp,fsp,comrade
    public function paymentReceive(Request $request)
    {
        $order = Order::find($request->id);
        $order->status = 5;
        $orderSystem = OrderSystem::where('order_id', $request->id)->first();
        $orderSystem->state = 4;
        
        if(!empty($order->pay_type))
        {
            return response()->json(['message' => 'Already payment completed'], 400);
        }
        $order->pay_type = 1;

        try{
            DB::beginTransaction();

                $order->save();
                $orderSystem->save();

                $orderDetails = OrderDetail::find($request->id);
                $serviceProvider = ServiceProvider::find($orderDetails->service_provider_id);
                self::orderBillPaymentReceived($orderDetails, $serviceProvider);
                
            NotificationController::orderPaymentReceivedNotification($orderDetails);
            DB::commit();
            
            broadcast(new OrderFeedBackEvent(new OrderResource($order)));
            return response()->json(['message' => 'Order Finish successfully'], 200);
        }catch(\Exception $e){
            return $e;
            DB::rollback();
        }
        return response()->json(['message' => 'Something went wrong'], 400);

    }

    public static function orderBillPaymentReceived($orderDetails, $serviceProvider)
    {
        $service_fee_txt = NULL;
        $service_extra_fees = 0;
        $order_for = $orderDetails->order_for;
        $service_provider_income_amount = $orderDetails->total_commission;
        $customize_charge = $orderDetails->customize_charge;

        if($customize_charge == 0)
        {
            $order_bill = $orderDetails->total_price;
        }
        else
        {
            $order_bill = $customize_charge;
        }
        
        if(($orderDetails->emergency_charge > 0) || ($orderDetails->outside_charge > 0)){
            $service_fee_txt = ' Service fee BDT '.($orderDetails->total_price - $orderDetails->discount).'';
            if(($orderDetails->emergency_charge > 0)){
                $order_bill = $order_bill + (int) $orderDetails->emergency_charge;
                $service_extra_fees = $service_extra_fees + (int) $orderDetails->emergency_charge;
                $service_fee_txt .= ' and Emergency charge BDT '.$orderDetails->emergency_charge.'';
            }
            if(($orderDetails->outside_charge > 0)){
                $order_bill = $order_bill + (int) $orderDetails->outside_charge;
                $service_extra_fees = $service_extra_fees + (int) $orderDetails->outside_charge;
                $service_fee_txt .= ' and Outside DMC charge BDT '.$orderDetails->outside_charge.'';
            }
        }

        $discount_txt = NULL;
        if($orderDetails->discount > 0){
            $discount_txt = ' Service discount BDT '.$orderDetails->discount.'.';
            if($customize_charge == 0)
            {
                $order_bill = ($order_bill - $orderDetails->discount);
            }
        }

        if($order_for == 'self'){
            $service_provider_income_amount = $service_provider_income_amount + $service_extra_fees;
        }
        if($order_for != 'self'){
            $service_provider_income_amount = $order_bill;
        }

        if($orderDetails->pay_type > 1)
        {
            $pay_type = 'Digital Payment';
            $type = 'Virtual';

            $mistrimama_receive = NULL;
            $mistrimama_income = [
                'trx_for' => 'mistrimama',
                'user_id' => 1,
                'amount' => $order_bill,
                'type' => 'Virtual',
                'heading' => 'Order Commission',
                'details' => '[Order # '.$orderDetails->order_no.'] Digital payment received from customer.'.$discount_txt.''.$service_fee_txt.'',
                'ref' => 'order',
                'ref_key' => $orderDetails->order_id,
            ];
            $mistrimama_expanse = [
                'trx_for' => 'mistrimama',
                'user_id' => 1,
                'amount' => $service_provider_income_amount,
                'type' => 'Virtual',
                'heading' => 'Discount adjustment',
                'details' => '[Order # '.$orderDetails->order_no.'] Service Partner Commission (Digital).',
                'ref' => 'order',
                'ref_key' => $orderDetails->order_id,
            ];
            $service_provider_income = [
                'trx_for' => 'service_provider',
                'user_id' => $serviceProvider->user_id,
                'amount' => $service_provider_income_amount,
                'type' => $type,
                'heading' => 'Order bill received',
                'details' => '[Order # '.$orderDetails->order_no.'] Digital received from customer.'.$discount_txt.''.$service_fee_txt.'',
                'ref' => 'order',
                'ref_key' => $orderDetails->order_id,
                'status' => 'credit',
            ];
            $service_provider_expanse = NULL;
            $client_expanse = [
                'trx_for' => 'client',
                'user_id' => $orderDetails->user_id,
                'amount' => $order_bill,
                'type' => 'Virtual',
                'heading' => 'Order Bill',
                'details' => '[Order # '.$orderDetails->order_no.'] Service fee digital payment to Mistri Mama through payment gateway.',
                'ref' => 'order',
                'ref_key' => $orderDetails->order_id,
            ];
        }
        else
        {
            $pay_type = 'Cash';
            $type = 'Cash';

            $mistrimama_receive = [
                'details' => '[Order # '.$orderDetails->order_no.'] Cash received from customer.'.$discount_txt.''.$service_fee_txt.'',
                'ref' => 'order',
                'ref_key' => $orderDetails->order_id,
            ];
            $mistrimama_income = [
                'trx_for' => 'mistrimama',
                'user_id' => 1,
                'amount' => ($order_bill - $service_provider_income_amount),
                'type' => $type,
                'heading' => 'Order Commission',
                'details' => '[Order # '.$orderDetails->order_no.'] Mistri Mama Service Commission recived from service partner.',
                'ref' => 'order',
                'ref_key' => $orderDetails->order_id,
            ];
            $mistrimama_expanse = NULL;
            $service_provider_income = [
                'trx_for' => 'service_provider',
                'user_id' => $serviceProvider->user_id,
                'amount' => $order_bill,
                'type' => $type,
                'heading' => 'Order bill received',
                'details' => '[Order # '.$orderDetails->order_no.'] Cash received from customer.'.$discount_txt.''.$service_fee_txt.'',
                'ref' => 'order',
                'ref_key' => $orderDetails->order_id,
                'status' => 'income',
            ];
            $service_provider_expanse = [
                'trx_for' => 'service_provider',
                'user_id' => $serviceProvider->user_id,
                'amount' => ($order_bill - $service_provider_income_amount),
                'type' => 'Virtual',
                'heading' => 'Order Commission',
                'details' => '[Order # '.$orderDetails->order_no.'] Mistri Mama Service Commission.',
                'ref' => 'order',
                'ref_key' => $orderDetails->order_id,
            ];
            $client_expanse = [
                'trx_for' => 'client',
                'user_id' => $orderDetails->user_id,
                'amount' => $order_bill,
                'type' => $type,
                'heading' => 'Order Bill',
                'details' => '[Order # '.$orderDetails->order_no.'] Service fee cash payment to Mistri Mama.',
                'ref' => 'order',
                'ref_key' => $orderDetails->order_id,
            ];
        }

        // mistrimama cash receive
        if(!empty($mistrimama_receive))
        {
            NotificationController::orderMistrimamaCashReceiveNotification($mistrimama_receive);
        }

        // mistrimama commsion income
        if(!empty($mistrimama_income) && ($mistrimama_income['amount'] > 0))
        {
            AccountController::WithdrawRequestCredit($mistrimama_income);
        }

        // mistrimama discount adjustment
        if(!empty($mistrimama_expanse) && ($mistrimama_expanse['amount'] > 0))
        {
            AccountController::WithdrawRequestDebit($mistrimama_expanse);
        }
        
        // service provider order bill income
        if(!empty($service_provider_income) && ($service_provider_income['amount'] > 0))
        {
            AccountController::serviceProviderIncome($service_provider_income);
        }

        // dedudct commssion amount from service provider account
        if(!empty($service_provider_expanse) && ($service_provider_expanse['amount'] > 0))
        {
            AccountController::WithdrawRequestDebit($service_provider_expanse);
        }
        // client order bill
        if(!empty($client_expanse) && ($client_expanse['amount'] > 0)){
            AccountController::WithdrawRequestDebit($client_expanse);
        }

        if ($orderDetails->ref_code != null) {
            AccountController::rewardPointAdd($orderDetails);
        }

        if(env('CHECK_SMS_STATUS'))
        {
            $txt = "Order No # : ".$orderDetails->order_no.". ".$service_provider_income['details'].".";
            SMS::send($serviceProvider->phone, $txt);
            if(!empty($orderDetails->comrade_id))
            {
                SMS::send($orderDetails->comrade->phone, $txt);
            }
        }
    }

    // public static function orderBillPaymentReceived($orderDetails, $serviceProvider)
    // {
    //     if($orderDetails->customize_charge == 0)
    //     {
    //         $total_order_amount = $orderDetails->total_price;
    //     }
    //     else
    //     {
    //         $total_order_amount = $orderDetails->customize_charge;
    //     }
    //     $commission_amount = (($total_order_amount * $orderDetails->commission) / 100);
    //     $order_bill = 0;
        
    //     if ($orderDetails->pay_type > 1) {
    //         $pay_type = 'Digital Payment';
    //         $type = 'Virtual';
    //         $status = 'credit';
    //         if($orderDetails->customize_charge == 0)
    //         {
    //             $order_bill = (($total_order_amount - $orderDetails->discount) - $commission_amount);
    //         }
    //         else
    //         {
    //             $order_bill = $orderDetails->customize_charge;
    //         }
    //     } else {
    //         $pay_type = 'Cash';
    //         $type = 'Cash';
    //         $status = 'income';
    //         if($orderDetails->customize_charge == 0)
    //         {
    //             $order_bill = ($total_order_amount - $orderDetails->discount);
    //         }
    //         else
    //         {
    //             $order_bill = $orderDetails->customize_charge;
    //         }
    //     }

    //     $service_provider_income_array = [
    //         'user_id' => $serviceProvider->user_id,
    //         'amount' => $order_bill,
    //         'type' => $type,
    //         'heading' => 'Order bill received',
    //         'details' => '[Order No #'.$orderDetails->order_no.'] Order bill received with '.$pay_type.'.',
    //         'ref_key' => $orderDetails->order_id,
    //         'status' => $status,
    //     ];
    //     // service provider order bill income
    //     AccountController::serviceProviderIncome($service_provider_income_array);
        
    //     $extra_charge = 0;
    //     if(($orderDetails->emergency_charge > 0) && ($orderDetails->customize_charge == 0))
    //     {
    //         $extra_charge = ($extra_charge + (int) $orderDetails->emergency_charge);
    //         $service_provider_emergency_charge_income_array = [
    //             'user_id' => $serviceProvider->user_id,
    //             'amount' => (int) $orderDetails->emergency_charge,
    //             'type' => $type,
    //             'heading' => 'Emergency charge received',
    //             'details' => '[Order No #'.$orderDetails->order_no.'] Emergency charge received with '.$pay_type.'.',
    //             'ref_key' => $orderDetails->order_id,
    //             'status' => $status,
    //         ];
    //         // service provider emergency charge also as a income
    //         AccountController::serviceProviderIncome($service_provider_emergency_charge_income_array);
    //     }
        
    //     if(($orderDetails->outside_charge > 0) && ($orderDetails->customize_charge == 0))
    //     {
    //         $extra_charge = ($extra_charge + (int) $orderDetails->outside_charge);
    //         $service_provider_outside_charge_income_array = [
    //             'user_id' => $serviceProvider->user_id,
    //             'amount' => (int) $orderDetails->outside_charge,
    //             'type' => $type,
    //             'heading' => 'Outside charge received',
    //             'details' => '[Order No #'.$orderDetails->order_no.'] Outside charge received with '.$pay_type.'.',
    //             'ref_key' => $orderDetails->order_id,
    //             'status' => $status,
    //         ];
    //         // service provider outside charge also as a income
    //         AccountController::serviceProviderIncome($service_provider_outside_charge_income_array);
    //     }

    //     $discount = 0;
    //     if (($orderDetails->discount != 0.00) && ($orderDetails->customize_charge == 0))
    //     {
    //         $discount = (int) $orderDetails->discount;
    //         $service_provider_adjust_discount_array = [
    //             'user_id' => $serviceProvider->user_id,
    //             'amount' => $discount,
    //             'type' => 'Virtual',
    //             'heading' => 'Discount adjustment',
    //             'details' => '[Order No #'.$orderDetails->order_no.'] Discount adjustment.',
    //             'ref_key' => $orderDetails->order_id,
    //             'status' => 'credit',
    //         ];
    //         // service provider discount adjustment also as a income
    //         AccountController::serviceProviderIncome($service_provider_adjust_discount_array);
    
    //         $mistrimama_adjust_discount_array = [
    //             'trx_for' => 'mistrimama',
    //             'user_id' => 1, // admin user id
    //             'amount' => $discount,
    //             'type' => 'Virtual',
    //             'heading' => 'Discount adjustment',
    //             'details' => '[Order No #'.$orderDetails->order_no.'] Discount adjustment. Served by  ' . $serviceProvider->name . '-' . $serviceProvider->sp_code . '',
    //             'ref' => 'order',
    //             'ref_key' => $orderDetails->order_id,
    //             'status' => 'debit',
    //         ];
    //         // mistrimama discount adjustment
    //         AccountController::WithdrawRequestDebit($mistrimama_adjust_discount_array);
    //     }

    //     if (($orderDetails->pay_type == 1) && ($orderDetails->customize_charge == 0) && ($orderDetails->commission != 0)) {
    //         $service_provider_commsion_deduction_array = [
    //             'trx_for' => 'service_provider',
    //             'user_id' => $serviceProvider->user_id,
    //             'amount' => (int) $commission_amount,
    //             'type' => 'Virtual',
    //             'heading' => 'Order Commission',
    //             'details' => '[Order No #'.$orderDetails->order_no.'] Mistrimama commission ' . $orderDetails->commission . '% has been deduction.',
    //             'ref' => 'order',
    //             'ref_key' => $orderDetails->order_id,
    //         ];
    //         // dedudct commssion amount from service provider account
    //         AccountController::WithdrawRequestDebit($service_provider_commsion_deduction_array);
    //     }

    //     if(($orderDetails->customize_charge == 0) && ($orderDetails->customize_charge == 0) && ($orderDetails->commission != 0))
    //     {
    //         $mistrimama_commsion_income_array = [
    //             'trx_for' => 'mistrimama',
    //             'user_id' => 1,
    //             'amount' => (int) $commission_amount,
    //             'type' => 'Virtual',
    //             'heading' => 'Order Commission',
    //             'details' => '[Order No #'.$orderDetails->order_no.'] Order Commission added. Served by  ' . $serviceProvider->name . '-' . $serviceProvider->sp_code . '',
    //             'ref' => 'order',
    //             'ref_key' => $orderDetails->order_id,
    //         ];
    //         // mistrimama commsion income
    //         AccountController::WithdrawRequestCredit($mistrimama_commsion_income_array);
    //     }
        
    //     $client_order_bill_array = [
    //         'trx_for' => 'client',
    //         'user_id' => $orderDetails->user_id,
    //         'amount' => round((($total_order_amount - $discount) + $extra_charge), 2),
    //         'type' => 'Virtual',
    //         'heading' => 'Client Order Bill',
    //         'details' => '[Order No #'.$orderDetails->order_no.'] Client Order Bill. Served by  ' . $serviceProvider->name . '-' . $serviceProvider->sp_code . '',
    //         'ref' => 'order',
    //         'ref_key' => $orderDetails->order_id,
    //         'status' => 'debit',
    //     ];
    //     // client order bill
    //     AccountController::WithdrawRequestDebit($client_order_bill_array);

    //     if ($orderDetails->ref_code != null) {
    //         AccountController::rewardPointAdd($orderDetails);
    //     }

    //     $received_amount = "";
    //     if ($orderDetails->pay_type == 1) {
    //         $received_amount = "Total Cash Received BDT ".round((($total_order_amount - $discount) + $extra_charge), 2)."/-";
    //     }
        
    //     if(env('CHECK_SMS_STATUS'))
    //     {
    //         $txt = "Order No # : ".$orderDetails->order_no.". ".$received_amount." Your Earning BDT ".(($total_order_amount - $commission_amount) + $extra_charge)."/- Mistri Mama Commission: BDT ".$commission_amount."/-";
    //         SMS::send($serviceProvider->phone, $txt);
    //         if(!empty($orderDetails->comrade_id))
    //         {
    //             SMS::send($orderDetails->comrade->phone, $txt);
    //         }
    //     }
    // }

    // no work this method
    public function payCashByClient($id)
    {
        $order = Order::find($id);
        $order->pay_type = 1;
        try{
            DB::beginTransaction();

            $order->save();

            DB::commit();

            return response()->json(['message' => 'Cash payment successfully'], 200);

        }catch(\Exception $e){
            DB::rollback();
        }
        return response()->json(['message' => 'Cash payment failed'], 400);
    }

    // pay ssl by sp and client
    public function paySSL($id)
    {
        $order = Order::find($id);
        if(!empty($order->pay_type))
        {
            return response()->json(['message' => 'Already payment completed'], 400);
        }
        $data['total_amount'] = $order->grant_total;
        $data['cus_name'] = $order->name;
        $data['cus_email'] = (!empty($order->user)) ? (!empty($order->user->email)) ? $order->user->email : 'info@mistrimama.com' : 'info@mistrimama.com';
        $data['cus_add1'] = $order->address;
        $data['cus_add2'] = $order->area;
        $data['cus_phone'] = $order->phone;
        $data['order_no'] = $order->order_no;
        $data['order_cat'] = $order->category_name;
        SslCommerzPaymentController::payment($data);
    }

    public function orderUserRating($order_id, $rating)
    {
        OrderSystem::where('order_id', $order_id)->update([
            'user_rating' => $rating
        ]);
        return response()->json(['message' => 'Thank you for your rating.']);
    }

    public function orderServiceProviderRating($order_id, $rating)
    {
        OrderSystem::where('order_id', $order_id)->update([
            'sp_rating' => $rating
        ]);
        return response()->json(['message' => 'Thank you for your rating.']);
    }

    public function downloadOrderHistory($type)
    {
        $authUser = auth()->user();
        $path = public_path('invoice/');
        $fileName = 'order_history.pdf';
        $data['orders'] = OrderDetail::where(['user_id' => 6, 'status' => 5])->limit(20)->orderBy('id', 'desc')->get();
        $mpdf = new \Mpdf\Mpdf(['tempDir' => public_path('tempdir'), 'mode' => 'utf-8', 'format' => 'A4']);
        $html = view('component.invoice.order_history', $data)->render();
        $mpdf->WriteHTML($html);

        if($type == 'web')
        {
            $mpdf->Output($path. '/' . $fileName, 'F');
            return response()->download($path. '/' . $fileName)->deleteFileAfterSend(true); 
        }

        if($type == 'app')
        {
            $mpdf->Output($path. '/' . $fileName);
            return response()->json(env('APP_URL'). '/public/invoice/' . $fileName, 200);
        }
        return response()->json(['message' => 'Download Failed'], 400);
    }

    public function sendOrderHistory(Request $request)
    {
        $request->validate([
            'email_address' => 'required|email|max:255',
        ]);
        
        $authUser = auth()->user();
        $path = public_path('invoice/');
        $fileName = 'order_history.pdf';
        $data['orders'] = OrderDetail::where(['user_id' => $authUser->id, 'status' => 5])->limit(20)->orderBy('id', 'desc')->get();
        $mpdf = new \Mpdf\Mpdf(['tempDir' => public_path('tempdir'), 'mode' => 'utf-8', 'format' => 'A4']);
        
        $html = view('component.invoice.order_history', $data)->render();
        $mpdf->WriteHTML($html);
        $mpdf->Output($path. '/' . $fileName, 'F');
        
        $from_address = env('MAIL_USERNAME');
        $to_address = $request->email_address;
        
        return SiteConfigsController::sendMailFunction($from_address, $to_address, "Order History", "Order History", $path. '/' . $fileName);
    }

    public function downloadQuickOrderHistory($type)
    {
        $authUser = auth()->user();
        $path = public_path('invoice/');
        $fileName = 'quick_order_history.pdf';
        $data['orders'] = QuickOrder::where(['phone' => $authUser->phone, 'status' => 0])->limit(20)->orderBy('id', 'desc')->get();
        $mpdf = new \Mpdf\Mpdf(['tempDir' => public_path('tempdir'), 'mode' => 'utf-8', 'format' => 'A4']);
        
        $html = view('component.invoice.quick_order_history', $data)->render();
        $mpdf->WriteHTML($html);

        if($type == 'web')
        {
            $mpdf->Output($path. '/' . $fileName, 'F');
            return response()->download($path. '/' . $fileName)->deleteFileAfterSend(true); 
        }

        if($type == 'app')
        {
            $mpdf->Output($path. '/' . $fileName);
            return response()->json(env('APP_URL'). '/public/invoice/' . $fileName, 200);
        }
        return response()->json(['message' => 'Download Failed'], 400);
    }

    public function sendQuickOrderHistory(Request $request)
    {
        $request->validate([
            'email_address' => 'required|email|max:255',
        ]);
        
        $authUser = auth()->user();
        $path = public_path('invoice/');
        $fileName = 'quick_order_history.pdf';
        $data['orders'] = QuickOrder::where(['phone' => $authUser->phone, 'status' => 0])->limit(20)->orderBy('id', 'desc')->get();
        $mpdf = new \Mpdf\Mpdf(['tempDir' => public_path('tempdir'), 'mode' => 'utf-8', 'format' => 'A4']);
        
        $html = view('component.invoice.quick_order_history', $data)->render();
        $mpdf->WriteHTML($html);
        $mpdf->Output($path. '/' . $fileName, 'F');
        
        $from_address = env('MAIL_USERNAME');
        $to_address = $request->email_address;
        
        return SiteConfigsController::sendMailFunction($from_address, $to_address, "Quick Order History", "Quick Order History", $path. '/' . $fileName);
    }

    public function downloadOrderInvoice($type, $order_no)
    {
        $setting = Setting::first();
        $data['office_start_time'] = $setting->office_start_time;
        $data['office_end_time'] = $setting->office_end_time;

        $path = public_path('invoice/');
        $fileName = 'invoice_'. $order_no .'.pdf';
        $data['order'] = OrderDetail::where(['order_no' => $order_no])->first();
        $mpdf = new \Mpdf\Mpdf(['tempDir' => public_path('tempdir'), 'mode' => 'utf-8', 'format' => 'A4']);
        
        $html = view('component.invoice.receipt', $data)->render();
        $mpdf->WriteHTML($html);

        if($type == 'web')
        {
            $mpdf->Output($path. '/' . $fileName, 'F');
            return response()->download($path. '/' . $fileName)->deleteFileAfterSend(true); 
        }

        if($type == 'app')
        {
            $mpdf->Output($path. '/' . $fileName);
            return response()->json(env('APP_URL'). '/public/invoice/' . $fileName, 200);
        }
        return response()->json(['message' => 'Download Failed'], 400);
    }

    public function sendOrderInvoice(Request $request)
    {
        $request->validate([
            'order_no' => 'required',
            'email_address' => 'required|email|max:255',
        ]);
        
        $order_no = $request->order_no;
        
        $setting = Setting::first();
        $data['office_start_time'] = $setting->office_start_time;
        $data['office_end_time'] = $setting->office_end_time;
        $path = public_path('invoice/');
        $fileName = 'invoice_'. $order_no .'.pdf';
        $data['order'] = OrderDetail::where(['order_no' => $order_no])->first();
        $mpdf = new \Mpdf\Mpdf(['tempDir' => public_path('tempdir'), 'mode' => 'utf-8', 'format' => 'A4']);
        
        $html = view('component.invoice.receipt', $data)->render();
        $mpdf->WriteHTML($html);
        $mpdf->Output($path. '/' . $fileName, 'F');
        
        $from_address = env('MAIL_USERNAME');
        $to_address = $request->email_address;
        
        return SiteConfigsController::sendMailFunction($from_address, $to_address, "Order Invoice ID# ".$order_no."", "Order Invoice ID# ".$order_no."", $path. '/' . $fileName);
    }
}
