<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Wallet;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request, $chat_id){
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

        $wallet_balance = Wallet::balance($user->id);

        return [
            'status'=>'success',
            'message'=>'',
            'data'=>compact('items', 'total', 'service_charge', 'grand_total', 'wallet_balance')
        ];

    }


    public function cancelProduct(Request $request, $message_id){
        $user=$request->user;
        $message=ChatMessage::whereHas('chat', function($chat)use($user){
            $chat->where('customer_id', $user->id);
        })
            ->findOrFail($message_id);


        if($message->is_completed==true){
            return [
                'status'=>'failed',
                'message'=>'This item cannot be cancelled now'
            ];
        }

        $message->status='cancelled';
        $message->save();

        $items=ChatMessage::whereHas('chat', function($chat)use($user){
            $chat->where('customer_id', $user->id);
        })
            ->where('chat_id', $message->chat_id)
            ->where('type', 'product')
            ->where('status', 'accepted')
            ->where('order_id', null)
            ->get();
        if(!count($items))
            return [
                'status'=>'failed',
                'message'=>'No items in the cart'
            ];

        ChatMessage::where('type', 'total')
            ->where('chat_id',$message->chat_id)
            ->delete();

        $total=0;
        foreach($items as $i){
            $total=$total+$i->price;
        }
        $service_charge=100;

        $grand_total=$total+$service_charge;

        $message=ChatMessage::create([
            'chat_id'=>$message->chat_id,
            'message'=>$total.','.$service_charge.','.$grand_total,
            'type'=>'total',
            'quantity'=>0,
            'direction'=>0,
            'price'=>$grand_total
        ]);



        return [
            'status'=>'success',
            'message'=>'Product has been cancelled',
            'data'=>compact('items', 'total', 'service_charge', 'grand_total')
        ];
    }
}
