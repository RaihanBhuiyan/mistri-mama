<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Slider;
use App\Http\Resources\SliderResources;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['sliders'] = Slider::all();
        return view('slider.index', $data);
    }

    public function sliderJson()
    {
        return SliderResources::collection(Slider::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('slider.create');
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
            'name'  => 'required|max:55',
            'image' => 'required',
        ]);
        
        $data['image'] = NULL;
        if ($request->hasFile('image')) {
            
            $fileNameWithExt = $request->file('image')->getClientOriginalName();
            // get file name
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // get extension
            $extension = $request->file('image')->getClientOriginalExtension();

            $fileNameToStore = time().rand(100,999).'.'.$extension;
            // upload
            // $path = $request->file('image')->move('upload/web', $fileNameToStore);
            // $data['image'] = base64_to_image($request->image, 'upload/web');
            $request->image->move(public_path('/upload/web'), $fileNameToStore);
            $data['image'] = $fileNameToStore;
        }
        
        $slider = Slider::create($data);
        if ($slider) {
            toastr()->success('New Slider uploaded');
        } else {
            toastr()->error('Slider upload falid! Try again.');
            
        }
        return redirect()->route('slider.index');
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
        $slider = Slider::find($id);
        try
        {
            DB::beginTransaction();
            if(!empty($slider->image))
            {
                $image_path = public_path('upload/web/'.$slider->image);
                if (file_exists($image_path))
                {
                    unlink($image_path);
                }
            }

            $slider->delete();

            DB::commit();

            toastr()->success('Slider deleted successfull');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            toastr()->error('Slider Delete Failed');
        }
        return redirect()->back();
    }
}
