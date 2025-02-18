<?php

namespace App\Http\Controllers\Area;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Division;
use App\Http\Resources\DivisionResource;

class DivisionController extends Controller
{
    public function division()
    {
        return DivisionResource::collection(Division::orderBy('name', 'asc')->get());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['division'] = Division::orderBy('name', 'asc')->get();
        return view('division.index', $data);
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
            'name' => 'required',
            'code' => 'required',
        ]);

        if (Division::create($data)) {
            toastr()->success('success');
            return back();

        } else {
            return 'Something worng';
        }  

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Division  $Division
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $division = Division::find($id);

        if ($division) {
            return $division;
        } else {
            return response(['message' => 'Something worng']);

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Division  $Division
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $division = Division::find($id);
        return view('division.edit', compact('division'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Division  $Division
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $division = Division::find($id);
        $data     = $request->validate([
            'name' => 'required|string',
            'code' => 'required',
        ]);

        if ($division->update($data)) {
            toastr()->success('Division Updated');
            return redirect()->route('division.index');
        } else {
            toastr()->success('Division Failed');
            return redirect()->back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Division  $Division
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $division = Division::find($id);

        if ($division->delete()) {
            toastr()->success('Division Deleted');
            return redirect()->route('division.index'); 
        } else {
            toastr()->success('Division Delete Failed');
            return redirect()->back();
        }
    }
}
