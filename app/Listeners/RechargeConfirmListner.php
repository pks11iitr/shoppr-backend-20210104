<?php

namespace App\Listeners;

use App\Events\RechargeConfirmed;
use App\Models\Chat;
use App\Models\ChatMessage;
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

        //send notification to shoppr if any
        if(!empty($wallet->chat_id)){
            $chat=Chat::with(['shoppr', 'customer'])->find($wallet->chat_id);
            if($chat){
                $chat->shoppr->notify(new FCMNotification('Recharge Done', $wallet->customer->name.' has made a recharge of Rs.'.$wallet->amount, [
                    'type'=>'recharge'
                ], 'notification_screen'));

                ChatMessage::create([
                    'chat_id'=>$wallet->chat_id,
                    'message'=>'Wallet recharge of Rs.'.$wallet->amount.' is successful',
                    'type'=>'recharge',
                    //'price'=>$request->price,
                    'quantity'=>0,
                    'direction'=>0,
                ]);

            }
        }

    }
}
