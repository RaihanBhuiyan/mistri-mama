<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\AccountsHeading;

class AccountsHeadingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['headings'] = AccountsHeading::all();
        return view('account.heading.index', $data);
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
        $request->validate([
            'type'    => 'required|in:investment,expenses,revenue',
            'title' => 'required|unique:accounts_headings,title',
        ]);

        try{
            DB::beginTransaction();
            
            $heading = new AccountsHeading();
            $heading->title = $request->title;
            $heading->heading_type = $request->type;
            $heading->type = headingType($request->type);
            $heading->save();

            DB::commit();

            toastr()->success('Transaction heading create successfully!');
            return redirect()->back();

        }catch(\Exception $e){
            DB::rollback();
        }
        toastr()->error('Transaction heading create failed');
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
        $data['heading'] = AccountsHeading::find($id);
        return view('account.heading.edit', $data);
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
            'type'    => 'required|in:investment,expenses,revenue',
            'title' => 'required|unique:accounts_headings,title,'.$id,
        ]);
        
        try{
            DB::beginTransaction();
            
            $heading = AccountsHeading::find($id);
            $heading->title = $request->title;
            $heading->save();

            DB::commit();

            toastr()->success('Transaction heading update successfully!');
            return redirect()->back();

        }catch(\Exception $e){
            DB::rollback();
        }
        toastr()->error('Transaction heading update failed');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getAccountHeadings($type)
    {
        $headings = AccountsHeading::where('heading_type', $type)->orderBy('id', 'desc')->get();
        if(count($headings) > 0)
        {
            return response()->json($headings, 200);
        }
        return response()->json(["There is no transaction headings"], 400);
    }
}
