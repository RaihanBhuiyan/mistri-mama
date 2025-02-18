<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\FeedbackQuestion;
use App\FeedbackOption;
use App\Category;
use App\FeedbackAnswer;
use App\Feedback;
use App\OrderSystem ;
use App\Events\OrderFeedBackEvent;
use App\Http\Resources\OrderResource;
use App\OrderDetail;
use App\ServiceProvider;
use App\Client;
use App\Http\Resources\FeedbackQuestionResource;
use App\Http\Resources\OrderRatingNFeedbackResource;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['feedback'] = FeedbackQuestion::all();
        return view('feedback.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['categories'] = Category::pluck('name', 'id');
        return view('feedback.create', $data);
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
            'category_id' => 'required|array|min:1',
            'type'        => 'required',
            'question'    => 'required',
        ]);


        try{
            DB::beginTransaction();

            if(!empty($data)){
                foreach($data['category_id'] as $value){
                    $questionArray = [
                        'category_id' => $value,
                        'type' => $data['type'],
                        'question' => $data['question'],
                    ];
                    $feedback = FeedbackQuestion::create($questionArray);

                    foreach ($request->option as $key => $value) {
                        if($value){
                            $option_data =  array(
                                'question_id' => $feedback->id ,
                                'title' =>  $value
                            );
                            FeedbackOption::create($option_data);
                        }  
                    }
                }
            }

            DB::commit();

            toastr()->success('Feedback create successfull');

        }catch(\Exception $e){
            DB::rollback();
            toastr()->error('Feedback create failed.');
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function show(Feedback $feedback)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['feedback'] = FeedbackQuestion::find($id);
        $data['categories'] = Category::pluck('name', 'id');
        return view('feedback.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $feedback = FeedbackQuestion::find($id);
        $data = $request->validate([
            'category_id' => 'required',
            'type'       => 'required',
            'question'       => 'required'
        ]);

        if ($feedback->update($data)) { 
            $options = FeedbackOption::where('question_id',$id)->get(); 
            $option_update  = $request->option_update ;  
                foreach ( $options  as $key => $value) {
                    if($option_update[$key] !=''){ 
                        $option_data =  array( 
                            'title' =>  $option_update[$key],
                        ); 
                        FeedbackOption::where('id', $value->id)->update($option_data);  
                    }else{
                        FeedbackOption::where('id', $value->id)->delete(); 
                    }
                } 

            if($request->option_new){
                foreach ($request->option_new as $key => $value) {
                    if($value){
                        $option_data =  array(
                            'question_id' => $id ,
                            'title' =>  $value
                        );
                        FeedbackOption::create($option_data);
                    }  
                }
            }

            toastr()->success('feedback Updated');
        } else {
            toastr()->error('feedback update Failed');
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $feedback = FeedbackQuestion::find($id);

        if ($feedback->delete()) {
            toastr()->success('feedback Deleted');
        } else {
            toastr()->error('feedback Failed');
        }
        return redirect()->back();
    }
    
    public function giveFeedbackRatinProcess(Request $request)
    {   
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

    
    public function checkFeedbackOrder($type)
    {
        $order = [];
        if($type == 'sp')
        {
            $id = auth()->user()->serviceProvider->id;
            $order = OrderDetail::where(['status' => 5, 'state' => 4, 'service_provider_id' => $id, 'user_rating' => NULL])->first();
        }
        if($type == 'client')
        {
            $id = auth()->user()->id;
            $order = OrderDetail::where(['status' => 5, 'state' => 4, 'user_id' => $id, 'sp_rating' => NULL])->first();
        }
        if($type == 'comrade')
        {
            $id = auth()->user()->comrade->id;
            $order = OrderDetail::where(['status' => 5, 'state' => 4, 'comrade_id' => $id, 'user_rating' => NULL])->first();
        }
        
        if (!empty($order)) {
            return new OrderRatingNFeedbackResource($order, $type);
        }
        return response(['data' => $order]);
    }
}
