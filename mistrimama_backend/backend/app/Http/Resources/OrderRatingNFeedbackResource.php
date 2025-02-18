<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\FeedbackQuestion;

class OrderRatingNFeedbackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    
    private $type;

    public function __construct($resource, $type)
    {
        parent::__construct($resource);
        $this->resource = $resource;
        $this->type = $type;
    }
    public function toArray($request)
    {
        $questions = [];
        
        $common_questions = FeedbackQuestion::where('type', 'common')->get();
        if($common_questions->count() > 3)
        {
            $common_questions = $common_questions->random(3);
        }

        $common_questions = FeedbackQuestionResource::collection($common_questions);

        if($this->type == 'sp')
        {
            $questions = FeedbackQuestionResource::collection($this->relServiceProviderFeedbacks);
        }
        if($this->type == 'client')
        {
            $questions = FeedbackQuestionResource::collection($this->relUserFeedbacks);
        }
        if($this->type == 'comrade')
        {
            $questions = FeedbackQuestionResource::collection($this->relServiceProviderFeedbacks);
        }

        $merged = collect($common_questions)->merge($questions);
        
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'order_no' => $this->order_no,
            'rating' => 0,
            'feedback_questions' => $merged->all(),
        ];
    }
}
