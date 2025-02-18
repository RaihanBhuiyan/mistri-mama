<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedbackAnswer extends Model
{
    protected $table = "feedback_answers";
    protected $fillable = [
        'feedback_id', 'option_id', 'answer'
    ];
}
