<?php

namespace App\Http\Controllers\Promo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Promocode;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PromoCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promocode = Promocode::all();
        return view('promocode.index', compact('promocode'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('promocode.create');
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
            'promo_type' => 'required|in:percentage,fixed_amount',
            'promocode' => 'required|string|max:50',
            'cash' => 'numeric|required_if:promo_type,==,fixed_amount|nullable',
            'percent' => 'numeric|between:0,100|required_if:promo_type,==,percentage|nullable',
            'up_to' => 'numeric|required_if:promo_type,==,percentage|nullable',
            'uses_count' => 'required|numeric',
            'details' => 'required|string|max:100',
            'validity_date' => 'required|date_format:Y-m-d'
        ]);

        $data['promocode'] = strtoupper($data['promocode']);
        $data['status'] = 1;
        $data['type'] = $request->promo_type;
        if($request->promo_type == 'percentage')
        {
            $data['cash'] = 0;
        }
        if($request->promo_type == 'fixed_amount')
        {
            $data['percent'] = 0;
            $data['up_to'] = 0;
        }

        if (Promocode::create($data)) {
            toastr()->success('Promocode save successfull');
        } else {
            toastr()->error('Promocode save failed');
        }
        return back();
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $promocode = Promocode::find($id);
        return view('promocode.edit', compact('promocode'));
    }


    public function update(Request $request, $id)
    {
        $promocode = Promocode::find($id);
        $data = $request->validate([
            'promo_type' => 'required|in:percentage,fixed_amount',
            'promocode' => 'required|string|max:50',
            'cash' => 'numeric|required_if:promo_type,==,fixed_amount|nullable',
            'percent' => 'numeric|between:0,100|required_if:promo_type,==,percentage|nullable',
            'up_to' => 'numeric|required_if:promo_type,==,percentage|nullable',
            'uses_count' => 'required|numeric',
            'details' => 'required|string|max:100',
            'validity_date' => 'required|date_format:Y-m-d'
        ]);

        $data['promocode'] = strtoupper($data['promocode']);
        $data['status'] = 1;
        $data['type'] = $request->promo_type;
        if($request->promo_type == 'percentage')
        {
            $data['cash'] = 0;
        }
        if($request->promo_type == 'fixed_amount')
        {
            $data['percent'] = 0;
            $data['up_to'] = 0;
        }

        if ($promocode->update($data)) {
            toastr()->success('Promocode Updated successfull');
        } else {
            toastr()->error('Promocode Updated failed');
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // It doesn't have to be deleted, but it can expire.
        // $promocode = Promocode::find($id);
        // try
        // {
        //     DB::beginTransaction();

        //     $promocode->delete();

        //     DB::commit();

        //     toastr()->success('Deleted sccuessfully!');
        // }
        // catch(\Exception $e)
        // {
        //     DB::rollback();
        //     toastr()->success('Promocode delete Failed');
        // }
        // return redirect()->back();
    }
}
