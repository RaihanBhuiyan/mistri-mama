<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\FeedbackAnswer;
use App\FeedbackQuestion;
use App\FeedbackOption;
use App\Feedback;
use App\OrderSystem ;
use Illuminate\Http\Request;
use App\Events\OrderFeedBackEvent;
use App\Http\Resources\OrderResource;
use App\OrderDetail;
use App\ServiceProvider;
use App\Client;
class FeedbackAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function giveFeedbackRatinProcess(Request $request){
        
        try
        {
            DB::beginTransaction();
            $order_details = OrderDetail::where(['order_id' => $request->order_id])->first();
            
            if($request->type == 'sp_to_user'){ 
                OrderSystem::where('order_id',  $request->order_id)->update([
                    'user_rating' => $request->rating ?  $request->rating : 0 
                ]); 
                if(!empty($request->rating) && !empty($order_details->client)){ 
                    $client = $order_details->client; 
                    Client::where('user_id', $client->user_id)->update([
                        'rating' => $client->rating + $request->rating ,
                        'total_rating_order' => (empty($order_details->user_rating)) ? ($client->total_rating_order + 1) : $client->total_rating_order
                    ]);
                } 
            }
            if($request->type == 'user_to_sp'){
                OrderSystem::where('order_id',  $request->order_id)->update([
                    'sp_rating' => $request->rating ?  $request->rating : 0 
                ]);
                if(!empty($request->rating) && !empty($order_details->serviceProvider)){ 
                    $sp = $order_details->serviceProvider;
                    ServiceProvider::where('id', $sp->id)->update([
                        'rating' => $sp->rating + $request->rating,
                        'total_rating_order' =>  (empty($order_details->sp_rating)) ? ($sp->total_rating_order + 1) : $sp->total_rating_order
                    ]); 
                } 
            } 
            if(!empty($request->feedback_answer)) {
                foreach ($request->feedback_answer as $key => $value) {
                    $question_id = $value[0] ;
                    $option_id = $value[1] ;

                    $feedback = [
                        'order_id' => $request->order_id,
                        'question_id' => $question_id,
                        'service_providers' =>  $order_details->service_provider_id,
                        'type'=>  $request->type,
                        'user_id' =>  $order_details->user_id,
                    ];

                    $feed = Feedback::create($feedback); 

                    //feedback_id 	option_id 	answer
                    $feedbackOption = FeedbackOption::find($option_id);
                    $answer = array(
                        'feedback_id' => $feed->id ,
                        'option_id' => $option_id,
                        'answer' => $feedbackOption->title
                    );
                    FeedbackAnswer::create($answer); 
                    $order = OrderDetail::find($request->order_id);
                    broadcast(new OrderFeedBackEvent(new OrderResource($order)));
                }
            }

            DB::commit();
            return response()->json(['message' => 'Thank you for you feedback'], 200);
        }
        catch(\Exception $e)
        {
            return $e;
            DB::rollback();
        }
    }
 

    /**
     * Display the specified resource.
     *
     * @param  \App\FeedbackAnswer  $feedbackAnswer
     * @return \Illuminate\Http\Response
     */
    public function show(FeedbackAnswer $feedbackAnswer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FeedbackAnswer  $feedbackAnswer
     * @return \Illuminate\Http\Response
     */
    public function edit(FeedbackAnswer $feedbackAnswer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FeedbackAnswer  $feedbackAnswer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeedbackAnswer $feedbackAnswer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FeedbackAnswer  $feedbackAnswer
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeedbackAnswer $feedbackAnswer)
    {
        //
    }
}
