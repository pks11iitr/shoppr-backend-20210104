<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\Notification;
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

        $order=Order::with(['details','reviews'])
            ->where('shoppr_id', $user->id)
            ->select('id', 'refid', 'total','service_charge', 'status', 'payment_status', 'balance_used', 'discount')
            ->findOrFail($order_id);

        $payment_text=($order->payment_status=='Paid')?'Total Paid':'To Be Paid';

        if($order->status=='Confirmed')
            $order->show_deliver_button=1;
        else
            $order->show_deliver_button=0;

        return [
            'status'=>'success',
            'data'=>compact('order', 'payment_text')
        ];

    }

    public function deliverOrder(Request $request, $order_id){

        $user=$request->user;

        $order=Order::with('customer')
        ->where('shoppr_id', $user->id)
            ->where('status', 'Confirmed')
            ->findOrFail($order_id);

        $commission=0;
        if($user->pay_commission){
            $commission=Settings::where('name', 'Commission')->first();
            $commission=$commission->value??0;
            $commission=intval(($order->total*$commission)/100);
        }

        $delivery_charge=0;
        if($user->pay_delivery){
            $delivery_charge=Settings::where('name', 'Shoppr Delivery Charge')->first();
            $delivery_charge=$delivery_charge->value??0;
        }


        $order->rider_commission=$commission;
        $order->rider_delivery_charge=$delivery_charge;
        $order->payment_status='Paid';
        $order->status='Delivered';
        $order->delivered_at=date('Y-m-d H:i:s');
        $order->save();

        ShopprWallet::updatewallet($user->id,'Order Id:'.$order->refid.' Delivered', 'Debit', $order->total,$order->id);

        ChatMessage::create([
            'chat_id'=>$order->chat_id,
            'message'=>'Order has been delivered',
            'type'=>'text',
            //'price'=>$request->price,
            'quantity'=>0,
            'direction'=>1,
            'order_id'=>$order->id
        ]);

        $message=ChatMessage::create([
            'chat_id'=>$order->chat_id,
            'message'=>'',
            'type'=>'rating',
            //'price'=>$request->price,
            'quantity'=>0,
            'direction'=>0,
            'order_id'=>$order->id
        ]);


        Notification::create([
            'user_id'=>$order->user_id,
            'title'=>'Order Delivered',
            'description'=>'Order ID: '.$order->refid.' has been delivered',
            'data'=>null,
            'type'=>'individual',
            'user_type'=>'CUSTOMER'
        ]);

        $order->customer->notify(new FCMNotification('Order Delivered', 'Order ID: '.$order->refid.' has been delivered. Please rate our service.', array_merge(['message'=>'Order ID: '.$order->refid.' has been delivered. Please rate our service.', 'title'=>'Order Delivered'], ['type'=>'chat', 'chat_id'=>''.$message->chat_id]),'chat_screen'));

        $order->shoppr->notify(new FCMNotification('Order Delivered', 'Good Job! Order ID: '.$order->refid.' has been delivered.', array_merge(['message'=>'Good Job! Order ID: '.$order->refid.' has been delivered.', 'title'=>'Order Delivered'], ['type'=>'chat', 'chat_id'=>''.$message->chat_id]),'chat_screen'));

        $user->is_available=true;
        $user->save();

        return [
            'status'=>'success',
            'message'=>'Order Has Been Delivered'
        ];

    }
}
