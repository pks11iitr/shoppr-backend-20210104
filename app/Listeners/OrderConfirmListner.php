<?php

namespace App\Listeners;

use App\Events\OrderConfirmed;
use App\Mail\SendMail;
use App\Models\ChatMessage;
use App\Models\Notification;
use App\Models\Order;
use App\Services\Notification\FCMNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class OrderConfirmListner
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
     * @param  OrderConfirmed  $event
     * @return void
     */
    public function handle(OrderConfirmed $event)
    {
        $order=$event->order;

        $this->sendNotifications($order);

    }


    public function sendNotifications($order){

        $message='Congratulations! Your order of Rs. '.$order->grandTotal().' at Shopr is successfull. Order Reference ID: '.$order->refid;

        $title='Order Confirmed';

        $user=$order->customer;

        //customer notification
        Notification::create([
            'user_id'=>$order->user_id,
            'title'=>$title,
            'description'=>$message,
            'data'=>null,
            'type'=>'individual',
            'user_type'=>'CUSTOMER'
        ]);

        $message=ChatMessage::create([
            'chat_id'=>$order->chat_id,
            'message'=>$message,
            'type'=>'text',
            //'price'=>$request->price,
            'quantity'=>0,
            'direction'=>1,
            'order_id'=>$order->id
        ]);

        $user->notify(new FCMNotification($title, $message . $user->name, [
            'type'=>'order', 'title'=>$title, 'message'=>$message
        ]));

        //shoppr notification

        $shopper_message='Order ID:'.$order->refid.' has been confirmed with total amount of Rs. '.$order->grandTotal().' at ShopR is successful.';

        Notification::create([
            'user_id'=>$order->shoppr_id,
            'title'=>$title,
            'description'=>$shopper_message,
            'data'=>null,
            'type'=>'individual',
            'user_type'=>'SHOPPR'
        ]);

        $order->shoppr->notify(new FCMNotification($title, $shopper_message , [
            'type'=>'order', 'title'=>$title, 'message'=>$shopper_message
        ]));

    }
}
