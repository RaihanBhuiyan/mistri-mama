<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\OrderEvent;
use App\Events\OrderPaymentEvent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Promo\PromoUserController;
use App\Http\Controllers\SiteConfigsController;
use App\Http\Resources\ComradeJobsResource;
use App\Http\Resources\OrderDetailsResource;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\UserResource;
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

class OrderController extends Controller
{

    public function orders()
    {
        return new OrderResource(Order::all());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['orders'] = Order::where('status', '<=', 2)->orderBy('id', 'desc')->get();
        return view('order.index', $data);
    }

    public function onGoingOrders()
    {
        $data['orders'] = Order::where('status', '>=', 3)->where('status', '<=', 4)->orderBy('id', 'desc')->get();
        return view('order.ongoing_orders', $data);
    }

    public function orderHistory()
    {
        $data['orders'] = Order::where('status', '>=', 5)->orderBy('id', 'desc')->get();
        return view('order.order_history', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $date = Carbon::now()->subDays(30);
        for($i = 0; $i <= 44; $i++)
        {
            $dataArrays[] = [
                'date' => $date->format('d-F-Y'),
                'day' => $date->format('l')
            ];
            $date = $date->addDays(1);
        }
        $data['site_config'] = SiteConfigsController::siteConfigs();
        $data['site_config']['schedule_dates'] = $dataArrays;
        
        Session::forget('order');
        $data['clusters'] = Cluster::all();
        $data['promocodes'] = Promocode::where('status', 1)->orderBy('id', 'desc')->get();
        $data['categories'] = Category::all();


        return view('order.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required|size:11|regex:/(01)[0-9]{9}/',
            'area' => 'required|exists:clusters,id',
        ]);
        
        $getRoleNames = (!empty(auth()->user())) ? auth()->user()->getRoleNames()->first() : "";
        $category_id = $request->input('category_id');
        $user_id = $request->input('user_id');
        $date = $request->input('date');
        $time = $request->input('time');
        $services = $request->input('services');
        $service_bit = $request->input('service_bit');
        $name = $request->input('name');
        $phone = $request->input('phone');
        $area = $request->input('area');
        $address = $request->input('address');
        $comments = $request->input('comments');
        $order_from = $request->input('order_from');
        $order_for = $request->input('order_for');
        $ref_code = $request->input('ref_code');
        $promocode = $request->input('promocode');
        $order_platform = $request->input('order_platform') ? $request->input('order_platform') : 'web';
        $schedule_charge = ($request->input('schedule_charge') != 0) ? Setting::first()->schedule_charge : 0;
        $area_charge = ($request->input('area_charge') != 0) ? Setting::first()->area_charge : 0;
        $custom_service_charge = (!empty($request->input('custom_service_charge'))) ? $request->input('custom_service_charge') : 0;
        

        $roles = Role::where('name', '!=', 'client')->pluck('name')->toArray();
        $getRole = User::where(['phone' => $phone])->first();
        if(!empty($getRole))
        {
            if (in_array($getRole->getRoleNames()->first(), $roles))
            {
                return response()->json(['message' => 'Phone number already exists.'], 400);
            }
        }

        $discount = 0; 

        if(strtotime(date('d-F-Y h:00 A')) > strtotime($date.' '.explode(" - ", $time)[0]))
        {
            return response()->json(['message' => 'Please select time after '.date('h:00 A').''], 400);
        }

        if($order_from == 'login')
        {
            $area_charge = 0;
            if(in_array($area, Setting::where('name', 'outside_area_id')->pluck('value')->toArray()))
            {
                $area_charge = Setting::first()->area_charge;
            }
        }
        $category = new Category();
        $cluster = new Cluster();

        $exists_ref_code = User::where(['ref_code' => $ref_code])->exists();

        if(!empty($ref_code))
        {
            if($exists_ref_code == false)
            {
                return response()->json(['message' => 'Invalid ref code.'], 400);
            }
            if(!empty(auth()->user()) && $ref_code == auth()->user()->ref_code)
            {
                return response()->json(['message' => 'You can not use your own ref code'], 400);
            }
        }

        $order_no = randomOrderNo();
        
        try{
            DB::beginTransaction();

            $client = Client::where(['phone' => $phone])->first();

            if(empty($client))
            {
                $data['name'] = $name;
                $data['phone'] = $phone;
                $otp_code = mt_rand(10000, 99999);
                $data['password'] = bcrypt(12345678);
                $data['otp_code'] = $otp_code;
                $data['ref_code'] = refCode();
    
                $user = User::create($data);
    
                $user->assignRole(7);
                $data['user_id'] = $user->id;
                $data['rating'] = 5;
                $data['type'] = 'client';
                $data['area'] = $area;
                $data['address'] = $address;
                $data['company_name'] = $name;
                
                $client = Client::create($data);
                $user_id = $client->user_id;

                if(env('CHECK_SMS_STATUS'))
                {
                    $msg = 'Your login phone number is: ' . $user->phone . ' & password is: 12345678. Do not share it with anyone.';
                    SMS::send($user->phone, $msg);
                }
            }

            $order = Order::create([
                'order_no' => $order_no,
                'category_id' => $category_id,
                'category_name' => $category->find($category_id)->name,
                'user_id' => (empty($client)) ? $user_id : $client->user_id,
                'date' => date('Y-m-d', strtotime($date)),
                'time' => $time,
                'name' => $name,
                'phone' => $phone,
                'area' => $area_name = $cluster->find($area)->name,
                'address' => $address,
                'comments' => $comments,
                'order_from' => $order_from,
                'order_platform' => $order_platform,
                'order_for' => $order_for,
                'ref_code' => $ref_code,
                'promocode' => $promocode,
                'status' => 0,
                'emergency_charge' => $schedule_charge,
                'outside_charge' => $area_charge,
                'discount' => $discount,
            ]);

            if (count($service_bit) > 0) {
                foreach ($service_bit as $serviceBit) {
                    $order_items[] = $this->storeService($serviceBit, $order);
                }
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
            if($order_platform =='android'){
                $checkExitsOrder = Order::where(['order_platform'=>'android','user_id'=>$client->user_id])->count();
                if($checkExitsOrder < 2){
                    $discount = $discount + 20;
                    Order::where(['order_no' => $order->order_no])->update([
                        'discount' => $discount
                    ]);
                }
            }
            

            // this terms only work for service provider own order and regular order when accept than order system will be created.
            if(!empty(auth()->user()->serviceProvider)){
                if(auth()->user()->serviceProvider->type == 'fsp' || auth()->user()->serviceProvider->type == 'esp'){

                    if(auth()->user()->serviceProvider->type == 'fsp'){
                        $data['state'] = 1;
                    }else{
                        $data['state'] = 0;
                    }

                    $data['order_id'] = $order->id ;
                    $data['service_provider_id'] = auth()->user()->serviceProvider->id;
                    $data['sp_cat'] = auth()->user()->serviceProvider->category;
                    $data['commission'] = 0;

                    if (OrderSystem::create($data)) {

                        $order_new = Order::find($order->id );
                        $order_new->status = 2;
                        $order_new->customize_charge = $custom_service_charge;
                        $order_new->accept_time = Carbon::now()->toDateTimeString();
                        $order_new->save(); 
                    }
                    $on_signal =  'One signal not send because this order for fsp or esp'; 

                }else{
                    $on_signal = onSignal($order);  
                }
            }else{
                 $on_signal = onSignal($order);  
            }

            NotificationController::orderSubmitNotification($order);
            
            DB::commit();
            
            if (broadcast(new OrderEvent(new OrderResource($order)))) {
                return response()->json(['message' => 'Your order has been placed', 'order_id' => $order->id , 'on_signal' =>  $on_signal], 200);
            }
            return response()->json(['message' => 'Your order has been placed', 'order_id' => $order->id], 200);

        }catch(\Exception $e){
            return $e;
            DB::rollback();
            return response()->json(['message' => 'Your order not placed'], 400);
        }
    }
    
    public function customOrderStore(Request $request)
    {
        $request->validate([
            'user_type' => 'required',
            'name' => 'required',
            'user_search' => 'required_if:user_type,==,old_user',
            'phone_no' => 'required|size:11|regex:/(01)[0-9]{9}/',
            'cluster' => 'required|exists:clusters,id',
            'address' => 'required',
            'order_date' => 'required',
            'order_time' => 'required',
        ]); 

        if (Session::has('order') == false)
        {
            return response()->json(['message' => 'Please select an category for chose your service'], 400);
        }
        
        $name = $request->input('name');
        $phone = $request->input('phone_no');
        $area = $request->input('cluster');
        $address = $request->input('address');
        $promocode = $request->input('promocode');
        $note = $request->input('note');
        $order_for = 'others';
        $order_platform = $request->input('order_platform') ? $request->input('order_platform') : 'admin';

        $client = Client::where(['phone' => $phone])->first(); 
        if($request->input('user_type') == 'new_user'){
            
            $ServiceProvider = ServiceProvider::where(['phone' => $phone])->first(); 
            if($ServiceProvider){
                return response()->json(['message' => 'Already have an account in SP panle'], 400);
            }  
            $comrades = Comrade::where(['phone' => $phone])->first(); 
            if($comrades){
                return response()->json(['message' => 'Already have an account in comrades panle'], 400);
            }           
        }
        
        if(!empty($client))
        {
            $user_id = $client->user_id;
            $name = $client->name;
            if($request->input('user_type') == 'new_user')
            {
                $name = $request->input('name');
            }
            $order_for = 'self';
        }

        $session_order = Session::get('order');
        $category_id = $session_order['category_id'];
        $date = $request->input('order_date');
        $order_time = $request->input('order_time');
        $time_explode = explode("--", $order_time);
        $time = $time_explode[0];
        
        // if((strtotime(date('d-F-Y')) == strtotime($date)) && (strtotime(date('d-F-Y h:00 A')) > strtotime($date.' '.explode(" - ", $time)[0])))
        // {
        //     return response()->json(['message' => 'Please select time after '.date('h:00 A').''], 400);
        // }
        
        $schedule_charge = 0;
        if((boolean)$time_explode[1] == 0)
        {
            $schedule_charge = Setting::first()->schedule_charge;
        }

        $service_bit = call_user_func_array("array_merge", array_column($session_order['order_items'], 'items'));

        $comments = $note;
        if(empty($note))
        {
            $comments = "".$name." order some ".$session_order['category_name']."";
        }
        $order_from = 'admin';
        $ref_code = $request->input('ref_code');

        $area_charge = 0;
        if(in_array($area, Setting::where('name', 'outside_area_id')->pluck('value')->toArray()))
        {
            $area_charge = Setting::first()->area_charge;
        }
        
        $discount = 0;

        $category = new Category();
        $cluster = new Cluster();

        $exists_ref_code = User::where(['ref_code' => $ref_code])->exists();

        if(!empty($ref_code))
        {
            if($exists_ref_code == false)
            {
                return response()->json(['message' => 'Invalid ref code.'], 400);
            }
            if(!empty(auth()->user()) && $ref_code == auth()->user()->ref_code)
            {
                return response()->json(['message' => 'You can not use your own ref code'], 400);
            }
        }
        
        $order_no = randomOrderNo();
        try{
            DB::beginTransaction();

            if(empty($client))
            {
                $data['name'] = $name;
                $data['phone'] = $phone;
                $otp_code = mt_rand(10000, 99999);
                $data['password'] = bcrypt(12345678);
                $data['otp_code'] = $otp_code;
                $data['ref_code'] = refCode();
    
                $user = User::create($data);
    
                $user->assignRole(7);
                $data['user_id'] = $user->id;
                $data['rating'] = 5;
                $data['type'] = 'client';
                $data['area'] = $area;
                $data['address'] = $address;
                $data['company_name'] = $data['name'];
                
                $client = Client::create($data);
                $user_id = $client->user_id;

                if(env('CHECK_SMS_STATUS'))
                {
                    $msg = 'Your login phone number is: ' . $user->phone . ' & password is: 12345678. Do not share it with anyone.';
                    SMS::send($user->phone, $msg);
                }
            }

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
                'ref_code' => $ref_code,
                'promocode' => $promocode,
                'status' => 0,
                'emergency_charge' => $schedule_charge,
                'outside_charge' => $area_charge,
                'discount' => $discount,
            ]);

            
                
            if (count($service_bit) > 0) {
                foreach ($service_bit as $serviceBit) {
                    $order_items[] = $this->storeService($serviceBit, $order);
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
            
            NotificationController::customOrderSubmitNotification($order);
            DB::commit();

            if (broadcast(new OrderEvent(new OrderResource($order)))) { 
                //$on_signal = onSignal($order), 'on_signal' => $on_signal;
                $on_signal = onSignal($order);
                return response()->json(['message' => 'Your order has been placed', 'on_signal' => $on_signal, 'order_id' => $order->id ], 200);
            }
        }catch(\Exception $e){
            return $e;
            DB::rollback();
        }
        return response()->json(['message' => 'Your order not placed'], 400);
    }

    public function storeService($serviceBit, $order)
    {
        $type = (empty($serviceBit['type'])) ? 'regular' : $serviceBit['type'];
        $commission = ($order['order_from'] == 'esp') ? ServiceBit::find($serviceBit['id'])->price : ServiceBit::find($serviceBit['id'])->commission;
        return [
            'order_id' => $order->id,
            'service_id' => $serviceBit['service_id'],
            'service_name' => ($type == 'regular') ? Service::find($serviceBit['service_id'])->name : $serviceBit['service_name'],
            'service_bit_id' => $serviceBit['id'],
            'service_bit_name' => ($type == 'regular') ? ServiceBit::find($serviceBit['id'])->name : $serviceBit['service_bit_name'],
            'price' => ($type == 'regular') ? ServiceBit::find($serviceBit['id'])->price : $serviceBit['price'],
            'additional_price' => ($type == 'regular') ? ServiceBit::find($serviceBit['id'])->price : $serviceBit['price'],
            'commission' => ($type == 'regular') ? $commission : $serviceBit['service_provider_price'],
            'quantity' => ($serviceBit['qty'] > 0) ? $serviceBit['qty'] : 0,
            'total_price' => 0, // only for mutate in model
            'type' => $type,
        ];
    }

    public function customServiceCartToOrder(Request $request, $order_id)
    {
        $request->validate([
            'service_name' => 'required',
            'service_bit_name' => 'required',
            'price' => 'required|numeric',
            'service_provider_price' => 'required|numeric|lte:price',
            'quantity' => 'required|numeric',
        ]);
        
        try{
            DB::beginTransaction();
            OrderItem::create([
                'order_id' => $order_id,
                'service_id' => NULL,
                'service_name' => $request->service_name,
                'service_bit_id' => NULL,
                'service_bit_name' => $request->service_bit_name,
                'price' => $request->price,
                'additional_price' => $request->additional_price,
                'quantity' => $request->quantity,
                'total_price' => 0, // only for mutate in model
            ]);
            DB::commit();
            toastr()->success('Service added successfull');
        }catch(\Exception $e){
            toastr()->error('Service added failed');
            DB::rollback();
        }
        return redirect()->back();
    }

    public function addNewServicBit(Request $request, $id)
    {
        $order = Order::find($id);
        if (count($request->data) > 0) {
            foreach ($request->data as $serviceBit) {
                $order_items[]  = $this->storeService($serviceBit, $order);
            }
            
            OrderItem::insert($order_items);
            
            if(env('CHECK_SMS_STATUS'))
            {
                $implode_services = OrderItem::where('order_id', $order->id)->groupBy('service_name')->pluck('service_name')->implode(', ');
                SMS::send($order->phone, "A new service ".$implode_services." added with your current order.");
            }
        }

        $order = OrderDetail::find($id);
        return OrderItemResource::collection($order->orderItems);
    }

    public function getServices($category_id, $for)
    {
        if($for == 'create')
        {
            Session::forget('order');
        }
        if($for == 'edit')
        {
            $data['exists_order'] = Session::get('order');
        }
        // return $data;
        $data['category_id'] = $category_id;
        $data['services'] = Service::where('category_id', $category_id)->get();
        return response()->json(view('component.services', $data)->render());
    }

    public function getServiceBit($category_id, $service_id, $type)
    {
        $data['order_id'] = 0;
        $data['ordered_items'] = [];
        if (Session::has('order') == true)
        {
            $data['order_id'] = Session::get('order.order_id');
            $session_order_items = Session::get('order.order_items');
            if(!empty($session_order_items[$service_id]['items']))
            {
                $data['ordered_items'] = $session_order_items[$service_id]['items'];
            }
        }
        
        // here type is custom service than service_id parameter provide (category id) and type is regular service than service_id parameter provide (service id)
        if($type == 'custom')
        {
            $data['order_id'] = Session::get('order.order_id');
            $data['category_id'] = $category_id;
            return response()->json(view('component.customserviceform', $data)->render());
        }


        $data['service_bits'] = ServiceBit::where('service_id', $service_id)->get();
        return response()->json(view('component.servicebits', $data)->render());
    }

    public function cartToServiceBit(Request $request)
    {
        if($request->type == 'regular')
        {
            $service_bit_id = $request->service_bit_id;
            $service_id = $request->service_id;
            $qty = $request->qty;

            $service = Service::find($service_id);
            $service_bit = ServiceBit::find($service_bit_id);

            $category_id = $service->category_id;
            $category_name = $service->category->name;
            $service_id = $service_bit->service_id;
            $service_name = $service_bit->service->name;
            $service_bit_name = $service_bit->name;
            $service_bit_price = $service_bit->price;
            $service_provider_price = $service_bit->commission;
            $unit_remarks = $service_bit->unit_remarks;
            $unit_type = $service_bit->unit_type;
            $brief = $service_bit->brief;
        }
        
        if($request->type == 'custom')
        {
            $request->validate([
                'price' => 'required|numeric',
                'service_provider_price' => 'required|numeric|lte:price',
            ]);

            $category_id = $request->category_id;
            $category = Category::find($category_id);
            $category_name = $category->name;
            $service_id = $category_id.'00';
            $service_name = "Custom Service";
            $service_bit_id = $category_id.''.rand(100,999);
            $service_bit_name = $request->service_bit_name;
            $qty = $request->qty;
            $service_bit_price = $request->price;
            $service_provider_price = $request->service_provider_price;
            $unit_remarks = NULL;
            $unit_type = NULL;
            $brief = NULL;
        }
        if (Session::has('order') == false)
        {
            Session::put('order', [
                'category_id' => $category_id,
                'category_name' => $category_name,
                'order_from' => 'admin',
                'status' => 0,
            ]);
        }
        if (Session::has('order') == true)
        {
            $order_items = Session::get('order.order_items');
            $order_items[$service_id]['items'][$service_bit_id] = [
                'service_id' => $service_id,
                'service_name' => $service_name,
                'id' => $service_bit_id,
                'service_bit_name' => $service_bit_name,
                'qty' => $qty,
                'price' => $service_bit_price,
                'service_provider_price' => $service_provider_price,
                'total_price' => ($service_bit_price * $qty),
                'unit_remarks' => $unit_remarks,
                'unit_type' => $unit_type,
                'brief' => $brief,
                'type' => $request->type,
            ];
            
            Session::put('order.order_items', $order_items);
            $session_order_item_total_price = Session::get('order.order_items')[$service_id]['items'][$service_bit_id]['total_price'];
            Session::put('order.total_price', (Session::get('order.total_price') + $session_order_item_total_price));
        }
        return Session::get('order');
    }

    public function removeServiceBitToCart(Request $request)
    {
        $order_id = $request->order_id;
        $service_bit_id = $request->service_bit_id;
        $service_id = $request->service_id;
        $session_order_item_total_price = 0;

        if($order_id > 0)
        {
            if(OrderItem::where(['order_id' => $order_id, 'service_id' => $service_id, 'service_bit_id' => $service_bit_id, 'status' => 1])->exists())
            {
                return response()->json(['message' => 'Service provider already finished this worked.'], 400);
            }
        }
        $order = Session::get('order');
        $session_order_item_total_price = Session::get('order.order_items')[$service_id]['items'][$service_bit_id]['total_price'];
        unset($order['order_items'][$service_id]['items'][$service_bit_id]);
        
        if(sizeof($order['order_items'][$service_id]['items']) == 0)
        {
            unset($order['order_items'][$service_id]);
        }
        Session::put('order', $order);
        Session::put('order.total_price', (Session::get('order.total_price') - $session_order_item_total_price));
        
        return Session::get('order');
    }

    public function retriveSelectedServiceBit($service_id)
    {
        $data['selected_service_bits'] = [];
        if (Session::has('order') == true)
        {
            $session_order_items = Session::get('order.order_items');
            if(!empty($session_order_items[$service_id]['items']))
            {
                $data['selected_service_bits'] = $session_order_items[$service_id]['items'];
            }
        }
        
        $data['service_bits'] = ServiceBit::where('service_id', $service_id)->get();
        return response()->json(view('component.selected_service_bits', $data)->render());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);
        if ($order) {
            return view('order.show', compact('order'));
        } else {
            return response(['message' => 'Something worng']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        Session::forget('order');
        $data['clusters'] = Cluster::all();
        $data['promocodes'] = Promocode::where('status', 1)->orderBy('id', 'desc')->get();
        $data['categories'] = Category::all();
        $data['site_config'] = SiteConfigsController::siteConfigs();

        $data['order'] = Order::find($id);
        
        Session::put('order', [
            'order_id' => $id,
            'category_id' => $data['order']->category_id,
            'category_name' => $data['order']->category_name,
        ]);

        $total_price = 0;
        foreach($data['order']->orderItems as $order_item)
        {
            $order_items[$order_item->service_id]['items'][$order_item->service_bit_id] = [
                'service_id' => $order_item->service_id,
                'service_name' => $order_item->service_name,
                'id' => $order_item->service_bit_id,
                'service_bit_name' => $order_item->service_bit_name,
                'qty' => $order_item->quantity,
                'price' => $order_item->price,
                'service_provider_price' => $order_item->commission,
                'total_price' => ($order_item->price * $order_item->quantity),
                'unit_remarks' => NULL,
                'unit_type' => NULL,
                'brief' => NULL,
                'type' => $order_item->type,
            ];
            $total_price = $total_price + $order_items[$order_item->service_id]['items'][$order_item->service_bit_id]['total_price'];
        }
        Session::put('order.order_items', $order_items);
        Session::put('order.total_price', $total_price);
        
        $data['order']['total_price'] = $total_price;
        $data['order']['date_with_day'] = Carbon::parse($data['order']->date)->format('d-F-Y-l');
        return view('order.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        $session_order = Session::get('order');
        $category_id = $session_order['category_id'];
        $promocode = $request->input('promocode');
        $phone = $request->input('phone_no');
        
        if(empty($session_order['order_items']))
        {
            return response()->json(['message' => 'You can not remove all of service. Please select at least one service.'], 400);
        }
        $service_bit = call_user_func_array("array_merge", array_column($session_order['order_items'], 'items'));

        $data = $request->validate([
            'address'     => 'string',
            'phone_no' => 'required',
        ]);

        try{
            DB::beginTransaction();
            $data['comments'] = $request->note;
            
            $order->update($data);

            if (count($service_bit) > 0) {
                $order_items = [];
                OrderItem::where('order_id', $order->id)->delete();
                foreach ($service_bit as $serviceBit) {
                    $order_items[] = $this->storeService($serviceBit, $order);
                }
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
                    'discount' => $discount,
                    'promocode' => $promocode,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Your order has been updated'], 200);
        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Your order has been update failed'], 400);
        }
    }

    public function order_item_quantity_edit(Request $request)
    {
        $order = OrderItem::find($request->orderid);
        $data = $request->validate([
            'quantity' => 'numeric'
        ]);

        if ($order->update($data)) {

            return "Order Updated.";
        } else {

            return "Order Failed.";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // Order accept on admin panel
    public function order_accept($order_id, $status)
    {
        if(empty($status))
        {
            toastr()->error('Order has no status');
        }

        $statusArray = [
            'accept' => 1,
            'fraud' => 7,
        ];

        try{
            DB::beginTransaction();

            $order = Order::find($order_id);
            $order->status = $statusArray[$status];
            $order->save();
            
            DB::commit();
            toastr()->success('Order Status update successfull');
        }catch(\Exception $e){
            DB::rollback();
            toastr()->error('Order accept failed');
        }
        return redirect()->back();
    }

    // Order cancel on admin panel
    public function order_cancel(Request $request, $order_id)
    {
        $request->validate([
            'order_cancel_note' => 'required|max:100',
        ]);
        try{
            DB::beginTransaction();

            $order = Order::find($order_id);
            $order->status = 6;
            $order->cancel_note = $request->order_cancel_note;

            $order->save();
            toastr()->success('Order Cancel');
            DB::commit();
        }catch(\Exception $e){
            toastr()->error('Order Cancel failed');
            DB::rollback();
        }
        return redirect()->back();
    }


    public function checkPaymentWaitingOrder()
    {
        $order = [];
        $userId = auth()->user()->id;
        $order = OrderDetail::where(['status' => 4, 'state' => 3, 'pay_type' => NULL, 'user_id' => $userId])->first();
        if ($order) {
            return new OrderDetailsResource($order);
        }
        return response(['data' => $order]);
    }

    public function payOffline(Request $request)
    {
        $order =  Order::find($request->orderId);

        $num = $order->phone;
        $text = "Click below link to pay your Mistri Mama bill. Link - " . $request->payUrl;
        try{
            DB::beginTransaction();
            
            SMS::send($num, $text);

            DB::commit();
            
            return response()->json(['message' => 'A payment link has been send to your client.'], 200);

        }catch(\Exception $e){
            DB::rollback();
        }
        return response()->json(['message' => 'Payment link not available'], 400);
    }

    public function quickOrder(Request $request)
    {
        $data = $request->validate([  
            'phone' => 'required|size:11|regex:/(01)[0-9]{9}/', 
        ]);
        
        $data['name'] = $request->name;
        $data['address'] = $request->address;
        $data['comments'] = $request->comments;
        $data['request_service'] = $request->request_service;
        
        $order_datetime = $request->request_datetime;

        $data['area_id'] = (!empty($request->area_id)) ? $request->area_id : NULL;
        $data['date'] = (!empty($order_datetime)) ? date('Y-m-d', strtotime($order_datetime)) : date('Y-m-d');
        $data['time'] = (!empty($order_datetime)) ? ''.date('h:00 A', strtotime($order_datetime)).' - '.date('h:00 A', strtotime('+1 hour',strtotime($order_datetime))).'' : ''.date('h:00 A', strtotime('+3 hour')).' - '.date('h:00 A', strtotime('+4 hour')).'';

        if (QuickOrder::create($data)) {
            NotificationController::quickOrderNotification($data);
            return response()->json(['message' => 'You have placed an Quick Order. We will contact you as soon as posible.'], 200);
        } else {
            return response()->json(['message' => 'Something went worng'], 400);
        }
    }
    public function quickOrderHistory()
    {
        $quick_order = QuickOrder::where(['phone' => auth()->user()->phone, 'status' => 0])->orderBy('id', 'desc')->get();
        if(!empty($quick_order))
        {
            return response()->json($quick_order);
        }
        return response()->json(NULL);
    }

    public function userSearch($val = 0)
    {
        $client = Client::select('user_id', 'name', 'phone')->where('phone', 'like', '%' . $val . '%')->orWhere('name', 'like', '%' . $val . '%')->take(10)->orderBy('id', 'desc')->get();
        if(!empty($client))
        {
            return $client;
        }
        return NULL;
    }

    public function userSelected($id)
    {
        return new UserResource(User::where('id', $id)->first());
    }

    public function qtyUpdate(Request $request, OrderItem $orderItem)
    {
        if ($request->has('order_id')) {
            $order_id = $request->order_id;
        } else {
            $order_id = Session::get('order_id');
        }

        $orderItem = $orderItem->where('service_bit_id', $request->id)->where('service_id', $request->service_id)->where('order_id', $order_id)->first();

        $orderItem->quantity = $request->qty;
        $orderItem->total_price = 0;
        /** this is only for inserting total_price , the price will be calculated in mutator  */
        if ($orderItem->save()) {
            $data['unit_point_adtnl'] = ServiceBit::find($request->id)->additional_unit_remarks;
            $data['total_price']      = OrderItem::where('service_bit_id', $request->id)->where('service_id', $request->service_id)->where('order_id', $order_id)->sum('total_price');
            return $data;
        } else {
            return 'false';
        }
    }

    public function TotalPrice()
    {
        $total_price = OrderItem::where('order_id', Session::get('order_id'))->sum('total_price');
        return $total_price;
    }


}
