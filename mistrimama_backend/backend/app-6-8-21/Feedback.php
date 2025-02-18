<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = "feedbacks";
    protected $fillable = [
        'order_id', 'question_id', 'type', 'service_providers', 'user_id'
    ];

    public function relFeedbackAnswer()
    {
        return $this->hasOne('App\FeedbackAnswer', 'feedback_id', 'id');
    }
  
}
