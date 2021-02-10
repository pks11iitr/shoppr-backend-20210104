<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index(Request $request){

        $user=$request->user;

        $orders=Order::with('details')
            ->where('user_id', $user->id)
            ->where('status', '!=', 'Pending')
            ->orderBy('id', 'desc')
            ->select('id','refid', 'total', 'service_charge', 'created_at', 'status')
            ->get();

        return [
            'status'=>'success',
            'data'=>compact('orders')
        ];

    }

    public function initiateOrder(Request $request, $chat_id){
        $user=$request->user;

        $chat=Chat::with('customer','shoppr')
            ->where('customer_id', $user->id)
            ->findOrFail($chat_id);

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
            'shoppr_id'=>$chat->shoppr_id,
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



    public function details(Request $request, $order_id){
        $user=$request->user;

        $order=Order::with(['details'])
            ->where('user_id', $user->id)
            ->select('id', 'refid', 'total','service_charge', 'status', 'payment_status', 'balance_used')
            ->findOrFail($order_id);

        return [
                'status'=>'success',
                'data'=>compact('order')
            ];

    }
}