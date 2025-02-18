<?php

namespace App\Http\Controllers\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\OrderEvent;
use App\Events\OrderPaymentEvent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Order\OrderSystemController;
use App\Http\Controllers\Promo\PromoUserController;
use App\Http\Controllers\SiteConfigsController;
use App\Http\Resources\ComradeJobsResource;
use App\Http\Resources\OrderDetailsResource;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Order\OrderItemController;
use App\ServiceProvider;
use App\Comrade;
use App\Category;
use App\Client;
use App\Order;
use App\OrderItem;
use App\Service;
use App\ServiceBit;
use App\Http\Resources\OrderResource;
use App\OrderDetail;
use App\QuickOrder;
use App\SMS;
use App\PromoUser;
use App\Role;
use App\User;
use App\Cluster;
use App\Division;
use App\Setting;
use App\Promocode;
use Carbon\Carbon;
use App\OrderSystem;
use Session;
use stdClass;
class BlukOrder extends Controller
{
    public function index(){
        return view('order.bulkOrder');
    }

    public function store(Request $request){
        $user_data = User::with(['client'])->get()->keyBy('phone');
        $Cluster = Cluster::get()->keyBy('name');
        $ServiceBit = ServiceBit::with(['service'])->get()->keyBy('id');
        $ServiceProvider = ServiceProvider::get()->keyBy('phone');
        $Comrade = Comrade::get()->keyBy('phone');
        $Order = Order::with(['orderItems'])->where('status',0)->get()->keyBy('order_no');

        $order_record = [];
        $order = [];
        $order_bits = [];  
        //Note: Csv to formate data calculation      
        if (($handle = fopen ( $request->file('picture_file'), 'r' )) !== FALSE) {
            $i=0;
            while ( ($data = fgetcsv ( $handle, 1000, ',' )) !== FALSE ) {
                if($i!=0){//Note: Ignore Heading.
                    //Note: All Csv order list record
                    $order_record =['order_no' => $data[13]];

                    $single_user = isset($user_data[$data[3]])?$user_data :[];
                    $single_serviceBit = isset($ServiceBit[$data[14]])?$ServiceBit[$data[14]]:[];

                    $orderData = [
                        //Note: User Info
                        'order_no'=>isset($Order[$data[13]])?$Order[$data[13]] : randomOrderNo(),
                        'order_Code'=>isset($Order[$data[13]])?$data[13] : randomOrderNo(),
                        'user_type'=>isset($single_user)?'old_user':'new_user',
                        'name' => isset($single_user->name)? $single_user->name :$data[3],
                        'phone_no' => $data[4],
                        'cluster' => isset($Cluster[$data[6]])?$Cluster[$data[6]]->id:1,//Note: cluster is area/zone
                        'address' => $data[5],
                        'order_date' => $data[1],
                        'order_time' => '09:00 AM - 10:00 AM',
                        'note' => isset($data[6])?$data[6]:' ',
                        'category_id' => isset($single_serviceBit->category_id)?$single_serviceBit->category_id:1,
                        'service_provider' => isset($ServiceProvider[$data[10]])?$ServiceProvider[$data[10]]:[],
                        'Comrade' => [],
                        'payment_mode' => 'Cash',
                        'discount' => !empty($data[8])?$data[8]:0,
                        'outside_charge' => !empty($data[12])?$data[12]:0,
                    ]; 

                    $order_bitsData = [
                        //Note: service info
                        'order_Code'=>isset($Order[$data[13]])?$data[13] : randomOrderNo(),
                        'service_id' => isset($single_serviceBit->service_id)?$single_serviceBit->service_id:1, 
                        'service_name' => isset($single_serviceBit['service']->name)?$single_serviceBit['service']->name:'Check up',                  
                        //Note: service bit info    
                        'id' => isset($single_serviceBit->id)?$single_serviceBit->id:1,                           
                        'service_bit_name' => isset($single_serviceBit->name)?$single_serviceBit->name:'This is from system',
                        'qty' => isset($data[16])?$data[16]:0,                   
                        'price' => isset($data[17])?$data[17]:0,                   
                        'service_provider_price' => isset($data[18])?$data[18]:0,  
                        'commission' => isset($data[18])?$data[18]:0,                 
                        'total_price' => isset($data[17])?$data[17]:0,                   
                        'unit_remarks' => ' ',                   
                        'unit_type' => isset($single_serviceBit->unit_type)?$single_serviceBit->unit_type:'',                   
                        'brief' => isset($single_serviceBit->brief)?$single_serviceBit->brief:'',                   
                        'type' => isset($single_serviceBit->type)?$single_serviceBit->type:'',
                    ];                
                        
                    if(!empty($orderData['phone_no'])){
                        $order[] = $orderData;
                    }

                    if(!empty($order_bitsData['service_id'])){
                        $order_bits[] = $order_bitsData;
                    }

                }
                $i++;   
                          
            }
            fclose ( $handle );
        }
        $order_bits = collect($order_bits)->groupby('order_Code')->all();
        //Note: All complete order list record in db 
        DB::table('bulk_orders_record')->insert($order_record);

        $order_delete = [];
        //Note: Bulk Order process
        foreach ($order as $key => $value) {  
            if(isset($Order[$value['order_Code']])){
                //Note:New  Bulk Order Update
                $value['session_order'] = $order_bits[$value['order_Code']]; 

                //Note: All complete order list record
                $order_delete[] = $value['order_Code'];

                $order_id = $Order[$value['order_Code']]->id;
                $data = self::bulkOrderUpdate((object)$value,$order_id);
            }
            //Note:New  Bulk Order Insert
            // else{//new Data entry
            //     // $data = self::bulkOrderStore((object)$value);

            // }
            //Note:New  Bulk Order Insert
        }
        //Note: Delete update order list
        DB::table('bulk_orders_record')->whereIn('order_no',$order_delete)->delete();

        empty($order_delete)?Session::flash('Error', 'No Order Modify'):Session::flash('Success', 'All Upload Successfully '.count($order_delete)); 
        

        return view('order.bulkOrder');
    }    

