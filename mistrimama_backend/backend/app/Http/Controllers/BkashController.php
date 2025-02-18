<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Library\Bkash\Bkash;

class BkashController extends Controller
{
    public function token()
    {
        $bkash = new Bkash();
        $bkash->getToken();
        return $bkash->createPayment(1000, 1290831);
    }
}
