<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    protected $fillable = [
        "name", "phone", "author_thumb", "post_id", "comment_id", "message", "status", "created_at"
    ];
        
    public function relReply(){
        return  $this->hasMany('App\BlogComment','comment_id')->where('comment_id', '!=', null);
    }
        
    public function relParent(){
        return  $this->belongsTo('App\BlogComment','comment_id')->where('comment_id', null);
    }
}
