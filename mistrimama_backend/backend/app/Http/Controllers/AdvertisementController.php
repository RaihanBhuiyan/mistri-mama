<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AdvertisementResource;
use App\Advertisement;

class AdvertisementController extends Controller
{
    public function advertisement($place_name)
    {
        $advertisement = Advertisement::where('place_name', $place_name)->orderBy('id', 'desc')->get();
        return AdvertisementResource::collection($advertisement);
        
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['advertisements'] = Advertisement::orderBy('id', 'desc')->get();
        return view('advertisement.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('advertisement.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'place_name' => 'required',
            'url' => 'nullable',
            'image' => 'required',
        ]);
        
        try {
            DB::beginTransaction();

            $data['image'] = NULL;
            if ($request->has('image') && $request->image != '') {
                $data['image'] = base64_to_image($request->image, 'upload/advertisement');
            }

            Advertisement::create($data);
            
            DB::commit();

            toastr()->success('Advertisement has been successfully submit');
            
        } catch (Exception $e) {
            DB::rollback();
            toastr()->error('Oops ! Something was wrong. try again.');
        }
        return redirect(route('advertisement.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $advertisement = Advertisement::find($id);
        
        try
        {
            DB::beginTransaction();
            if(!empty($advertisement->image))
            {
                $image_path = public_path('upload/advertisement/'.$advertisement->image);
                if (file_exists($image_path))
                {
                    unlink($image_path);
                }
            }
            $advertisement->delete();

            DB::commit();

            toastr()->success('Advertisement deleted successfull');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            toastr()->error('Advertisement Delete Failed');
        }
        return redirect()->back();
    }
}
