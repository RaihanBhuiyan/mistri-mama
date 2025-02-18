<?php

namespace App\Http\Controllers\Offer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\NotificationController;
use App\Http\Resources\OfferResource;
use App\Category;
use App\ServiceBit;
use App\Offer;
use App\Promocode;
use Carbon\Carbon;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['offers'] = Offer::orderBy('id', 'desc')->get();
        return view('offers.index', $data);
    }

    public function allOffer()
    {
        $offers = Offer::where('expire_date', '>=', Carbon::now()->toDateString())->orderBy('id', 'desc')->get();
        if(!empty($offers))
        {
            return response()->json(OfferResource::collection($offers), 200);
        }
        return response()->json(NULL, 400);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['categories'] = Category::orderBy('position', 'asc')->get();
        $data['promo_code'] = Promocode::where(['status' => 1])->where('validity_date', '>=', Carbon::now()->toDateString())->get();
        return view('offers.create', $data);
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
            'title' => 'required|max:55',
            'offers_for' => 'required|max:55',
            'description' => 'string',
            'expire_date' => 'required|date_format:Y-m-d',
            'offers_type' => 'required|in:general_offer,referral_offer,quick_order_offer,discount_offer',
        ]);

        
            
        $data['offer_image'] = NULL;
        if ($request->has('offer_image') && $request->offer_image != '') {
            $data['offer_image'] = base64_to_image($request->offer_image, 'upload/offers');
        }
        if($request->offers_type == 'quick_order_offer')
        {
            $data['alt_description'] = $request->find_service;
        }
        if($request->offers_type == 'discount_offer')
        {
            $service_bit = ServiceBit::where(['id' => $request->discount_offer['service_bit_id'], 'service_id' => $request->discount_offer['service_id']])->first();

            $discount_offer = $request->discount_offer;
            $discount_offer['category_id'] = $service_bit->category_id;
            $data['alt_description'] = json_encode($discount_offer);
        }

        if (Offer::create($data)) {
            NotificationController::offerNotification($data);
            toastr()->success('Offer create successfull');
        } else {
            toastr()->success('Offer create failed');
        }
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Offer::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['offer'] = Offer::find($id);
        return view('offers.edit', $data);
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
        $offer = Offer::find($id);

        $data = $request->validate([
            'title' => 'required|max:55',
            'offers_for' => 'required|max:55',
            'description' => 'string',
            'expire_date' => 'required|date_format:Y-m-d',
        ]);
        
        if ($request->has('offer_image') && $request->offer_image != '') {
            $data['offer_image'] = base64_to_image($request->offer_image, 'upload/offers');
            if(!empty($offer->offer_image))
            {
                $offer_image_path = public_path('upload/offers/'.$offer->offer_image);
                if (file_exists($offer_image_path))
                {
                    unlink($offer_image_path);
                }
            }
        }

        if ($offer->update($data)) {
            NotificationController::offerNotification($data);
            toastr()->success('Offer update successfull');
        } else {
            toastr()->success('Offer update failed');
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
        $offer = Offer::find($id);
        if(!empty($Offer->offer_image))
        {
            $offer_image_path = public_path('upload/offers/'.$offer->offer_image);
            if (file_exists($offer_image_path))
            {
                unlink($offer_image_path);
            }
        }
        if ($offer->delete()) {
            toastr()->success('Offer delete successfull');
        } else {
            toastr()->success('Offer delete failed');
        }
        return back();
    }
}
