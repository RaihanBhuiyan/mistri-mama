<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedbackOption extends Model
{
    protected $table = "feedback_options";
    protected $fillable = [
        'question_id', 'title' 
    ];

    public function options()
    {
        return $this->belongsTo('App\FeedbackQuestion', 'question_id', 'id');
    }

}
