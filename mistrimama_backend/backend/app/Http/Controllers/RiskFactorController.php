<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\RiskFactor;
use App\Http\Resources\RiskFactorResource;

class RiskFactorController extends Controller
{
    public function getRiskFactors($type)
    {
        $risk_factor = RiskFactor::where('type', $type)->get();
        if(!empty($risk_factor))
        {
            return RiskFactorResource::collection($risk_factor);
        }
        return NULL;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['risk_factors'] = RiskFactor::orderBy('id', 'desc')->get();
        return view('risk_factors.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('risk_factors.create');
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
            'type' => 'required',
            'title' => 'required',
            'particulars' => 'required'
        ]);


        $particulars = [];

        if(!empty($request->particulars))
        {
            $string = strip_tags(str_replace("</li><li>", ",", $request->particulars));
            $particulars = explode(",", $string);
        }
        
        try
        {
            DB::beginTransaction();
                RiskFactor::create([
                    'title' => $request->title,
                    'type' => $request->type,
                    'particulars' => json_encode($particulars)
                ]);
            DB::commit();
            toastr()->success('Risk Factor Added successfully');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            toastr()->error('Risk Factor Added Failed!');
        }
        return redirect()->back();

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
        $data['risk_factor'] = RiskFactor::where('id', $id)->first();
        return view('risk_factors.edit', $data);
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
        $request->validate([
            'type' => 'required',
            'title' => 'required',
            'particulars' => 'required'
        ]);


        $particulars = [];

        if(!empty($request->particulars))
        {
            $string = strip_tags(str_replace("</li><li>", ",", $request->particulars));
            $particulars = explode(",", $string);
        }
        
        try
        {
            DB::beginTransaction();
                RiskFactor::where('id', $id)->update([
                    'title' => $request->title,
                    'type' => $request->type,
                    'particulars' => json_encode($particulars)
                ]);
            DB::commit();
            toastr()->success('Risk Factor Update successfully');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            toastr()->error('Risk Factor Update Failed!');
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
        $risk_factor = RiskFactor::find($id);
        try
        {
            DB::beginTransaction();
            $risk_factor->delete();
            DB::commit();

            toastr()->success('Deleted successfull');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            toastr()->error('Delete Failed');
        }
        return redirect()->back();
    }
}
