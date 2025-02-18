<?php
namespace App\Http\Controllers\ServiceProvider;

use App\Account;
use App\Comrade;
use App\Http\Controllers\Account\AccountController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ServiceProvider\ServiceProviderClusterController;
use App\Http\Controllers\ServiceProvider\ServiceProviderDivisionController;
use App\Http\Controllers\ServiceProvider\ServiceProviderTimeController;
use App\Http\Controllers\ServiceProvider\ServiceProviderZoneController;
use App\Http\Controllers\SiteConfigsController;
use App\ServiceProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\NotificationController;
use App\Http\Resources\AllServicesResource;
use App\Http\Resources\OrderDetailsResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ServiceProviderResource;
use App\Http\Resources\ComradeResource;
use App\Http\Resources\SchemeResource;
use App\Http\Resources\StatementResource;
use App\Setting;
use App\OrderReject;
use App\Order;
use App\OrderDetail;
use App\OrderSystem;
use App\RechargeRequest;
use App\Service;
use App\ServiceBit;
use App\User;
use App\ServiceProviderCategoryUpdateHistory;
use App\ServiceProviderService;
use App\Category;
use App\Division;
use App\BecomeServiceProvider;
use App\Cluster;
use App\Zone;
use App\Media;
use App\WithdrawRequest;
use Carbon\Carbon;
use App\SMS;
use App\MfsNumber;
use Illuminate\Support\Facades\Hash;
use File;
use URL;
use Madzipper;
use App\Scheme;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Builder;
use App\Myclass\PHPMailer;
use App\Myclass\SMTP; 

class ServiceProviderController extends Controller
{

