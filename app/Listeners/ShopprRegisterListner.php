<?php

namespace App\Listeners;

use App\Events\ShopprRegistered;
use App\Models\OTPModel;
use App\Services\SMS\Msg91;
use App\Services\SMS\Nimbusit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ShopprRegisterListner
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
     * @param  ShopprRegistered  $event
     * @return void
     */
    public function handle(ShopprRegistered $event)
    {
        $otp=OTPModel::createOTP('shopper', $event->shoppr->id, 'register');
        $msg=str_replace('{{otp}}', $otp, config('sms-templates.register'));
        Nimbusit::send($event->shoppr->mobile,$msg, env('OTP_TEMPLATE_ID'));

        //register on sendbird app
        $sendbird=app('App\Services\SendBird\SendBird');
        $response=$sendbird->createUser($event->shoppr);

        if(isset($response['user_id'])){
            $event->shoppr->sendbird_token=$response['access_token']??null;
            $event->shoppr->save();
        }
    }
}
