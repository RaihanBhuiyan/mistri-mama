<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MfsNumber extends Model
{
    //
    protected $table = "mfs_number_log";
    protected $fillable = [
        'mfs_number', 'user_id', 'status', 'created_at'
    ];
}
