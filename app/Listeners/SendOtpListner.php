<?php

namespace App\Listeners;

use App\Events\SendOtp;
use App\Services\SMS\Msg91;
use App\Services\SMS\Nimbusit;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOtpListner implements ShouldQueue
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
     * @param  SendOtp  $event
     * @return void
     */
    public function handle(SendOtp $event)
    {
        Nimbusit::send($event->mobile,$event->message, env('OTP_TEMPLATE_ID'));
    }
}
