<?php

namespace App\Http\Controllers\b2b;

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

class B2binventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$client = Client::all();
        return view('b2b.inventory.index');
    }
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('b2b.inventory.create');
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
