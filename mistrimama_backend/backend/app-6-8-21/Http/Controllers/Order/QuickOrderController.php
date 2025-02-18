<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\QuickOrder;
use App\Cluster;
use App\Promocode;
use App\Category;
use App\User;
use App\Setting;
use App\Http\Controllers\SiteConfigsController;
use Session;

class QuickOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['quickOrders'] = QuickOrder::where(['status' => 0])->orderBy('id', 'desc')->get();
        return view('quick_order.index', $data);
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
        //
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
        Session::forget('order');
        $data['clusters'] = Cluster::all();
        $data['promocodes'] = Promocode::where('status', 1)->orderBy('id', 'desc')->get();
        $data['categories'] = Category::all();
        $data['site_config'] = SiteConfigsController::siteConfigs();
        $quickOrder = QuickOrder::where(['id' => $id, 'status' => 0])->first();
        
        $user = User::where(['phone' => $quickOrder->phone])->first();

        $data['user_type'] = 'new_user';
        $data['name'] = $quickOrder->name;
        $data['phone_no'] = $quickOrder->phone;
        $data['cluster_id'] = $quickOrder->area_id;
        $data['date'] = date('d-F-Y', strtotime($quickOrder->date));
        $data['time'] = $quickOrder->time;
        if(!empty($user))
        {
            $data['user_type'] = 'old_user';
            $data['id'] = $user->id;
            $data['name'] = $user->name;
            $data['phone_no'] = $user->phone;
            $data['cluster_id'] = (!empty($quickOrder->area_id)) ? $quickOrder->area_id : $user->client->area;
        }
        $data['address'] = $quickOrder->address;
        $data['comments'] = $quickOrder->comments;
        $data['request_service'] = $quickOrder->request_service;
        return view('quick_order.edit', $data);
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
        if (QuickOrder::where(['id' => $id])->delete()) {
            toastr()->success('Order delete successfull.');
        } else {
            toastr()->success('Order delete failed.');
        }
        return redirect()->back();
    }
}
