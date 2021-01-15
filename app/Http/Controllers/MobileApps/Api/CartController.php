<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request, $chat_id){
        $user=$request->user;

        $items=ChatMessage::where('customer_id', $user->id)
            ->where('chat_id', $chat_id)
            ->where('type', 'product')
            ->where('status', 'accepted')
            ->get();

        $total=0;
        foreach($items as $i){
            $total=$total+$i->price;
        }
        $service_charge=100;

        $grand_total=$total+$service_charge;

        return [
            'status'=>'success',
            'message'=>'',
            'data'=>compact('items', 'total', 'service_charge', 'grand_total')
        ];

    }


    public function cancelProduct(Request $request, $message_id){
        $user=$request->user;
        $message=ChatMessage::whereHas('chat', function($chat)use($user){
            $chat->where('customer_id', $user->id);
        })->findOrFail($message_id);

        $message->status='cancelled';
        $message->save();

        $items=ChatMessage::where('chat_id', $message->chat_id)
            ->where('shoppr_id', $user->id)
            ->where('type', 'product')
            ->where('status', 'accepted')
            ->get();
        if(!count($items))
            return [
                'status'=>'failed',
                'message'=>'No items in the cart'
            ];

        Chat::where('type', 'total')
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
