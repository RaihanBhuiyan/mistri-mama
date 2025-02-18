<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Baboharbidhi;

class BaboharbidhiController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['baboharbidhis'] = Baboharbidhi::all();
        return view('baboharbidhi.index', $data);
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
            'title'       => 'required',
            'discription'       => 'required',
        ]);

        if (Baboharbidhi::create($data)) {
            toastr()->success('baboharbidhi Create successfull');
        } else {
            toastr()->error('baboharbidhi Create failed');
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
        $data['baboharbidhi'] = Baboharbidhi::find($id);
        return view('baboharbidhi.edit', $data);
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
       $baboharbidhi = Baboharbidhi::find($id);
        $data        = $request->validate([
            'title'       => 'required',
            'discription'       => 'required',
        ]);

        if ($baboharbidhi->update($data)) {
            toastr()->success('baboharbidhi Updated');
        } else {
            toastr()->success('baboharbidhi update Failed');
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
        $baboharbidhi = Baboharbidhi::find($id);

        if ($baboharbidhi->delete()) {
            toastr()->success('baboharbidhi Deleted');
        } else {
            toastr()->success('baboharbidhi delete Failed');
        }
        return redirect()->back();
    }

    public function baboharbidhi()
    {
        $baboharbidhi = Baboharbidhi::orderBy('id', 'desc')->get();
        if($baboharbidhi)
        {
            return response()->json($baboharbidhi, 200);
        }
        return response()->json(NULL, 400);
    }
}
