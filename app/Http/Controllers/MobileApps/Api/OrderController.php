<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function initiateOrder(Request $request, $chat_id){
        $user=$request->user;

        $items=ChatMessage::whereHas('chat', function($chat)use($user,$chat_id){
            $chat->where('customer_id', $user->id);
        })
            ->where('chat_id', $chat_id)
            ->where('type', 'product')
            ->where('status', 'accepted')
            ->where('order_id', null)
            ->get();

        $total=0;
        foreach($items as $i){
            $total=$total+$i->price;
        }
        $service_charge=100;

        $grand_total=$total+$service_charge;

        $refid=env('MACHINE_ID').time();

        $order=Order::create([
            'user_id'=>$user->id,
            'chat_id'=>$chat_id,
            'refid'=>$refid,
            'total'=>$total,
            'service_charge'=>$service_charge
        ]);

        return [
            'status'=>'success',
            'message'=>'',
            'data'=>[
                'order_id'=>$order->id
            ]
        ];
    }
}
