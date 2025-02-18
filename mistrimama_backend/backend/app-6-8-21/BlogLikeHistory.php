<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogLikeHistory extends Model
{
    protected $fillable = [
        "post_id", "ip"
    ];
}
