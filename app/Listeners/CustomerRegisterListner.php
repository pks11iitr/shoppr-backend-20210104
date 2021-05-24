<?php

namespace App\Listeners;

use App\Events\CustomerRegistered;
use App\Models\OTPModel;
use App\Services\SendBird;
use App\Services\SMS\Msg91;
use App\Services\SMS\Nimbusit;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerRegisterListner implements ShouldQueue
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
     * @param  CustomerRegistered  $event
     * @return void
     */
    public function handle(CustomerRegistered $event)
    {
        //send OTP

        $otp=OTPModel::createOTP('customer', $event->user->id, 'register');
        $msg=str_replace('{{otp}}', $otp, config('sms-templates.register'));
        Nimbusit::send($event->user->mobile,$msg, env('OTP_TEMPLATE_ID'));

        //register on sendbird app
        $sendbird=app('App\Services\SendBird\SendBird');
        $response=$sendbird->createUser($event->user);

        if(isset($response['user_id'])){
            $event->user->sendbird_token=$response['access_token']??null;
            $event->user->save();
        }


    }
}
