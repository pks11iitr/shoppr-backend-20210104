<?php

namespace App\Listeners;

use App\Events\RechargeConfirmed;
use App\Models\Notification;
use App\Services\Notification\FCMNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RechargeConfirmListner
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
     * @param  RechargeConfirmed  $event
     * @return void
     */
    public function handle(RechargeConfirmed $event)
    {
        $wallet=$event->wallet;

        $this->sendNotifications($wallet);
    }


    public function sendNotifications($wallet){

        $message='Congratulations! Your wallet recharge of Rs. '.$wallet->amount.' at Shoppr is successfull. Order Reference ID: '.$wallet->refid;
        $title='Recharge Confirmed';

        $user=$wallet->customer;

       Notification::create([
            'user_id'=>$wallet->user_id,
            'title'=>$title,
            'description'=>$message,
            'data'=>null,
            'type'=>'individual',
           'user_type'=>'CUSTOMER'
        ]);

        $user->notify(new FCMNotification($title, $message, [
            'type'=>'recharge'
        ]));
    }
}
