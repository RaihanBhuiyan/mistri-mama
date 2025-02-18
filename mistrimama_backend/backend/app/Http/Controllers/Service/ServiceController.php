<?php

namespace App\Http\Controllers\Service;

use App\Category;
use App\Http\Controllers\Controller;
use App\Service;
use App\ServiceBit;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\ServiceBitResource;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['service']  = Service::orderBy('position', 'asc')->get();
        return view('service.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['category'] = Category::orderBy('position', 'asc')->get();
        return view('service.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Service $service)
    {
        $data = $request->validate([
            'category_id'   => 'required',
            'name'          => 'string|required',
            'description'   => 'string',
            'name_bn'       => 'string|required',
            'description_bn'=> 'string',
            'position'      => 'numeric|required',
        ]);

        $data['slug'] = str_slug($request->name);

        try
        {
            DB::beginTransaction();

            if ($request->has('thumb') && $request->thumb != '') {
                // file upload funtion base64_to_image($request->thumb , $location_path)
                //$data['thumb'] = $request->file('thumb')->store('/');
                $data['thumb'] = base64_to_image($request->thumb, 'upload/services');
            }
            Service::create($data);

            DB::commit();

            toastr()->success('Service upload successfull');
            return redirect()->route('service.index');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            toastr()->error('Service upload falied');
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return Service::where('slug', $slug)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::all();
        $service = Service::find($id);

        return view('service.edit', compact('service', 'category'));
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
        $service = Service::find($id);
        $data    = $request->validate([
            'category_id' => 'required',
            'name'        => 'string|required',
            'description' => 'string',
            'name_bn'        => 'string|required',
            'description_bn' => 'string',
            'position'    => 'numeric|required',
        ]);
        
        try
        {
            DB::beginTransaction();

            if ($request->has('thumb') && $request->thumb != '') {
                //Storage::delete($request->thumb);
                //$data['thumb'] = $request->file('thumb')->store('/');
                $data['thumb'] = base64_to_image($request->thumb, 'upload/services');
                if(!empty($service->thumb))
                {
                    $thumb_path = public_path('upload/services/'.$service->thumb);
                    if (file_exists($thumb_path))
                    {
                        unlink($thumb_path);
                    }
                }
            }

            $service->update($data);

            DB::commit();

            toastr()->success('Service update successfull');
            return redirect()->route('service.index');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            toastr()->error('Service update failed');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::find($id);

        if($service->serviceBits->count() > 0)
        {
            toastr()->error('Service can not be delete. This subordinate service bit must be deleted first.');
            return redirect()->back();
        }

        try
        {
            DB::beginTransaction();
            if(!empty($service->thumb))
            {
                $thumb_path = public_path('upload/services/'.$service->thumb);
                if (file_exists($thumb_path))
                {
                    unlink($thumb_path);
                }
            }
            $service->delete();

            DB::commit();

            toastr()->success('Service deleted successfull');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            toastr()->error('Service Delete Failed');
        }
        return redirect()->back();
    }

    public function getPopulerServices()
    {
        $service = Service::where('is_populer', 1)->orderBy(DB::raw('RAND()'))->limit(20)->get();
        if(!empty($service))
        {
            return ServiceResource::collection($service);
        }
        return null;
    }

    public function getSuggestedServices()
    {
        $auth_client_id = auth()->user()->id;
        // $auth_client_id = 6;
        $suggestsed = DB::select("SELECT services.id, services.name AS service_name, CONCAT('".env('APP_URL')."/upload/services/', services.thumb) AS thumb, services.description, categories.name AS categgory_name FROM services 
        JOIN order_items ON order_items.service_id = services.id 
        JOIN categories ON categories.id = services.category_id
        JOIN orders ON orders.id = order_items.order_id
        WHERE orders.user_id = ".$auth_client_id."
        GROUP BY services.id, services.name, services.thumb, services.description, categories.name ORDER BY COUNT(*) DESC LIMIT 0,20");

        if(!empty($suggestsed))
        {
            $count = count($suggestsed);
            $populer = DB::select("SELECT services.id, services.name AS service_name, CONCAT('".env('APP_URL')."/upload/services/', services.thumb) AS thumb, services.description, categories.name AS categgory_name FROM services 
            JOIN categories ON categories.id = services.category_id
            WHERE services.is_populer = 1 ORDER BY RAND() LIMIT 0, ".(20-$count)."");
            if(!empty($populer)){
                $suggestsed = array_merge($suggestsed, $populer);
            }
        }
        $data['service'] = [];
        if(!empty($suggestsed)){
        foreach($suggestsed as $key => $service){
            $data['service'][$service->id] = [
                "id" => $service->id,
                "service_name" => $service->service_name,
                "thumb" => $service->thumb,
                "description" => $service->description,
                "categgory_name" => $service->categgory_name
            ];
            $service_bit = ServiceBit::where('service_id', $service->id)->get();
            $data['service'][$service->id]['service_bit'] = ServiceBitResource::collection($service_bit);
        }
        }
        return response()->json($data);
    }

    public function togglePopulerServiceBit($id)
    {
        $service = Service::find($id);

        try
        {
            DB::beginTransaction();
            
            if($service->is_populer)
            {
                Service::where('id', $id)->update([
                    'is_populer' => 0
                ]);
                $message = 'removed on';
            }
            else
            {
                Service::where('id', $id)->update([
                    'is_populer' => 1
                ]);
                $message = 'added on';
            }

            DB::commit();

            toastr()->success('Service '.$message.' populer service.');
            return redirect()->back();
        }
        catch(\Exception $e)
        {
            DB::rollback();
        }
        toastr()->error('Service Update Failed');
        return redirect()->back();
    }
}
