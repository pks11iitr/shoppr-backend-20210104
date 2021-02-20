<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\Order;
use App\Models\Settings;
use App\Models\ShopprWallet;
use App\Services\Notification\FCMNotification;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request){

        $user=$request->user;

        $orders=Order::with('details')
            ->where('shoppr_id', $user->id)
            ->where('status', '!=', 'Pending')
            ->orderBy('id', 'desc')
            ->select('id','refid', 'total', 'service_charge', 'created_at', 'status')
            ->paginate(10);

        foreach($orders as $o){
            if($o->status=='Confirmed')
                $o->show_deliver_button=1;
            else
                $o->show_deliver_button=0;
        }

        return [
            'status'=>'success',
            'data'=>compact('orders')
        ];

    }

    public function details(Request $request, $order_id){
        $user=$request->user;

        $order=Order::with(['details'])
            ->where('shoppr_id', $user->id)
            ->select('id', 'refid', 'total','service_charge', 'status', 'payment_status', 'balance_used')
            ->findOrFail($order_id);

        if($order->status=='Confirmed')
            $order->show_deliver_button=1;
        else
            $order->show_deliver_button=0;

        return [
            'status'=>'success',
            'data'=>compact('order')
        ];

    }

    public function deliverOrder(Request $request, $order_id){

        $user=$request->user;

        $order=Order::with('cutomer')
        ->where('shoppr_id', $user->id)
            ->where('status', 'Confirmed')
            ->findOrFail($order_id);

        $commission=Settings::where('name', 'Commission')->first();
        $commission=$commission->value??0;

        $order->rider_commission=$commission;
        $order->payment_status='Paid';
        $order->status='Delivered';
        $order->delivered_at=date('Y-m-d H:i:s');
        $order->save();

        ShopprWallet::updatewallet($user->id,'Order Id:'.$order->refid.' Delivered', 'Debit', $order->total,$order->id);

        $message=ChatMessage::create([
            'chat_id'=>$order->chat_id,
            'message'=>'',
            'type'=>'rating',
            //'price'=>$request->price,
            'quantity'=>0,
            'direction'=>0,
        ]);

        $order->customer->notify(new FCMNotification('New Message', $message->message??'', array_merge($message->only('message'), ['type'=>'chat', 'chat_id'=>''.$message->chat_id]),'chat_screen'));

        return [
            'status'=>'success',
            'message'=>'Order Has Been Delivered'
        ];

    }
}