    private function bulkOrderStore($request)
    {
        $name = $request->name;
        $phone = $request->phone_no;
        $area = $request->cluster;
        $address = $request->address;
        $promocode = '';
        $note = $request->note;
        $order_for = 'others';
        $order_platform = 'admin';
        $payment_mode = $request->payment_mode;
        $Comrade = $request->Comrade;
        $client = Client::where(['phone' => $phone])->first(); 
        $discount = $request->discount;
        if($request->user_type == 'new_user'){
            
            $ServiceProvider = ServiceProvider::where(['phone' => $phone])->first(); 
            if($ServiceProvider){
                $msg =  'Already have an account in SP panle';
            }  
            $comrades = Comrade::where(['phone' => $phone])->first(); 
            if($comrades){
                $msg =  'Already have an account in comrades panle';
            }           
        }
        
        if(!empty($client))
        {
            $user_id = $client->user_id;
            $name = $client->name;
            if($request->user_type == 'new_user')
            {
                $name = $request->name;
            }
            $order_for = 'self';
        }

        $session_order = $request->session_order;

        $category_id = $request->category_id;
        $date = $request->order_date;
        $order_time = $request->order_time;
        $time_explode = explode("-", $order_time);
        $time = $time_explode[0];
     
        
        $schedule_charge = 0;
        if((boolean)$time_explode[1] == 0)
        {
            $schedule_charge = Setting::first()->schedule_charge;
        }

        //Note: Seesion data for session data
        // $service_bit = call_user_func_array("array_merge", array_column($session_order['order_items'], 'items'));

        $service_bit = $session_order;

        $comments = $note;
        if(empty($note))
        {
            $comments = "".$name." order some ".$session_order['category_name']."";
        }
        $order_from = 'admin';
        $ref_code = isset($client->ref_code)?$client->ref_code:refCode();

        $area_charge = 0;
        if(in_array($area, Setting::where('name', 'outside_area_id')->pluck('value')->toArray()))
        {
            $area_charge = Setting::first()->area_charge;
        }
        
        $category = new Category();
        $cluster = new Cluster();

        $exists_ref_code = User::where(['ref_code' => $ref_code])->exists();

        if(!empty($ref_code))
        {
            if($exists_ref_code == false)
            {
               $msg = 'Invalid ref code.';
            }
            if(!empty(auth()->user()) && $ref_code == auth()->user()->ref_code)
            {
                $msg = 'You can not use your own ref code';
            }
        }
        //Note: random order create from db
        $order_no = randomOrderNo();
        try{
            DB::beginTransaction();

            if(empty($client))
            {//Note: new user create
                $data['name'] = $name;
                $data['phone'] = $phone;
                $otp_code = mt_rand(10000, 99999);
                $data['password'] = bcrypt(12345678);
                $data['otp_code'] = $otp_code;
                $data['ref_code'] = null;
    
                $user = User::create($data);
    
                $user->assignRole(7);
                $data['user_id'] = $user->id;
                $data['rating'] = 5;
                $data['type'] = 'client';
                $data['area'] = $area;
                $data['address'] = $address;
                $data['company_name'] = $data['name'];
                //user to client create
                $client = Client::create($data);
                $user_id = $client->user_id;

                if(env('CHECK_SMS_STATUS'))
                {
                    $msg = 'Your login phone number is: ' . $user->phone . ' & password is: 12345678. Do not share it with anyone.';
                    //Note: sms on
                    SMS::send($user->phone, $msg);
                }
            }

            //Note: Order generate
            $order = Order::create([
                'order_no' => $order_no,
                'category_id' => $category_id,
                'category_name' => $category->find($category_id)->name,
                'user_id' => $user_id,
                'date' => date('Y-m-d', strtotime($date)),
                'time' => $time,
                'name' => $name,
                'phone' => $phone,
                'area' => $cluster->find($area)->name,
                'address' => $address,
                'comments' => $comments,
                'order_from' => $order_from,
                'order_for' => $order_for,
                'order_platform' => $order_platform,
                'ref_code' => null,
                'promocode' => $promocode,
                'status' => 0,
                'emergency_charge' => $schedule_charge,
                'outside_charge' => $area_charge,
                'discount' => $discount,
            ]);
            //Note: service bit calculation
            if (count($service_bit) > 0) {
                foreach ($service_bit as $serviceBit) {
                    $order_items[] = self::storeBulkService($serviceBit, $order);
                };               
                OrderItem::insert($order_items);
            }

            if(!empty($promocode))
            {
                $total_price = $order->total_price;
                $discount =  PromoUserController::applyPromocode($promocode, $phone, $total_price);

                $promouser = PromoUser::where(['phone' => $phone, 'promocode' => $promocode])->first();
                $promouser->update([
                    'uses_count' => ($promouser->uses_count + 1)
                ]);

                Order::where(['order_no' => $order->order_no])->update([
                    'discount' => $discount
                ]);
            }

            if(QuickOrder::where(['phone' => $phone])->exists())
            {
                QuickOrder::where(['phone' => $phone])->update([
                    'status' => 1
                ]);
            }
            
            //Note: Add Notification table in every order on.
            NotificationController::customOrderSubmitNotification($order);
            
            // Note: this part is for notification on
            if (broadcast(new OrderEvent(new OrderResource($order)))) { 
                //$on_signal = onSignal($order), 'on_signal' => $on_signal;
                $on_signal = onSignal($order);
                $msg = 'Your order has been placed';
            }
            //Note: Process complete order
            $processOrdr = self::processOrder($request,$order->id);
            DB::commit();

            $msg = 'order complete ';
        }catch(\Exception $e){
            DB::rollback();
            $msg = $e;
        }
        
        return $msg;
        
    }

