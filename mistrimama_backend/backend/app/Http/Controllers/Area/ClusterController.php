<?php

namespace App\Http\Controllers\Area;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cluster;
use App\Division;
use App\Http\Resources\ClusterResource;

class ClusterController extends Controller
{
    public function cluster($division_id)
    {
        return ClusterResource::collection(Cluster::where(['division_id' => $division_id])->get());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['division'] = Division::all();
        $data['cluster'] = Cluster::all();
        return view('cluster.index', $data);
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
            'division_id' => 'required',
            'name'        => 'required|string',
            'code'        => 'required',
        ]);

        
        if (Cluster::create($data)) {
            toastr()->success('success');
            return back();

        } else {
            return 'Something worng';
        } 

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cluster  $cluster
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cluster = Cluster::find($id);

        if ($cluster) {
            return $cluster;
        } else {
            return response(['message' => 'Something worng']);

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cluster  $cluster
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cluster = Cluster::find($id);
        $division = Division::all();
        return view('cluster.edit', compact('division','cluster'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cluster  $cluster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cluster = Cluster::find($id);
        $data    = $request->validate([
            'division_id' => 'required',
            'name'        => 'required|string',
            'code'        => 'required',
        ]);
        
        if ($cluster->update($data)) {
            toastr()->success('Cluster Updated');
            return redirect()->route('cluster.index');
        } else {
            toastr()->success('Cluster Failed');
            return redirect()->back();
        }

        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cluster  $cluster
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cluster = Cluster::find($id);

        if ($cluster->delete()) {
            toastr()->success('Cluster Updated');
            return redirect()->route('cluster.index');
        } else {
            toastr()->success('Cluster Failed');
            return redirect()->back();
        }
    }
}
