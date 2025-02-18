<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $fillable = [
        'name', 'email', 'phone_number', 'year_of_experience', 'salary_expectation', 'position', 'link', 'cv', 'comments', 'created_at', 'updated_at',
    ];
}
