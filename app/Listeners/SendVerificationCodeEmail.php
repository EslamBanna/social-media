<?php

namespace App\Listeners;

use App\Events\SendVerificationCode;
use App\Mail\VerificationMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendVerificationCodeEmail
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
    public function handle(SendVerificationCode $event)
    {
        $verificationCode = $event->verificationCode;
        $userName = $event->userName;
        $email = $event->email;
        Mail::to($email)->send(new VerificationMail($verificationCode, $userName));
    }
}