    public $category_id;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type = null)
    {
        $sp = ServiceProvider::orderBy('id', 'desc');
        if(!empty($type))
        {
            $sp = $sp->where('type', $type);
        }
        $data['serviceprovider'] = $sp->get();
        return view('sp.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['days'] = ['Friday', 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
        $a_start = strtotime('07:00 AM');
        $a_end = strtotime('08:00 PM');
        while ($a_start !== $a_end)
        {
            $a_start = strtotime('+1 hour', $a_start);
            $data['start_times'][] = date('h:i A', $a_start);
        }

        $b_end = strtotime('09:00 PM');
        $b_start = strtotime('08:00 AM');
        while ($b_end !== $b_start)
        {
            $b_end = strtotime('-1 hour', $b_end);
            $data['end_times'][] = date('h:i A', $b_end);
        }
        $data['categories'] = Category::all();
        $data['divisions'] = Division::all();
        $data['clusters'] = Cluster::all();
        $data['zones'] = Zone::where('status', 0)->get();
        return view('sp.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sp_code = spCode();

        if ($request->has('email') && empty($request->email))
        {
            $name =  strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $request->name ));
            $request->merge(['email' => $name . '_' . $sp_code . '@mistrimama.com']);
        }

        $data = $request->validate([
            'name' => 'required|max:100', 
            'phone' => 'numeric|digits:11|required|unique:users,phone|regex:/(01)[0-9]{9}/', 
            'alt_phone' => 'numeric|nullable|digits:11|regex:/(01)[0-9]{9}/', 
            'email' => 'max:100|email|unique:users,email', 
            'shop_name' => 'string|nullable', 
            'address' => 'string|nullable', 
            'mfs_type' => 'string|required', 
            'mfs_no' => 'required|nullable|regex:/(01)[0-9]{9}/', 
            'nid_no' => 'numeric|required', 
            'trade_lic_no' => 'numeric|nullable', 
            'tin_no' => 'numeric|nullable', 
            'photo' => 'required', 
            'nid_front' => 'required', 
            'nid_back' => 'required', 
            'type' => 'required|string',
            'service_category' => 'required',
            'division' => 'required', 
            'cluster' => 'required', 
            'zone' => 'required'
        ]);

        if (empty($request->shop_name))
        {
            $data['shop_name'] = $data['name'];
        }

        $data['sp_code'] = $sp_code;
        $data['addedBy'] = auth()->user()->id;
        $data['password'] = rand(100000, 999999);
        $data['rating'] = 5;
        $data['rating'] = 5;
        $data['category'] = 'starter';
        $dir = NULL;

        if ($request->has('photo'))
        {
            $data['photo'] = base64_to_image($request->photo, 'upload/sp');
        }

        $media_nid_front = '';
        if ($request->has('nid_front'))
        {
            $media_nid_front = base64_to_image($request->nid_front, 'upload/sp');
            $dir = 'upload/sp';
        }

        $media_nid_back = '';
        if ($request->has('nid_back'))
        {
            $media_nid_back = base64_to_image($request->nid_back, 'upload/sp');
            $dir = 'upload/sp';
        }

        $media_trade_lic_image = '';
        if ($request->has('trade_lic_image'))
        {
            $media_trade_lic_image = base64_to_image($request->trade_lic_image, 'upload/sp');
            $dir = 'upload/sp';
        }
        
        $media_tin_image = '';
        if ($request->has('tin_image'))
        {
            $media_tin_image = base64_to_image($request->tin_image, 'upload/sp');
            $dir = 'upload/sp';
        }

        if ($request->has('nid_no') || $request->has('trade_lic_no') || $request->has('tin_no'))
        {
            $othersDoc = [];
            $othersDoc['nid_no'] = $request->nid_no;
            $othersDoc['trade_lic_no'] = $request->trade_lic_no;
            $othersDoc['tin_no'] = $request->tin_no;
            $data['others_doc'] = json_encode($othersDoc);
        }

        unset($data['nid_no']);
        unset($data['trade_lic_no']);
        unset($data['tin_no']);
        unset($data['nid_front']);
        unset($data['nid_back']);
        unset($data['nid_back']);
        
        try
        {
            DB::beginTransaction();

            $RegisteredUser = RegisterController::create($data, 0);
            $data['user_id'] = $RegisteredUser->id;
            $serviceProvider = ServiceProvider::create($data);
            $RegisteredUser->assignRole($data['type']);
            if ($request->has('day') && $request->has('start') && $request->has('end'))
            {
                $this->addTime($request, $serviceProvider->id);
            }

            if ($request->has('division'))
            {
                $this->addDivision($request, $serviceProvider->id);
            }

            if ($request->has('service_category'))
            {
                $this->addServices($request, $serviceProvider->id);
            }

            if ($request->has('cluster'))
            {
                $this->addCluster($request, $serviceProvider->id);
            }

            if ($request->has('zone'))
            {
                $this->addZone($request, $serviceProvider->id);
            }

            if(!empty($media_nid_front))
            {
                Media::create([
                    'user_id' => $serviceProvider->id,
                    'type' => 'nid_front',
                    'dir' => $dir,
                    'filename' => $media_nid_front,
                    'status' => 'pending',
                ]);
            }

            if(!empty($media_nid_back))
            {
                Media::create([
                    'user_id' => $serviceProvider->id,
                    'type' => 'nid_back',
                    'dir' => $dir,
                    'filename' => $media_nid_back,
                    'status' => 'pending',
                ]);
            }

            if(!empty($media_trade_lic_image))
            {
                Media::create([
                    'user_id' => $serviceProvider->id,
                    'type' => 'trade_lic_image',
                    'dir' => $dir,
                    'filename' => $media_trade_lic_image,
                    'status' => 'pending',
                ]);
            }

            if(!empty($media_tin_image))
            {
                Media::create([
                    'user_id' => $serviceProvider->id,
                    'type' => 'tin_image',
                    'dir' => $dir,
                    'filename' => $media_tin_image,
                    'status' => 'pending',
                ]);
            }
            
            NotificationController::createServiceProviderNotification();
            DB::commit();

            toastr()->success('New Service Provider register successfully');
            return redirect()->route('service-provider.create');
        }
        catch(\Exception $e)
        {
            dd($e);
            DB::rollback();
        }
        toastr()->error('Create Service Provider has been Failed!');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ServiceProvider  $serviceProvider
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['serviceProvider'] = ServiceProvider::find($id);
        return view('sp.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ServiceProvider  $serviceProvider
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceProvider $serviceProvider)
    {
        $data['days'] = ['Friday', 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];
        $a_start = strtotime('07:00 AM');
        $a_end = strtotime('08:00 PM');
        while ($a_start !== $a_end)
        {
            $a_start = strtotime('+1 hour', $a_start);
            $data['start_times'][] = date('h:i A', $a_start);
        }

        $b_end = strtotime('09:00 PM');
        $b_start = strtotime('08:00 AM');
        while ($b_end !== $b_start)
        {
            $b_end = strtotime('-1 hour', $b_end);
            $data['end_times'][] = date('h:i A', $b_end);
        }
        $data['categories'] = Category::all();
        $data['divisions'] = Division::all();
        $data['clusters'] = Cluster::all();
        $data['zones'] = Zone::where('status', 0)->get();
        $data['serviceProvider'] = $serviceProvider;
        return view('sp.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ServiceProvider  $serviceProvider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|max:100',
            'alt_phone' => 'numeric|nullable|digits:11|regex:/(01)[0-9]{9}/',
            'shop_name' => 'string|required',
            'address' => 'string|nullable',
            'mfs_type' => 'string|required',
            'mfs_no' => 'required|nullable|regex:/(01)[0-9]{9}/',
            'nid_no' => 'numeric|required',
            'trade_lic_no' => 'numeric|nullable',
            'tin_no' => 'numeric|nullable',
            'type' => 'required|string',
            'service_category' => 'required',
            'division' => 'required',
            'cluster' => 'required',
            'zone' => 'required'
        ]);
        $auth_user_id = auth()->user()->id;

        if (!$request->has('shop_name'))
        {
            $data['shop_name'] = $data['name'];
        }

        if ($request->has('photo') && $request->photo != '')
        {
            $data['photo'] = base64_to_image($request->photo, 'upload/sp');
        }

        $dir = NULL;
        $media_nid_front = '';
        if ($request->has('nid_front') && $request->nid_front != '')
        {
            $media_nid_front = base64_to_image($request->nid_front, 'upload/sp');
            $dir = 'upload/sp';
        }

        $media_nid_back = '';
        if ($request->has('nid_back') && $request->nid_back != '')
        {
            $media_nid_back = base64_to_image($request->nid_back, 'upload/sp');
            $dir = 'upload/sp';
        }

        $media_trade_lic_image = '';
        if ($request->trade_lic_image != '')
        {
            $media_trade_lic_image = base64_to_image($request->trade_lic_image, 'upload/sp');
            $dir = 'upload/sp';
        }
        
        $media_tin_image = '';
        if ($request->tin_image != '')
        {
            $media_tin_image = base64_to_image($request->tin_image, 'upload/sp');
            $dir = 'upload/sp';
        }

        if (($request->has('nid_no') || $request->has('trade_lic_no')) || ($request->has('tin_no')))
        {
            $othersDoc = [];
            $othersDoc['nid_no'] = $request->nid_no;
            $othersDoc['trade_lic_no'] = $request->trade_lic_no;
            $othersDoc['tin_no'] = $request->tin_no;
            $data['others_doc'] = json_encode($othersDoc);
        }

        unset($data['nid_no']);
        unset($data['trade_lic_no']);
        unset($data['tin_no']);
        
        try
        {
            DB::beginTransaction();
            
            if ($request->has('day') && $request->has('start') && $request->has('end'))
            {
                ServiceProviderTimeController::deleteTime($id);
                $this->addTime($request, $id);
            }

            if ($request->has('division'))
            {
                ServiceProviderDivisionController::deleteDivision($id);
                $this->addDivision($request, $id);
            }

            if ($request->has('service_category'))
            {
                ServiceProviderServiceController::deleteService($id);
                $this->addServices($request, $id);
            }

            if ($request->has('cluster'))
            {
                ServiceProviderClusterController::deleteCluster($id);
                $this->addCluster($request, $id);
            }

            if ($request->has('zone'))
            {
                ServiceProviderZoneController::deleteZone($id);
                $this->addZone($request, $id);
            }
            
            unset($data['division']);
            unset($data['cluster']);
            unset($data['zone']);
            unset($data['service_category']);


            if(!empty($media_nid_front))
            {
                Media::where(['user_id' => $id, 'type' => 'nid_front'])->delete();
                Media::create([
                    'user_id' => $id,
                    'type' => 'nid_front',
                    'dir' => $dir,
                    'filename' => $media_nid_front,
                    'status' => 'pending',
                ]);
            }

            if(!empty($media_nid_back))
            {
                Media::where(['user_id' => $id, 'type' => 'nid_back'])->delete();
                Media::create([
                    'user_id' => $id,
                    'type' => 'nid_back',
                    'dir' => $dir,
                    'filename' => $media_nid_back,
                    'status' => 'pending',
                ]);
            }

            if(!empty($media_trade_lic_image))
            {
                Media::where(['user_id' => $id, 'type' => 'trade_lic_image'])->delete();
                Media::create([
                    'user_id' => $id,
                    'type' => 'trade_lic_image',
                    'dir' => $dir,
                    'filename' => $media_trade_lic_image,
                    'status' => 'pending',
                ]);
            }

            if(!empty($media_tin_image))
            {
                Media::where(['user_id' => $id, 'type' => 'tin_image'])->delete();
                Media::create([
                    'user_id' => $id,
                    'type' => 'tin_image',
                    'dir' => $dir,
                    'filename' => $media_tin_image,
                    'status' => 'pending',
                ]);
            }

            $serviceProvider = ServiceProvider::where('id', $id)->update($data);

            DB::commit();
            toastr()->success('New Service Provider update successfully');
            return redirect()->route('service-provider.edit', $id);
        }
        catch(\Exception $e)
        {
            dd($e);
            DB::rollback();
        }
        toastr()->error('Create Service Provider update Failed!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ServiceProvider  $serviceProvider
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceProvider $serviceProvider)
    {
        //
        
    }

    public function getServiceProviderDetails()
    {
        $sp = auth()->user()->serviceProvider;
        return new ServiceProviderResource($sp);
    }

    // find service provider who has match on order service category.
    public function findServiceProviderWithCategory($category_id, $order_id)
    {
        $data['order'] = Order::where(['id' => $order_id])->first();
        $data['service_providers'] = ServiceProvider::whereHas('services', function ($query) use ($category_id){
            $query->with('service')->where('category_id', $category_id);
        })->orderBy('rating', 'desc')->get();

        $data['selected_service_provider_id'] = 0;
        $order_system = OrderSystem::select('service_provider_id')->where('order_id', $order_id)->first();

        if (!empty($order_system))
        {
            $data['selected_service_provider_id'] = $order_system->service_provider_id;
        }
        $data['order_id'] = $order_id;

        return view('order.allocated_orders', $data);
    }

    public function addTime(Request $request, $serviceProviderId)
    {
        $days = $request->day;
        $start = $request->start;
        $end = $request->end;
        for ($i = 0;$i < count($days);$i++)
        {
            $time[$i] = ['day' => $days[$i], 'start' => $start[$days[$i]][0], 'end' => $end[$days[$i]][0]];
            ServiceProviderTimeController::storeTime($time[$i], $serviceProviderId);
        }
    }

    public function addDivision(Request $request, $serviceProviderId)
    {
        for ($i = 0;$i < count($request->division);$i++)
        {
            ServiceProviderDivisionController::storeDivision($request->division[$i], $serviceProviderId);
        }
        // return $division;
        
    }

    public function addCluster(Request $request, $serviceProviderId)
    {
        for ($i = 0;$i < count($request->cluster);$i++)
        {
            ServiceProviderClusterController::storeCluster($request->cluster[$i], $serviceProviderId);
        }
    }

    public function addZone(Request $request, $serviceProviderId)
    {
        for ($i = 0;$i < count($request->zone);$i++)
        {
            ServiceProviderZoneController::storeZone($request->zone[$i], $serviceProviderId);
        }
    }

    public function addServices(Request $request, $serviceProviderId)
    {
        for ($i = 0;$i < count($request->service_category);$i++)
        {
            ServiceProviderServiceController::storeService($request->service_category[$i], $serviceProviderId);
        }
    }

    public function download($id)
    {
        $serviceProvider = ServiceProvider::find($id);

        $name = $this->normalizeString($serviceProvider->name);
        $path = public_path('download/' . $name);
        $mainpath = public_path('download/' . $name);

        if (!File::isDirectory($path))
        {
            File::makeDirectory($path, 0755, true, true);
        }


        $mess = false;
        $photo = public_path('upload/sp/' . $serviceProvider->photo);
        if (is_file($photo))
        {
            $photo_file = explode('/', $photo);
            $mess = copy($photo, $path . '/' .'profile-photo-'. end($photo_file));
        }
        
        if(!empty($serviceProvider->media))
        {
            foreach($serviceProvider->media as $media)
            {
                $file = public_path($media->dir.'/'.$media->filename);
                if (is_file($file))
                {
                    $file_explode = explode('/', $file);
                    $mess = copy($file, $path . '/' .str_replace('_', '-', $media->type).'-'. end($file_explode));
                }
            }
        }
        if($mess == false){
            toastr()->error('Files are does not exists!');
            return redirect()->back();
        }
        Madzipper::make($mainpath . '.zip')->add($mainpath)->close();
        $this->deleteDir($mainpath);
        return response()->download($mainpath . '.zip')->deleteFileAfterSend(true);
    }

    public static function normalizeString($str = '')
    {
        $str = strip_tags($str);
        $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
        $str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
        $str = strtolower($str);
        $str = html_entity_decode($str, ENT_QUOTES, "utf-8");
        $str = htmlentities($str, ENT_QUOTES, "utf-8");
        $str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
        $str = str_replace(' ', '-', $str);
        $str = rawurlencode($str);
        $str = str_replace('%', '-', $str);
        return $str;
    }
    public static function deleteDir($dirPath)
    {
        if (!is_dir($dirPath))
        {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/')
        {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file)
        {
            if (is_dir($file))
            {
                self::deleteDir($file);
            }
            else
            {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    public function active($id, $status)
    {
        $status = ($status == 1) ? 0 : 1;
        $password = rand(100000, 999999);
        $status_array = ['In Active', 'Active'];

        $serviceprovider = ServiceProvider::find($id);
        $user = User::find($serviceprovider->user_id);

        $data['status'] = $status;
        $data['password'] = Hash::make($password);

        if ($serviceprovider->update($data) && $user->update($data))
        {
            if ($status == 1)
            {
                $msg = 'Your phone number is: ' . $serviceprovider->phone . ' & password is: ' . $password;
                SMS::send($serviceprovider->phone, $msg);
            }
            toastr()->success('Service Provider ' . $status_array[$status] . ' Successfull');
        }
        else
        {
            toastr()->success('Service Provider ' . $status_array[$status] . ' Failed');
        }
        return redirect()->back();
    }

    public function updateInfo(Request $request)
    {
        $id = auth()->user()->id;
        $user = User::find(auth()->user()->id);

        $request->validate([
            'name' => 'required|max:100',
            'email' => 'nullable|max:100|email|unique:users,email,' . $id, 
            'address' => 'string|nullable', 
            'mfs_type' => 'required', 
            'mfs_no' => 'required|size:11|regex:/(01)[0-9]{9}/', 
        ]);

        $old_mfs_no = $user->serviceProvider->mfs_no;
        $check_mfs_number_has_changed = ($old_mfs_no != $request->mfs_no) ? true : false;

        if ($check_mfs_number_has_changed)
        {
            $today = Carbon::now('Asia/Dhaka')->today()->format('Y-m-d');
            $exists = MfsNumber::where('user_id', $id)->where('created_at', 'like', '%'.$today.'%')->exists();
            if($exists)
            {
                return response()->json(['message' => 'You can change MFS Number (Bkash) once a day'], 400);
            }

            $mfs_data = [
                "mfs_number" => $old_mfs_no,
                "user_id" => auth()->user()->id,
                "status" => 1
            ];
        }

        try
        {
            DB::beginTransaction();

            $user->update([
                'name' => $request->name,
                'email' => $request->email
            ]);
            
            $user->serviceProvider->update([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'mfs_type' => $request->mfs_type,
                'mfs_no' => $request->mfs_no,
            ]);

            if ($check_mfs_number_has_changed)
            {
                MfsNumber::create($mfs_data);
            }

            DB::commit();

            return response()->json(['message' => 'Profile update Successfully'], 200);

        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['message' => 'Profile update failed'], 400);
        }
    }

    public function getComrades()
    {
        $comrades = Comrade::where('service_provider_id', auth()->user()->serviceProvider->id)->orderBy('id', 'desc')->get();
        if (!empty($comrades))
        {
            return ComradeResource::collection($comrades);
        }
        return NULL;
    }
    
    public function comradeExport()
    {

        //$Comrade =  Comrade::all();
        $Comrade = auth()->user()->serviceProvider->comrades;

        if (empty($Comrade))
        {
            toastr()->error('Comrade not available');
            return redirect()
                ->back();
        }

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Comrade_" . date('d-m-Y') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = ['Sl', 'Comrade Joining Date', 'Comrade ID', 'Comrade Name', 'Comrade Phone', 'Number of Job Done by This Comrade', 'Service Provider ID', 'Service Provider Name', 'Service Provider Category', 'Service Provider Account Type', 'Service Provider Zone', 'Service Provider Phone', 'Number of Comrade under this Service Provider', 'Rating', 'Status', 'Approve'];

        $callback = function () use ($Comrade, $columns)
        {
            $file = fopen('php://output', 'w');;
            fputcsv($file, $columns);

            foreach ($Comrade as $key => $item)
            {
                $categoris = '';
                if (!empty($item
                    ->serviceProvider
                    ->service))
                {
                    foreach ($item
                        ->serviceProvider->service as $value)
                    {
                        $categoris .= $value
                            ->service->name . ', ';
                    }
                }

                $zone = '';
                if (!empty($item
                    ->serviceProvider
                    ->cluster))
                {
                    foreach ($item
                        ->serviceProvider->cluster as $value)
                    {
                        $zone .= $value
                            ->cluster->name . ', ';
                    }
                }
                $status = '';
                if ($item->status == 1)
                {
                    $status = 'Active';
                }
                else
                {
                    $status = 'Inactive';
                }
                $approve = '';
                if ($item->approve == 1)
                {
                    $approve = 'Approved';
                }
                else
                {
                    $approve = 'Not Approved';
                }
                fputcsv($file, [$key + 1, $item
                    ->created_at
                    ->format('Y-m-d') , $item->comrade_code, $item->name, $item->phone, $item->total_job_done, $item
                    ->serviceProvider->sp_code, $item
                    ->serviceProvider->name, $categoris, $item
                    ->serviceProvider->category, $zone, $item
                    ->serviceProvider->phone, $item
                    ->serviceProvider->no_of_active_comrade, $item
                    ->serviceProvider->rating, $status, $approve]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function getComradesByCategory($category)
    {
        return Comrade::where('service_provider_id', auth()->user()->serviceProvider->id)->where('status', 1)->where('approve', 1)->where('services', 'like', '%' . $category . '%')->select('id', 'name', 'phone', 'services')->get();
    }

    public function showall()
    {
        $serviceprovider = ServiceProvider::all();
        return view('sp.show', compact('serviceprovider'));
    }
    public function changeImage(Request $request)
    {
        $sp = ServiceProvider::find(auth()->user()->serviceProvider->id);
        $sp->photo = base64_to_image($request->photo, 'upload/sp');
        if ($sp->save())
        {
            return response(['message' => 'Profile picture updated']);
        }
    }

    public function allServices()
    {
        $sp = ServiceProvider::find(auth()->user()->serviceProvider->id);
        $allServices = ServiceBit::whereIn('category_id', $sp->services->pluck('category_id')->toArray())->get();
        return AllServicesResource::collection($allServices);        
    }

    public function newAvaiableOrder()
    {
        $getRoleNames = auth()->user()->getRoleNames()->first();
        if($getRoleNames == 'esp' || $getRoleNames == 'fsp')
        {
            // have to check if not have sufficient balance.
            $sp = ServiceProvider::where('user_id', auth()->user()->id)->first();
            $start_date = Carbon::now('Asia/Dhaka')->subMinutes(20)->toDateTimeString();
            $end_date = Carbon::now('Asia/Dhaka')->toDateTimeString();
            if ($sp->withdrawable_balance > 0)
            {
                $rejector_id = auth()->user()->id;

                $services = $sp->services->pluck('category_id')->toArray();

                $newOrders = Order::whereDoesntHave('orderReject', function ($query) use ($rejector_id) {
                    return $query->where('rejector_id', '=', $rejector_id);
                })->where('status', 0)
                ->whereIn('category_id', $services)
                ->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)
                ->orderBy('id', 'DESC')
                ->limit(5)
                ->get();
                if(count($newOrders) > 0)
                {
                    return OrderResource::collection($newOrders);
                }
            }
        }
        return ['data' => []];
    }

    public function rejectOrder(Request $request)
    {
        $order_id = $request->order_id;


        $order = Order::find($request->order_id);
        if($order->status == 6)
        {
            return response(['message' => 'The order has been canceled by the mistrimama']);
            exit();
        }
        
        try
        {
            DB::beginTransaction();
            OrderReject::create([
                'order_id' => $order_id,
                'rejector_id' => auth()->user()->id,
            ]);
            DB::commit();

            return response()->json(['message' => 'Order reject Successfully'], 200);

        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['message' => 'Order reject failed'], 400);
        }
    }

    public function allOngoingOrder()
    {
        $order = OrderDetail::where(['service_provider_id' => auth()->user()->serviceProvider->id])->where('status', '>=', 3)->where('status', '<=', 4)->orderBy('updated_at', 'desc')->get();;
        return OrderDetailsResource::collection($order);
    }

    public function orderDetails($id)
    {
        return new OrderResource(Order::find($id));
    }
    
    public function order_details($id)
    { 
        $order = OrderDetail::where('order_id', $id)->first();
        if(!empty($order))
        {
            return new OrderDetailsResource($order);
        }
        return null;
    }

    public function phoneOrder()
    {
        // have to check if not have sufficient balance.
        $sp = ServiceProvider::where('user_id', auth()->user()->id)->first();
        if ($sp->withdrawable_balance > 0)
        {
            $services = $sp->services->pluck('category_id')->toArray();
            $newOrders = Order::where(['status' => 0, 'order_from' => 'admin'])->whereIn('category_id', $services)->whereBetween('created_at', [Carbon::now('Asia/Dhaka')
                ->subMinutes(20)
                ->toDateTimeString() , Carbon::now('Asia/Dhaka')
                ->toDateTimeString() ])
                ->orderBy('id', 'DESC')
                ->limit(5)
                ->get();
            return OrderResource::collection($newOrders);
        }
        return ['data' => null];
    }   

    public function balance()
    {
        return AccountController::balance(auth()->user()->id);
    }

    public function miniStatement()
    {
        $statement = Account::with('relWithdraw', 'relRechargeRequest')
        ->where('user_id', auth()->user()->id)
        ->orderBy('id', 'asc')
        ->get()->toArray();
        $total_balance = 0;
        $record = [];
        if(!empty($statement)){
            foreach($statement as $key => $value){
                $amount = (int)$value['amount'];
                if (in_array($value['status'], ['income','credit'])){
                    $total_balance = $total_balance + $amount;
                }
                else
                {
                    $total_balance = $total_balance - $amount;
                }

                $relations = NULL;
                if($value['ref'] == 'recharge')
                {
                    $relations = [
                        'created_at' => $value['rel_recharge_request']['created_at'],
                        'date' => $value['date'],
                        'medium' => $value['rel_recharge_request']['medium'],
                        'trxno' => $value['rel_recharge_request']['trxno'],
                        'amount' => $value['rel_recharge_request']['amount'],
                    ];
                }

                if($value['ref'] == 'withdraw')
                {
                    $relations = [
                        'created_at' => $value['rel_withdraw']['created_at'],
                        'date' => $value['date'],
                        'mfs' => $value['rel_withdraw']['mfs'],
                        'mfs_number' => $value['rel_withdraw']['mfs_number'],
                        'trxno' => $value['trxno'],
                        'amount' => $value['rel_withdraw']['amount'],
                    ];
                }

                $record[] = [
                    'id' => $key,
                    'date' => $value['date'],
                    'details' => ($value['ref'] == 'order') ? $value['details'][0] : $value['details'],
                    'details_bn' => ($value['ref'] == 'order') ? $value['details_bd'] : $value['details_bd'],
                    'trxno' => $value['trxno'],
                    'status' => $value['status'],
                    'amount' => $amount,
                    'balance' => $total_balance,
                    'ref' => $value['ref'],
                    'relations' => $relations,
                ];
            }
        }
        return array_reverse($record);
    }
    public function miniStatementHistory()
    {
        $statement = Account::with('relWithdraw', 'relRechargeRequest')
        ->where('user_id', auth()
        ->user()
        ->id)
        ->get();
        return StatementResource::collection($statement);
    }

    public function miniStatementHistoryLager()
    {

        $list = Account::with('relWithdraw', 'relRechargeRequest')->where('user_id', auth()
            ->user()
            ->id)
            ->get();
        $total_amount = 0;
        foreach ($list as $value)
        {
            if ($value->status == 'income' || $value->status == 'credit' || $value->status == 'extra_charge')
            {
                $total_amount += $value->amount;
            }
            else
            {
                $total_amount -= $value->amount;
            }
            $value->balench = $total_amount;
        }
        return $list;
    }

    public function statementsHistoryLagerExport()
    {
        $list = $this->miniStatement();
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=statement_" . date('d-m-Y') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = ['Date', 'Details', 'TXN ID #', 'Debit' , 'Credit', 'Balance'];

        $callback = function () use ($list, $columns)
        {
            $file = fopen('php://output', 'w');;
            fputcsv($file, $columns);
            for ($i = 0 ;$i <  count($list) ;$i++)
            {
                $type = '';
                if ($list[$i]['status'] == 'income' || $list[$i]['status'] == 'credit')
                {
                    $type = '+';
                }
                else
                {
                    $type = '-';
                }

                fputcsv($file, [$list[$i]['date'], $list[$i]['details'], $list[$i]['trxno'], $type  == '+' ? $list[$i]['amount'] : '' , $type  == '-' ? $list[$i]['amount'] : '' , $list[$i]['balance']

                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);

    }

    public function downloadStatment($type)
    {
        $path = public_path('invoice/');
        $fileName = 'statment.pdf';
        $data['user'] = auth()->user();
        $data['statments'] = $this->miniStatement();
        $mpdf = new \Mpdf\Mpdf(['tempDir' => public_path('tempdir'), 'mode' => 'utf-8', 'format' => 'A4']);
        
        $html = view('component.invoice.statment', $data)->render();
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
    }

    public function sendStatment(Request $request)
    {
        $request->validate([
            'email_address' => 'required|email|max:255',
        ]);
        
        $to_address = $request->email_address;

        $path = public_path('invoice/');
        $fileName = 'statment.pdf';
        $data['user'] = auth()->user();
        $data['statments'] = $this->miniStatement();
        $mpdf = new \Mpdf\Mpdf(['tempDir' => public_path('tempdir'), 'mode' => 'utf-8', 'format' => 'A4']);
        
        $html = view('component.invoice.statment', $data)->render();
        $mpdf->WriteHTML($html);
        $mpdf->Output($path. '/' . $fileName, 'F');

        $from_address = env('MAIL_USERNAME');

        return SiteConfigsController::sendMailFunction($from_address, $to_address, "Generated Statment", "Generated Statment", $path. '/' . $fileName);
    }

    public function downloadCashoutHistory($type)
    {
        $path = public_path('invoice/');
        $fileName = 'cashout_history.pdf';
        $data['cashout_histories'] = WithdrawRequest::where(['user_id' => auth()->user()->id, 'approve' => 1])->limit(20)->orderBy('id', 'desc')->get();
        $mpdf = new \Mpdf\Mpdf(['tempDir' => public_path('tempdir'), 'mode' => 'utf-8', 'format' => 'A4']);
        
        $html = view('component.invoice.cashout_history', $data)->render();
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
    }

    public function sendCashoutHistory(Request $request)
    {
        $request->validate([
            'email_address' => 'required|email|max:255',
        ]);
        
        $to_address = $request->email_address;

        $path = public_path('invoice/');
        $fileName = 'cashout_history.pdf';
        $data['cashout_histories'] = WithdrawRequest::where(['user_id' => auth()->user()->id, 'approve' => 1])->limit(20)->orderBy('id', 'desc')->get();
        $mpdf = new \Mpdf\Mpdf(['tempDir' => public_path('tempdir'), 'mode' => 'utf-8', 'format' => 'A4']);
        
        $html = view('component.invoice.cashout_history', $data)->render();
        $mpdf->WriteHTML($html);
        $mpdf->Output($path. '/' . $fileName, 'F');

        $from_address = env('MAIL_USERNAME');

        return SiteConfigsController::sendMailFunction($from_address, $to_address, "Cashout History", "Cashout History", $path. '/' . $fileName);
    }

    public function downloadRecharegHistory($type)
    {
        $path = public_path('invoice/');
        $fileName = 'recharge_history.pdf';
        $data['recharge_histories'] = RechargeRequest::where(['user_id' => auth()->user()->id, 'status' => 1])->limit(20)->orderBy('id', 'desc')->get();
        $mpdf = new \Mpdf\Mpdf(['tempDir' => public_path('tempdir'), 'mode' => 'utf-8', 'format' => 'A4']);
        
        $html = view('component.invoice.recharge_history', $data)->render();
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
    }

    public function sendRechargeHistory(Request $request)
    {
        $request->validate([
            'email_address' => 'required|email|max:255',
        ]);
        
        // return $request->all();
        $to_address = $request->email_address;

        $path = public_path('invoice/');
        $fileName = 'recharge_history.pdf';
        $data['recharge_histories'] = RechargeRequest::where(['user_id' => auth()->user()->id, 'status' => 1])->limit(20)->orderBy('id', 'desc')->get();
        $mpdf = new \Mpdf\Mpdf(['tempDir' => public_path('tempdir'), 'mode' => 'utf-8', 'format' => 'A4']);
        
        $html = view('component.invoice.recharge_history', $data)->render();
        $mpdf->WriteHTML($html);
        $mpdf->Output($path. '/' . $fileName, 'F');

        $from_address = env('MAIL_USERNAME');

        return SiteConfigsController::sendMailFunction($from_address, $to_address, "Recharge History", "Recharge History", ''.$path. '/' . $fileName.'');
    }

    public function lastRecharge()
    {
        return RechargeRequest::where('user_id', auth()->user()
            ->id)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function lastWithdraw()
    {
        return WithdrawRequest::where('user_id', auth()
            ->user()
            ->id)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function lastOrder()
    {
        $order = OrderDetail::where('service_provider_id', auth()->user()
            ->serviceProvider
            ->id)
            ->where('state', 4)
            ->orderBy('id', 'desc')
            ->first();
        return new OrderDetailsResource($order);
    }

    public function scheme()
    {
        $sp_id = auth()->user()->serviceProvider->id;
        $scheme = Scheme::where('service_provider_id', $sp_id)->get();
        if(!empty($scheme))
        {
            return SchemeResource::collection($scheme);
        }
        return null;
    }

    public function schemeLastWeek()
    {
        $sp = auth()->user()->serviceProvider;
        $today = Carbon::now('Asia/Dhaka')->toDateTimeString();
        $orders = OrderDetail::where('service_provider_id', $sp->id)
        ->whereIn('order_from', ['esp', 'fsp', 'comrade'])
        ->where('state', 4)
        ->whereBetween('finish_time', [$sp->last_scheme_update, $today])->get()
        ->count();
            
        $data['last_scheme_update'] = $sp->last_scheme_update;
        $data['total_order'] = $orders;

        if($orders <= 15)
        {
            $data['scheme']['a'] = round(($orders * 100) / 15);
            $data['scheme']['b'] = 0;
            $data['scheme']['c'] = 0;
        }else if($orders > 15 && $orders <= 20)
        {
            $data['scheme']['a'] = round((15 * 100) / 15);
            $data['scheme']['b'] = round((($orders-15) * 100) / 5);
            $data['scheme']['c'] = 0;
        }else if($orders > 20 && $orders <= 30)
        {
            $data['scheme']['a'] = round((15 * 100) / 15);
            $data['scheme']['b'] = round((20 * 100) / 20);
            $data['scheme']['c'] = round((($orders-20) * 100) / 10);
        }
        else
        {
            $data['scheme']['a'] = round((15 * 100) / 15);
            $data['scheme']['b'] = round((20 * 100) / 20);
            $data['scheme']['c'] = round((30 * 100) / 30);
        }
        $data['scheme_percentage'] = round(array_sum($data['scheme'])/3);
        
        return response()->json($data, 200);
    }

    public function orderHistory()
    { 
        $orders = OrderDetail::where('service_provider_id', auth()->user()
            ->serviceProvider
            ->id)
            ->where('state', 4)
            ->orderBy('id', 'desc')
            ->get();
        return OrderDetailsResource::collection($orders);
    }

    public function orderHistorySelf()
    {
        $orders = OrderDetail::where('service_provider_id', auth()->user()
            ->serviceProvider
            ->id)
            ->whereIn('order_from', ['esp', 'fsp', 'comrade'])
            ->where('state', 4)
            ->orderBy('id', 'desc')
            ->get();
        return OrderDetailsResource::collection($orders);
    }
    public function orderHistoryOthers()
    {
        $orders = OrderDetail::where('service_provider_id', auth()->user()
            ->serviceProvider
            ->id)
            ->where('state', 4)
            ->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])
            ->orderBy('id', 'desc')
            ->get();
        return OrderDetailsResource::collection($orders);
    }

    public function todaysIncome()
    {

        return Account::where('user_id', auth()->user()
            ->id)
            ->where('created_at', 'like', '%' . date('Y-m-d') . '%')
            ->where('ref', 'order')
            ->whereIn('status', ['income', 'credit'])
            ->sum('amount');
    }

    public function yesterdaysIncome()
    {
        return Account::where('user_id', auth()
            ->user()
            ->id)
            ->where('created_at', 'like', '%' . date('Y-m-d', strtotime("-1 days")) . '%')
            ->where('ref', 'order')
            ->whereIn('status', ['income', 'credit'])
            ->sum('amount');
    }

    public function thisMonthIncome()
    {
        return Account::where('user_id', auth()
            ->user()
            ->id)
            ->whereMonth('created_at', Carbon::now()
            ->month)
            ->where('ref', 'order')
            ->whereIn('status', ['income', 'credit'])
            ->sum('amount');
    }

    public function lastMonthIncome()
    {
        return Account::where('user_id', auth()
            ->user()
            ->id)
            ->whereMonth('created_at', Carbon::now()
            ->subMonth()
            ->month)
            ->where('ref', 'order')
            ->whereIn('status', ['income', 'credit'])
            ->sum('amount');
    }

    public function totalSelfOrder()
    {
        $sp = auth()->user()->serviceProvider;
        return $selfOrders = OrderDetail::where('service_provider_id', $sp->id)
            ->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])
            ->where('state', 4)
            ->count();
    }

    public function totalSelfOrderIncome()
    {
        $sp = auth()->user()->serviceProvider;
        $selfOrders = OrderDetail::where('service_provider_id', $sp->id)
            ->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])
            ->where('state', 4)
            ->pluck('order_id');
        return Account::where('user_id', auth()
            ->user()
            ->id)
            ->where('ref', 'order')
            ->whereIn('ref_key', $selfOrders)->sum('amount');
    }

    public function totalMMOrder()
    {
        $sp = auth()->user()->serviceProvider;
        return $selfOrders = OrderDetail::where('service_provider_id', $sp->id)
            ->whereIn('order_from', ['esp', 'fsp', 'comrade'])
            ->where('state', 4)
            ->count();
    }

    public function totalMMOrderIncome()
    {
        $sp = auth()->user()->serviceProvider;
        $mmOrders = OrderDetail::where('service_provider_id', $sp->id)
            ->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])
            ->where('state', 4)
            ->pluck('order_id');
        return Account::where('user_id', auth()
            ->user()
            ->id)
            ->where('ref', 'order')
            ->whereIn('ref_key', $mmOrders)->sum('amount');
    }

    public function thisMonthSelfJob()
    {
        $sp = auth()->user()->serviceProvider;
        return OrderDetail::where('service_provider_id', $sp->id)
            ->whereIn('order_from', ['esp', 'fsp', 'comrade'])
            ->where('state', 4)
            ->whereMonth('finish_time', Carbon::now()
            ->month)
            ->get()
            ->count();
    }

    public function thisMonthSelfIncome()
    {

        $sp = auth()->user()->serviceProvider;
        $orders = OrderDetail::where('service_provider_id', $sp->id)
            ->whereIn('order_from', ['esp', 'fsp', 'comrade'])
            ->where('state', 4)
            ->whereMonth('finish_time', Carbon::now()
            ->month)
            ->pluck('order_id');
        return Account::where('user_id', auth()
            ->user()
            ->id)
            ->where('ref', 'order')
            ->whereIn('ref_key', $orders)->sum('amount');
    }

    public function thisMonthMMJob()
    {
        $sp = auth()->user()->serviceProvider;
        $orders = OrderDetail::where('service_provider_id', $sp->id)
            ->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])
            ->where('state', 4)
            ->whereMonth('finish_time', Carbon::now()
            ->month)
            ->pluck('order_id');
        return Account::where('user_id', auth()
            ->user()
            ->id)
            ->where('ref', 'order')
            ->whereIn('ref_key', $orders)->sum('amount');
    }

    public function thisMonthMMIncome()
    {
        $sp = auth()->user()->serviceProvider;
        $orders = OrderDetail::where('service_provider_id', $sp->id)
            ->whereNotIn('order_from', ['esp', 'fsp', 'comrade'])
            ->where('state', 4)
            ->whereMonth('finish_time', Carbon::now()
            ->month)
            ->pluck('order_id');
        return Account::where('user_id', auth()
            ->user()
            ->id)
            ->where('ref', 'order')
            ->whereIn('ref_key', $orders)->sum('amount');
    }

    public function lowBalance()
    {
        $withdrawable_limit = (int) Setting::first()->withdrawable_limit;
        $services_providers = ServiceProvider::orderBy('id', 'desc')->get();
        $data['services_providers'] = $services_providers->where('withdrawable_balance', '<', $withdrawable_limit);
        return view('sp.low_balance', $data);
    }

    public function become_service_provider(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:100',
            'phone' => 'numeric|digits:11|required|regex:/(01)[0-9]{9}/',
            'email' => 'nullable|email|max:100',
            'area' => 'required',
            'other_service' => 'nullable',
            'service_categoris' => 'required'
        ]);

        try
        {
            DB::beginTransaction();
            if ($request->has('service_categoris'))
            {
                $data['service_categoris'] = implode(',', $data['service_categoris']);
            }
            BecomeServiceProvider::create($data);
            
            DB::commit();

            return response()->json(['message' => 'Our concern department will contact with you soon'], 200);

        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json(['message' => 'Save failed'], 400);
        }
    }

    public function become()
    {
        $data['services_providers'] = BecomeServiceProvider::orderBy('id', 'desc')->get();
        return view('sp.become', $data);
    }

    public function become_delete($id)
    {
        BecomeServiceProvider::where('id', $id)->delete();
        toastr()->success('Service Provider removed successfully');
        return redirect()->back();
    }

    public function accountUpgrade($id, $status)
    {
        // if($status == 'approve')
        // {
        //     $change = ServiceProviderCategoryUpdateHistory::where('user_id', $id)->first();
        //     ServiceProvider::where('user_id', $id)->update([
        //         'category' => $change->requested_category
        //     ]);
        // }
        // ServiceProviderCategoryUpdateHistory::where('user_id', $id)->delete();
        
        // NotificationController::upgradeServiceProviderCategoryApproveDenyNotification($id, $status);
        // toastr()->success('Service Provider account upgrade request '.$status.'');
        // return redirect()->back();
    }

    public function documentUpload(Request $request)
    {
        $type = $request->type;
        $photo = $request->photo;
        $id = auth()->user()->id;

        $media_filename = NULL;
        if ($photo != '')
        {
            $media_filename = base64_to_image($photo, 'upload/sp');
            $dir = 'upload/sp';
        }

        try
        {
            DB::beginTransaction();

            if($type == 'nidFrontPhoto')
            {
                $message = 'NID front photo';
                Media::where(['user_id' => $id, 'type' => 'nid_front'])->delete();
                Media::create([
                    'user_id' => $id,
                    'type' => 'nid_front',
                    'dir' => $dir,
                    'filename' => $media_filename,
                    'status' => 'pending',
                ]);
            }

            if($type == 'nidBackPhoto')
            {
                $message = 'NID back photo';
                Media::where(['user_id' => $id, 'type' => 'nid_back'])->delete();
                Media::create([
                    'user_id' => $id,
                    'type' => 'nid_back',
                    'dir' => $dir,
                    'filename' => $media_filename,
                    'status' => 'pending',
                ]);
            }

            if($type == 'tradeLicesePhoto')
            {
                $message = 'Trade License photo';
                Media::where(['user_id' => $id, 'type' => 'trade_lic_image'])->delete();
                Media::create([
                    'user_id' => $id,
                    'type' => 'trade_lic_image',
                    'dir' => $dir,
                    'filename' => $media_filename,
                    'status' => 'pending',
                ]);
            }

            if($type == 'tinCertificatePhoto')
            {
                $message = 'Tin Certificate photo';
                Media::where(['user_id' => $id, 'type' => 'tin_image'])->delete();
                Media::create([
                    'user_id' => $id,
                    'type' => 'tin_image',
                    'dir' => $dir,
                    'filename' => $media_filename,
                    'status' => 'pending',
                ]);
            }
            $id = auth()->user()->serviceProvider->id;
            NotificationController::documentUploadNotification($id, $message);
            
            DB::commit();
            return response()->json(['message' => ''.$message.' uploaded. Our concern department will approve soon'], 200);
        }
        catch(\Exception $e)
        {
            DB::rollback();
        }
        return response()->json(['message' => ''.$message.' uploaded failed'], 200);
    }

    public function documentUploadRequest()
    {
        $data['services_providers'] = Media::with('serviceProvider')->select('user_id')->where(['status' => 'pending'])->groupBy('user_id')->orderBy('id', 'desc')->get();
        return view('sp.document_upload', $data);
    }
}

