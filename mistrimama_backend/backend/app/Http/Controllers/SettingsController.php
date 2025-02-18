<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\SMS;
use App\User;
use App\Setting;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    
    public function index()
    {
        $datas = Setting::where('name','refer')->get();
        return view('settings.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('setting.create');
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
            'name'        => 'required|max:55',
            'description' => 'string',
            'benifits'    => 'string',
            'name_bn'        => 'required|max:55',
            'description_bn' => 'string',
            'benifits_bn'    => 'string',
            'position'    => 'required|numeric',
        ]);

        $data['slug'] = str_slug($request->name);
        //$data['description_bn'] = $request->description_bn);
        //dd($request);

        try
        {
            DB::beginTransaction();

            if ($request->has('thumb') && $request->thumb != '') {
                // file upload funtion base64_to_image($request->thumb , $location_path)
                //$data['thumb'] = $request->file('thumb')->store('/');
                $data['thumb'] = base64_to_image($request->thumb, 'upload/categories');
            }
    
            if ($request->has('icon') && $request->icon != '') {
                // file upload funtion base64_to_image($request->icon , $location_path)
                // $data['icon'] = $request->file('icon')->store('/');
                $data['icon'] = base64_to_image($request->icon, 'upload/categories');
            }
    
            if ($request->has('opt_image') && $request->opt_image != '') {
                // file upload funtion base64_to_image($request->icon , $location_path)
                // $data['opt_image'] = $request->file('opt_image')->store('/');
                $data['opt_image'] = base64_to_image($request->opt_image, 'upload/categories');
            }

            Category::create($data);

            DB::commit(); 

            toastr()->success('Category upload successfull');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            toastr()->error('Category upload falied');
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $data['setting'] = Setting::where('id', $id)->first();
        return view('settings.edit', $data);
    }

    public function show($slug)
    {
        return Setting::where('slug', $slug)->first();
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name'     => 'required'
        ]);  
        try{
            DB::beginTransaction();

            Setting::where('id', $id)->update([
                'value'     => $request->value
            ]);  
            DB::commit();

            toastr()->success('Setting update successfully');
        }catch(\Exception $e){
            toastr()->error('Setting update failed');
            DB::rollback();
        }
        return redirect()->back();
    }
}
