<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedbackQuestion extends Model
{
    protected $table = "feedback_questions";

    protected $fillable = [
        'category_id', 'type', 'question'
    ];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function options()
    {
        return $this->hasMany('App\FeedbackOption', 'question_id', 'id');
    }

}
