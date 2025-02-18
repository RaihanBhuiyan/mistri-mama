<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blogs extends Model
{
    protected $fillable = [
        "category_id", "title","title_bn", "slug", "url", "users_id", "image", "content","short_description","content_bn","short_description_bn","meta_title", "meta_description", "meta_keyword", "status", "published_date"
    ];
    
    public function category(){
        return  $this->belongsTo('App\BlogCategory','category_id');
    }
        
    public function users(){
        return  $this->belongsTo('App\User','users_id');
    }
        
    public function relLikeHistory(){
        return  $this->hasMany('App\BlogLikeHistory','post_id');
    }
        
    public function relComments(){
        return  $this->hasMany('App\BlogComment','post_id')->where('comment_id', null);
    }

    // thumb
    public function getThumbAttribute()
    {
        return (!empty($this->image)) ? env('APP_URL').'/upload/blogs/'. $this->image : env('APP_URL').'/upload/'.'black-thumbnail.png';
    }

    // total_comments
    public function getTotalCommentsAttribute()
    {
        return $this->relComments->count();
    }

    // total_like
    public function getTotalLikeAttribute()
    {
        return $this->relLikeHistory->count();
    }

    // is_liked
    public function getIsLikedAttribute()
    {
        $ip = getIPAddress();
        $exists_ip = $this->relLikeHistory->pluck('ip')->toArray();
        if(in_array($ip, $exists_ip))
        {
            return true;
        }
        return false;
    }
}
