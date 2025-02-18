<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table = "password_resets";

    protected $fillable = [
        'phone', 'otp_code', 'created_at'
    ];
}
