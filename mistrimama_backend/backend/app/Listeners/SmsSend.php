<?php

namespace App\Listeners;

use App\Events\SmsEvenet;
use App\SMS;

class SmsSend
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(SmsEvenet $event)
    {
        return SMS::send($event->phone, $event->text);
    }
}