    private function bulkOrderUpdate($request, $id)
    {
        $order = Order::find($id);
        $session_order = $request->session_order;
        $category_id = $request->category_id;
        $promocode = null;
        $phone = $request->phone_no;
        $category = new Category();
        if(empty($session_order))
        {
            $msg = 'You can not remove all of service. Please select at least one service.';
        }
        $service_bit = $session_order;

        try{
            DB::beginTransaction();
            $data['category_id'] = $category_id;
            $data['category_name'] = $category->find($category_id)->name;
            $data['outside_charge'] = $request->outside_charge;
            $data['discount'] = $request->discount;
            $data['comments'] = $request->note;
            
            $order->update($data);

            if (count($service_bit) > 0) {
                $order_items = [];
                OrderItem::where('order_id', $order->id)->delete();
                foreach ($service_bit as $serviceBit) {
                    $order_items[] = $this->storeBulkService($serviceBit, $order);
                }
                OrderItem::insert($order_items);
            }
            
            $processOrdr = self::processOrder($request,$id);

            DB::commit();
            $msg = 'Your order has been updated';
        }catch(\Exception $e){
            DB::rollback();
            $msg = $e;
        }
        return $msg;
    }

    private function processOrder($request, $id){
        try{
            //Note: Add To new function
            $servicePro_info = $request->service_provider;
            $orderSysControlr = new OrderSystemController;            
            // Note: Assign Order to Sp
            $order_assign = $orderSysControlr->allowcatingServiceProvider($id,$servicePro_info->id);
            if(!empty($Comrade)){
                //Note: Assign to comrade
                $order_assign = $orderSysControlr->changeComrade($id, $Comrade->id);
            }


            //Note: Start work by Esp , Fsp & Comrade
            $sp_espObj = new Request;
            $sp_espObj->id = $id;

            $start_work = $orderSysControlr->startServicing($sp_espObj);

            //Note: Order Item status comeplete create.
            $ordrItm_contrl = new OrderItemController;
            $order_itemData = OrderItem::where('order_id',$id)->get();
            foreach ($order_itemData as $key => $value) {
                $ordrItm_contrl->itemStatusChange($value->id);
            }

            // Note: Mark As Finished this order
            $orderSysControlr->finishServicing($sp_espObj);//Note: Use sp_esp obj

            //Note: cash payment received part
            if($request->payment_mode=='Cash'){
                $compelete_work = $orderSysControlr->paymentReceive($sp_espObj);
            }
            
            //Note: add to new function 
            $msg = 'Success!!';    
        }catch(\Exception $e){
            $msg = $e;
        }
        return $msg;
    }

    private function storeBulkService($serviceBit, $order)
    {
        $type = (empty($serviceBit['type'])) ? 'regular' : $serviceBit['type'];
        $commission = ($serviceBit['commission'] == 0) ? ServiceBit::find($serviceBit['id'])->price : $serviceBit['commission'];

        return [
            'order_id' => $order->id,
            'service_id' => $serviceBit['service_id'],
            'service_name' => ($type == 'regular') ? Service::find($serviceBit['service_id'])->name : $serviceBit['service_name'],
            'service_bit_id' => $serviceBit['id'],
            'service_bit_name' => ($type == 'regular') ? ServiceBit::find($serviceBit['id'])->name : $serviceBit['service_bit_name'],
            'price' =>  $serviceBit['price'],
            'additional_price' => $serviceBit['price'],
            'commission' => $commission, 
            'quantity' => ($serviceBit['qty'] > 0) ? $serviceBit['qty'] : 0,
            'total_price' => 0, // only for mutate in model
            'type' => $type,
        ];
    }

}
