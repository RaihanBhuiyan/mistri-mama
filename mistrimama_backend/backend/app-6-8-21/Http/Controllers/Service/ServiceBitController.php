<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\ServiceBit;
use App\Service;
use App\Category;
use App\ServiceBitsFeaturesHistory;
use Illuminate\Http\Request;

class ServiceBitController extends Controller
{

    public function index()
    {
        $data['service_bits'] = ServiceBit::orderBy('id', 'desc')->get();
        return view('servicebit.index', $data);
    }


    public function create()
    {
        $data['categories'] = Category::orderBy('position', 'asc')->get();
        return view('servicebit.create', $data);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'service_id'                => 'required|exists:services,id',
            'name'                      => 'string|required',
            'name_bn'                   => 'string|required',
            'mrp_price'                 => 'numeric|required',
            'price'                     => 'numeric|required',
            'service_provider_price'    => 'numeric|required',
            'unit_remarks'              => 'numeric|required',
            'additional_unit_remarks'   => 'numeric|required',
            'brief'                     => 'string',
            'brief_bn'                  => 'string',
            'unit_type'                 => 'string|required',
        ]);

        $tag_values = $request->input('tags_values');
        
        $data['category_id'] = Service::find($request->service_id)->category_id;

        $data['tags'] = (!empty($tag_values)) ? implode(",", $tag_values) : NULL;

        unset($data['service_provider_price']);
        $data['additional_price'] = $request->input('price');
        $data['commission'] = $request->input('service_provider_price');
        
        if (ServiceBit::create($data)) {
            toastr()->success('Service Bit create Success');
        } else {
            toastr()->error('Service Bit create Failed');
        }
        return redirect()->back();
    }


    public function edit($id)
    {
        $data['categories'] = Category::orderBy('position', 'asc')->get();
        $data['service_bit'] = ServiceBit::find($id);
        return view('servicebit.edit', $data);
    }


    public function update(Request $request, $id)
    {
        // return $request->all();
        $serviceBit = ServiceBit::find($id);
        $data = $request->validate([
            'service_id'                => 'required|exists:services,id',
            'name'                      => 'string|required',
            'name_bn'                   => 'string|required',
            'mrp_price'                 => 'numeric|required',
            'price'                     => 'numeric|required|lte:mrp_price',
            'service_provider_price'    => 'numeric|required',
            'unit_remarks'              => 'numeric|required',
            'additional_unit_remarks'   => 'numeric|required',
            'brief'                     => 'string',
            'brief_bn'                  => 'string',
            'unit_type'                 => 'string|required',
            'is_features'               => 'nullable',
            'features_image'            => 'nullable',
        ]);

        $tag_values = $request->input('tags_values');

        try
        {
            DB::beginTransaction();
            if($request->is_features == 'false')
            {
                ServiceBitsFeaturesHistory::where(['category_id' => $serviceBit->category_id])->delete();
            }

            if($request->is_features == 'true')
            {
                $features_image = '';
                if ($request->has('features_image') && $request->features_image != '')
                {
                    $features_image = base64_to_image($request->features_image, 'upload/services');

                    ServiceBitsFeaturesHistory::where(['category_id' => $serviceBit->category_id])->delete();
                    ServiceBitsFeaturesHistory::create([
                        'category_id' => $serviceBit->category_id,
                        'service_bit_id' => $serviceBit->id,
                        'features_image' => $features_image,
                    ]);
                }
            }

            unset($data['is_features']);
            unset($data['features_image']);
            unset($data['service_provider_price']);
            $data['tags'] = (!empty($tag_values)) ? implode(",", $tag_values) : NULL;
            $data['additional_price'] = $request->input('price');
            $data['commission'] = $request->input('service_provider_price');

            $serviceBit->update($data);

            DB::commit();

            toastr()->success('Service Bit Updated');
            return redirect()->back();
        }
        catch(\Exception $e)
        {
            dd($e);
            DB::rollback();
        }
        toastr()->error('Service Bit Update Failed');
        return redirect()->back();
    }


    public function destroy($id)
    {
        $servicebit = servicebit::find($id);
        if ($servicebit->delete()) {
            toastr()->success('Service Bit Deleted');
        } else {
            toastr()->error('Service Bit Delete Failed');
        }
        return redirect()->back();
    }

    public function removeHotServiceBit($id)
    {        
        if (ServiceBitsFeaturesHistory::where('service_bit_id', $id)->delete()) {
            toastr()->success('Hot Service Bit Removed');
        } else {
            toastr()->error('Hot Service Bit Removed Failed');
        }
        return redirect()->back();
    }

    public function getServiceBit($id)
    {
        $service_bit = servicebit::where('service_id', $id)->pluck('name', 'id');
        return response()->json($service_bit);
    }
}
