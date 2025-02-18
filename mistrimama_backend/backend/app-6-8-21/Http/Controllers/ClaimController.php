<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Claim;

class ClaimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['claims'] = Claim::orderBy('id', 'desc')->get();
        return view('claims.index', $data);
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
            'claim' => 'required'
        ]);

        $auth_user = auth()->user();
        $getRoleNames = $auth_user->getRoleNames()->first();
        try{
            DB::beginTransaction();
            
                Claim::create([
                    'claim_by' => $auth_user->id,
                    'claims' => $request->claim,
                    'type' => $getRoleNames
                ]);
                
            DB::commit();
            return response()->json(['message' => 'Your cliam has been placed. we will contact you soon.'], 200);
        }catch(\Exception $e){
            DB::rollback();
        }
        return response()->json(['message' => 'Your cliam has not send. Please try again'], 400);
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
        //
    }
}
