<?php

namespace App\Http\Controllers\Client;

use App\Client;
use App\Http\Controllers\Auth\Api\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\UserOrderHistory;
use App\Http\Resources\ClientResource;
use App\Order;
use App\OrderDetail;
use App\SMS;
use App\User;
use App\MfsNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$client = Client::all();
        return view('client.client');
    }

    public function clientsFilter(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $client = Client::orderBy('id', 'desc');
        if($from_date != '' && $to_date != '')
        {
            $client->whereBetween('created_at', [$from_date, $to_date]);
        }
        return datatables()->eloquent($client)->addColumn('client_photo', function ($client) {
            return $client->photo_url;
        })->addColumn('cluster_name', function ($client) {
            return (isset($client->cluster->name) ? $client->cluster->name : '');
        })->addColumn('last_order_info', function ($client) {
            return (!empty($client->order->first()) ? $client->order->first()->id : '');
        })->addColumn('total_order', function ($client) {
            return $client->order->count();
        })->addColumn('total_cancel_order', function ($client) {
            return $client->orderCancel->count();
        })->addColumn('popular_services', function ($client) {
            return (isset($client->popularservice->first()->name) ? $client->popularservice->first()->name : '');
        })->addColumn('client_total_spent', function ($client) {
            return $client->client_total_spent;
        })->addColumn('average_client_spent', function ($client) {
            return $client->average_client_spent;
        })->addColumn('ref_code', function ($client) {
            return $client->user->ref_code;
        })->addColumn('total_ref_order', function ($client) {
            return $client->total_ref_order;
        })->addColumn('total_earn_point', function ($client) {
            return $client->total_earn_point;
        })->addColumn('available_reward_point', function ($client) {
            return $client->available_reward_point;
        })->addColumn('available_reward_balance', function ($client) {
            return $client->available_reward_balance;
        })->addColumn('total_cashout', function ($client) {
            return $client->total_cashout;
        })->addColumn('status', function ($client) {
            return ['status' => $client->user->status, 'user_id' => $client->user_id];
        })->toJson();
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
        if (!$request->has('password')) {
            $request->merge(['password' => '12345678', 'password_confirmation' => '12345678']); // password forcefully add.(if admin add any user)
        }
        $data = $request->validate([
            'name'       => 'required|max:100',
            'phone'      => 'numeric|required|unique:users,phone',
            'email'      => 'max:100|email|unique:users,email',
            'password'   => 'required',
            'area'       => 'string|nullable',
            'address'    => 'string|nullable',
            'location'   => 'string|nullable',
            'mfs'        => 'string|nullable',
            'mfs_number' => 'numeric|nullable',
            'type'       => 'string|nullable',
            'remarks'    => 'string|nullable',
            'company_name'    => 'string|nullable',
            'company_logo'    => 'string|nullable',
        ]);


        if ($request->has('photo')) {
            $data['photo'] = base64_to_image($request->photo, 'upload');
        }

        if ($request->has('company_logo')) {
            $data['company_logo'] = base64_to_image($request->company_logo, 'upload');
        }

        if (!$request->has('type')) {
            $data['type'] = 'client';
        }
        
        try{
            DB::beginTransaction();

            $registeredUser = RegisterController::create($data, 1);
            if ($registeredUser) {
                $registeredUser->assignRole(7);
                $data['user_id'] = $registeredUser->id;
                $client = Client::create($data);
                DB::commit();
                if ($client) {

                    if (auth()->check() && auth()->user()->hasRole('admin')) {
                        toastr()->success('New user create successfully');
                        return back();
                    }
                    $smssend = SMS::send($registeredUser->phone, "Your phone number varification code is " . $registeredUser->otp_code);
                    if ($smssend) {
                        return response(['message' => 'success']);
                    }

                }
            } 
        }catch(\Exception $e){
            DB::rollback();
        }
        return response(['message' => 'Something went worng']);
        
        
    }

    public function singUpWithMFSnumber(Request $request)
    {
        if (!$request->has('password')) {
            $request->merge(['password' => '12345678', 'password_confirmation' => '12345678']); // password forcefully add.(if admin add any user)
        }
        $data = $request->validate([
            'name'       => 'required|max:100',
            'phone'      => 'numeric|required|unique:users,phone',
            'email'      => 'max:100|email|unique:users,email',
            'password'   => 'required',
            'area'       => 'required|exists:clusters,id',
            'mfs_type'   => 'string|nullable',
            'mfs_no'     => 'numeric|nullable',
        ]);

        try{
            DB::beginTransaction();

            $registeredUser = RegisterController::create($data, 1);
            if ($registeredUser) {
                $registeredUser->assignRole(7);
                $data['user_id'] = $registeredUser->id;
                $data['company_name'] = $registeredUser->name;
                $client = Client::create($data);
                DB::commit();
                return response(['message' => 'Sign up successfull', 200]);
            } 
        }catch(\Exception $e){
            DB::rollback();
        }
        return response(['message' => 'Something went worng', 400]);
    }

    public function getClientDetails()
    {
        $client = auth()->user()->client;
        return new ClientResource($client);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\client  $client
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::find($id);
        if ($client) {
            return $client;
        } else {
            return response(['message' => 'No data found']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = auth()->user()->id;
        $client = Client::where(['user_id' => $id])->first();
        $old_mfs_no = $client->mfs_no;

        $data   = $request->validate([
            'name'       => 'nullable|max:100',
            'email'      => 'nullable|email|max:100|unique:users,email,' . $id,
            'area'       => 'string|nullable',
            'address'    => 'string|required',
            'location'   => 'string|nullable',
            'mfs_type'   => 'string|required',
            'mfs_no'     => 'required|size:11|regex:/(01)[0-9]{9}/',
            'remarks'    => 'string|nullable',
            'company_name' => 'string|nullable',
        ]);

        $data['company_name'] = $data['name'];

        try
        {
            DB::beginTransaction();
                
            if ($old_mfs_no != $request->mfs_no)
                {
                    $mfs_data = [
                        "mfs_number" => $old_mfs_no,
                        "user_id" => auth()->user()->id,
                        "status" => 1
                    ];
                    
                    MfsNumber::create($mfs_data);
                }
                $client->update($data);
        
                $user = User::find($id);
        
                $user->name  = $data['name'];
                $user->email = $data['email']; 
                $user->save();

            DB::commit();

            return response()->json(['message' => 'Profile update Successfully'], 200);

        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['message' => 'Profile update failed'], 400);
        }
    }

    public function changeImage(Request $request)
    {
        $id = auth()->user()->id;
        $client = Client::where(['user_id' => $id])->first();
        
        $client->photo = base64_to_image($request->photo, 'upload/client');

        if ($client->save()) {
            return response()->json(['message' => 'Profile picture updated'], 200);
        }
        return response()->json(['message' => 'Profile picture failed'], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\client  $client
     * @return \Illuminate\Http\Response
     */

    public function destroy($user_id)
    {
        $clientUser = User::find($user_id);

        if ($clientUser->delete()) {
            return response(['message' => 'Client deleted successfully']);
        }
    }


    public function CurrentOrder()
    {
        $order = Order::where('user_id', auth()->user()->id)->whereIn('status', [0, 1, 2, 3, 4])->orderBy('id', 'desc')->get();
        return OrderResource::collection($order);
    }

    public function CurrentOrderCountUser()
    {
        return $order = Order::where('user_id', auth()->user()->id)->whereIn('status', [0, 1, 2, 3, 4])->count();
    }

    public function userMfsNumberHistory()
    {
        return MfsNumber::where(['user_id' => auth()->user()->id])->orderBy('id', 'desc')->get();
    }

    public function orderHistory()
    {
        $order = [];
        $order = Order::where(['user_id' => auth()->user()->id])->whereIn('status', [5, 6])->orderBy('id', 'desc')->get();
        if(!empty($order))
        {
            return UserOrderHistory::collection($order);
        }
        return response(['data' => $order]);
    }
    
    public function toggleStatus($id)
    {
        $toggle = User::find($id);
        if(!empty($toggle))
        {
            if($toggle->status == 0)
            {
                User::find($id)->update(['status' => 1]);
                toastr()->success('User active successfully');
            }
            if($toggle->status == 1)
            {
                User::find($id)->update(['status' => 0]);
                toastr()->error('User In Active successfully');
            }
        }
        return back();
    }
}
