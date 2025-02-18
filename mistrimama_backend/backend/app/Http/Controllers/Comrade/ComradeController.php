<?php

namespace App\Http\Controllers\Comrade;

use App\Comrade;
use App\User;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Events\SmsEvenet;
use App\Http\Resources\ComradeJobsResource;
use App\Http\Resources\ComradeResource;
use App\Http\Controllers\NotificationController;
use App\OrderDetail;
use App\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ComradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['comrades'] = Comrade::orderBy('id', 'desc')->get();
        return view('comrade.index', $data);
    }

    public function request()
    {
        $data['comrades'] = Comrade::where('approve', 0)->orderBy('id', 'desc')->get();
        return view('comrade.request', $data);
    }

    public function comradeExport()
    {
        
        $comrade =  Comrade::all();
        // $Comrade = auth()->user()->serviceProvider->comrades;

        if (empty($comrade)) {
            toastr()->error('Comrade not available');
            return redirect()->back();
        }

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Comrade_".date('d-m-Y').".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = ['Sl', 'Comrade Joining Date', 'Comrade ID', 'Comrade Name',  'Comrade Phone',
            'Number of Job Done by This Comrade',
            'Service Provider ID' ,'Service Provider Name' , 'Service Provider Category',
            'Service Provider Account Type' , 'Service Provider Zone' , 
            'Service Provider Phone' , 'Number of Comrade under this Service Provider',
            'Rating' ,'Status', 'Approve'
            

        ];

        $callback = function () use ($comrade, $columns) {
            $file = fopen('php://output', 'w');;
            fputcsv($file, $columns);

            foreach ($comrade as $key=>$item) {
                $categoris = '';
                if(!empty($item->serviceProvider->service)){
                    foreach ($item->serviceProvider->service as $value) {
                        $categoris .=  $value->service->name .', '  ;
                    }
                } 

                $zone = '';
                if(!empty($item->serviceProvider->cluster)){
                    foreach ($item->serviceProvider->cluster as $value) {
                        $zone .=  $value->cluster->name .', '  ;
                    }
                } 
                $status  = '';
                if($item->status == 1){
                    $status  = 'Active';
                }else{
                    $status  = 'Inactive';
                }
                $approve  = '';
                if($item->approve == 1){
                    $approve  = 'Approved';
                }else{
                    $approve  = 'Not Approved';
                }  
                fputcsv($file, [$key+ 1 , 
                $item->created_at->format('Y-m-d'), 
                $item->comrade_code, 
                $item->name,
                $item->phone,
                $item->total_job_done,
                $item->serviceProvider->sp_code,
                $item->serviceProvider->name, 
                $categoris , 
                $item->serviceProvider->category, 
                $zone ,
                $item->serviceProvider->phone, 
                $item->serviceProvider->no_of_active_comrade, 
                $item->serviceProvider->rating, 
                $status, 
                $approve
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
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

        $serviceProvider = ServiceProvider::find(auth()->user()->serviceProvider->id);
        $comrade_count = Comrade::where('service_provider_id', $serviceProvider->id)->count();
        $comrade_code = $serviceProvider->sp_code.''. ($comrade_count + 1);

        if ($request->has('email') && empty($request->email)) {
            $name =  strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $request->name ));
            $request->merge(['email' =>  $name.'_'.$comrade_code.'@mistrimama.com']);
        }

        $request->merge([
            'password' => $request->password,
            'password_confirmation' => $request->password
        ]);

        $data = $request->validate([
            'name'                => 'required|max:100',
            'phone'               => 'required|unique:users,phone|size:11|regex:/(01)[0-9]{9}/',
            'alt_phone'           => 'nullable||size:11|regex:/(01)[0-9]{9}/',
            'email'               => 'nullable|email|max:100|unique:users,email',
            'nid_no'              => 'required',
            'services'            => 'required|array|min:1',
            'password'            => 'required|confirmed|min:6',
        ]);

        try{
            DB::beginTransaction();
            
                $data['service_provider_id'] = auth()->user()->serviceProvider->id;

                if ($request->has('photo') && !empty($request->photo)) {
                    $data['photo'] = base64_to_image($request->photo, 'upload/sp');
                } else {
                    $data['photo'] = '';
                }

                if ($request->has('nid_front') && !empty($request->nid_front)) {
                    $data['nid_front'] = base64_to_image($request->nid_front, 'upload/sp');
                } else {
                    $data['nid_front'] = '';
                }

                if ($request->has('nid_back') && !empty($request->nid_back)) {
                    $data['nid_back'] = base64_to_image($request->nid_back, 'upload/sp');
                } else {
                    $data['nid_back'] = '';
                }
            
                $data['services'] = json_encode($request->services);
                $data['comrade_code'] = $comrade_code;
                $data['status'] = 0;
                $data['lastStatusUpdateBy'] = 'sp';
                
                $RegisteredUser = RegisterController::create($data, 0);
                $data['user_id'] = $RegisteredUser->id;
                $RegisteredUser->assignRole('comrade'); // add role
                $data['alt_phone'] = $request->alt_phone;
                $comrade = Comrade::create($data);

                NotificationController::comradeRegistrationNotification();
            DB::commit();

            return response()->json(['message' => 'Comrade create successfully'], 200);

        }catch(\Exception $e){
            DB::rollback();
        }
        return response()->json(['message' => 'Comrade create failed'], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comrade  $comrade
     * @return \Illuminate\Http\Response
     */
    public function show(Comrade $comrade)
    {
        return $comrade;
    }

    public function comrade_profile()
    {
        $id = auth()->user()->id;
        return response()->json(new ComradeResource(Comrade::where('user_id', $id)->first()));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comrade  $comrade
     * @return \Illuminate\Http\Response
     */
    public function edit(Comrade $comrade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comrade  $comrade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        if ($request->has('password') && !empty($request->password)) {
            $request->merge([
                'password' => $request->password,
                'password_confirmation' => $request->password
            ]);
        }
        
        $data = $request->validate([
            'name'                => 'required|max:100',
            'phone'               => 'required|size:11|regex:/(01)[0-9]{9}/|unique:users,phone,'.$id,
            'email'               => 'nullable|email|max:100|unique:users,email,'.$id,
            'nid_no'              => 'required',
            'services'            => 'required|array|min:1',
            'password'            => 'nullable|confirmed|min:6',
            'alt_phone'           => 'nullable||size:11|regex:/(01)[0-9]{9}/',
            
        ]);

        unset($data['password']);
        
        try{
            DB::beginTransaction();

                $user_update = [
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                ];
                if ($request->has('photo') && !empty($request->photo)) {
                    $data['photo'] = base64_to_image($request->photo, 'upload/sp');
                    $data['approve'] = 0;
                    $data['status'] = 0;
                }

                if ($request->has('nid_front') && !empty($request->nid_front)) {
                    $data['nid_front'] = base64_to_image($request->nid_front, 'upload/sp');
                    $data['approve'] = 0;
                    $data['status'] = 0;
                    $user_update['status'] = 0;
                }

                if ($request->has('nid_back') && !empty($request->nid_back)) {
                    $data['nid_back'] = base64_to_image($request->nid_back, 'upload/sp');
                    $data['approve'] = 0;
                    $data['status'] = 0;
                    $user_update['status'] = 0;
                }

                $data['services'] = json_encode($request->services);
                $data['lastStatusUpdateBy'] = 'sp';
                
                if ($request->has('password') && !empty($request->password)) {
                    $user_update['password'] = Hash::make($request->password);
                }
                
                User::where(['id' => $id])->update($user_update);
                $data['alt_phone'] = $request->alt_phone;
                Comrade::where(['user_id' => $id])->update($data);
                    
            DB::commit();

            return response()->json(['message' => 'Comrade update successfully'], 200);

        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Comrade update failed'], 400);
        }
    }

    public function profile_update(Request $request)
    {
        $id = auth()->user()->id;
        $data = $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|max:100|unique:users,email,'.$id,
            'nid_no' => 'required',
            'address' => 'nullable',
            'alt_phone' => 'nullable|size:11|regex:/(01)[0-9]{9}/',
        ]);
        
        try{
            DB::beginTransaction();

            User::where(['id' => $id])->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);
            Comrade::where(['user_id' => $id])->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'address' => $data['address'],
                'alt_phone' => (!empty($request)) ? $data['alt_phone'] : NULL,
                'nid_no' => $data['nid_no'],
            ]);
                    
            DB::commit();

            return response()->json(['message' => 'Profile update successfully'], 200);

        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Profile update failed'], 400);
        }
    }

    public function changeComradeImage(Request $request)
    {
        $id = auth()->user()->id;
        $client = Comrade::where(['user_id' => $id])->first();
        
        $client->photo = base64_to_image($request->photo, 'upload/sp');

        if ($client->save()) {
            return response()->json(['message' => 'Profile picture updated'], 200);
        }
        return response()->json(['message' => 'Profile picture failed'], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comrade  $comrade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,  $id)
    {
        $user = User::find($id);
        if(empty($user))
        {
            return response()->json(['message' => 'Comrade has not found!'], 400);
        }

        if($user->comrade->lastStatusUpdateBy == 'admin'){
            if($user->comrade->approve == 2)
            {
                return response()->json(['message' => 'Comrade has been deny from management'], 400);
            }
            if($user->status == 0)
            {
                return response()->json(['message' => 'Comrade already inactive from management'], 400);
            }
        }

        try{
            DB::beginTransaction();
            if($user->status == 0)
            {
                User::where(['id' => $id])->update([
                    'status' => 1
                ]);
                Comrade::where(['user_id' => $id])->update([
                    'status' => 1,
                    'lastStatusUpdateBy' => 'sp'
                ]);
                $status = "Active";
            }
            if($user->status == 1)
            {
                User::where(['id' => $id])->update([
                    'status' => 0
                ]);
                Comrade::where(['user_id' => $id])->update([
                    'status' => 0,
                    'lastStatusUpdateBy' => 'sp'
                ]);
                $status = "In Active";
            }
            NotificationController::comradeStatusUpdateFromServiceProviderNotification($status);
            DB::commit();

            return response()->json(['message' => 'Comrade '.$status.' successfully'], 200);

        }catch(\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Comrade update failed'], 400);
        }
    }

    public function approve($id)
    {
        $data['approve'] = 1;
        $data['status'] = 1;
        $data['approveBy'] = (auth()->check()) ? auth()->user()->name : '';
        $data['lastStatusUpdateBy'] = 'admin';
        
        try{
            DB::beginTransaction();
            
                $comrade = Comrade::find($id);
                $comrade->update($data);

                $comrade->user->status = 1;
                $comrade->user->save();
                NotificationController::comradeApproveDenyNotification($data, $comrade);
                
            DB::commit();

            toastr()->success('Comrade approve successfully');

        }catch(\Exception $e){
            DB::rollback();
            toastr()->success('Comrade approve failed');
        }
        return redirect()->back();
    }

    public function deny($id)
    {
        $data['approve'] = 2;
        $data['lastStatusUpdateBy'] = 'admin';
        try{
            DB::beginTransaction();
            $comrade = Comrade::find($id);
            $comrade->update($data);
            NotificationController::comradeApproveDenyNotification($data, $comrade);
            DB::commit();

            toastr()->success('Comrade Deny Successfull');

        }catch(\Exception $e){
            DB::rollback();
            toastr()->error('Comrade Deny Failed');
        }
        return redirect()->back();
    }

    public function active($id)
    {
        $data['status'] = 1;
        $data['lastStatusUpdateBy'] = 'admin';
        
        try{
            DB::beginTransaction();
            
                $comrade = Comrade::find($id);
                $comrade->update($data);

                $comrade->user->status = 1;
                $comrade->user->save();
                NotificationController::comradeStatusUpdateNotification($data, $comrade);
            DB::commit();

            toastr()->success('Comrade active successfully');

        }catch(\Exception $e){
            DB::rollback();
            toastr()->success('Comrade active failed');
        }
        return redirect()->back();
    }

    public function inactive($id)
    {
        $data['status'] = 0;
        $data['lastStatusUpdateBy'] = 'admin';
        
        try{
            DB::beginTransaction();
            
                $comrade = Comrade::find($id);
                $comrade->update($data);

                $comrade->user->status = 0;
                $comrade->user->save();
                NotificationController::comradeStatusUpdateNotification($data, $comrade);
                
            DB::commit();

            toastr()->success('Comrade in active successfully');

        }catch(\Exception $e){
            DB::rollback();
            toastr()->success('Comrade in active failed');
        }
        return redirect()->back();
    }

    public function allowcatedOrders()
    {
        $order = OrderDetail::where('comrade_id', auth()->user()->comrade->id)->where('state', '!=', 4)->get();
        return ComradeJobsResource::collection($order);
    }

    public function OrdersHistory()
    {
        $order = OrderDetail::where(['comrade_id' => auth()->user()->comrade->id, 'state' => 4])->get();
        return ComradeJobsResource::collection($order);
    }
}
