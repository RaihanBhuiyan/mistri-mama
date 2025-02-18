<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Mail;

class TestController extends Controller
{
    public function sendEmailReminder(Request $request)
    { 
        $user = (object) null;
        $user->email = 'abdul.mazid@ssgbd.com';
        $user->name = 'Abdul mazid';

        Mail::send('emails.reminder', ['user' => $user], function ($m) use ($user) {
            $m->from('info@mistrimama.com', 'Your Application');

            $m->to($user->email, $user->name)->subject('Your Reminder!');
        });
    }
}
