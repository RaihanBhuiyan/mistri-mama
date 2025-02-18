<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jiggasha;

class JiggashaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jiggashas = Jiggasha::all();
        return view('jiggasha.index', compact('jiggashas'));
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
            'type'       => 'required',
            'title'       => 'required',
            'discription'       => 'required',
        ]);

        if (Jiggasha::create($data)) {
            toastr()->success('jiggasa create successfull');
        } else {
            toastr()->error('jiggasa create Failed');
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
        $jiggasha = Jiggasha::find($id);
        return view('jiggasha.edit', compact('jiggasha'));
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
       $Jiggasha = Jiggasha::find($id);
        $data        = $request->validate([
            'type'       => 'required',
            'title'       => 'required',
            'discription'       => 'required',
        ]);

        if ($Jiggasha->update($data)) {
            toastr()->success('Jiggasha Updated');
        } else {
            toastr()->error('Jiggasha Failed');
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
        $Jiggasha = Jiggasha::find($id);

        if ($Jiggasha->delete()) {
            toastr()->success('Jiggasha Deleted');
        } else {
            toastr()->success('Jiggasha Failed');
        }
        return redirect()->back();
    }

    public function jiggasha($type)
    {
        $jiggasha = Jiggasha::where('type', $type)->orderBy('id', 'desc')->get();
        if($jiggasha)
        {
            return response()->json($jiggasha, 200);
        }
        return response()->json(NULL, 400);
    }
}
