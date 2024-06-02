<?php

namespace App\Listeners;

use App\Events\SendForgotPasswordCode;
use App\Mail\ForgotPasswordMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendForgotPasswordEmail
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
    public function handle(SendForgotPasswordCode $event)
    {
        $code = $event->code;
        $userName = $event->userName;
        $email = $event->email;
        Mail::to($email)->send(new ForgotPasswordMail($userName,$code));
    }
}
