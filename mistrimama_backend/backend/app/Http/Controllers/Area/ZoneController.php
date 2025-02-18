<?php

namespace App\Http\Controllers\Area;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Zone;
use App\Cluster;
use App\Http\Resources\ZoneResource;

class ZoneController extends Controller
{
    public function zone($cluster_id)
    {
        return ZoneResource::collection(Zone::where(['status' => 0, 'cluster_id' => $cluster_id])->get());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $zone = Zone::all();
        $cluster = Cluster::all();
        return view('zones.index', compact('zone', 'cluster'));
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
        $data = $request->validate([
            'cluster_id' => 'required',
            'name'       => 'required',
            'code'       => 'required',
        ]);

        if (Zone::create($data)) {
            toastr()->success('success');
            return back();
        } else {
            return 'Something worng';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $zone = Zone::find($id);

        if ($zone) {
            return $zone;
        } else {
            return response(['message' => 'Something worng']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $zone = Zone::find($id);
        $cluster = Cluster::all();
        return view('zones.edit', compact('zone', 'cluster'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $zone = Zone::find($id);
        $data        = $request->validate([
            'cluster_id' => 'required',
            'name'       => 'required|string',
            'code'       => 'required',
        ]);

        if ($zone->update($data)) {
            toastr()->success('Zone Updated');
            return redirect()->route('zones.index');
        } else {
            toastr()->success('Zone Failed');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $zone = Zone::find($id);
        if($zone->status==0){
            $status=1;
        }else{
            $status=0;
        }
        if ($zone->update(['status' => $status])) {
            toastr()->success('Zone Updated');
            return redirect()->back();
        } else {
            toastr()->success('Zone Failed');
            return redirect()->back();
        }

    }
}
