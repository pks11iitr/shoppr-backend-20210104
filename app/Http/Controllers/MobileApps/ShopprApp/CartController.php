<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\Settings;
use App\Models\Wallet;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request, $chat_id){

        $user=$request->user;

        $items=ChatMessage::whereHas('chat', function($chat)use($user,$chat_id){
            $chat->where('shoppr_id', $user->id);
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
        $settings=Settings::where('name', 'Service Fee')->first();
        $service_charge=$settings->value??0;

        $wallet_balance = Wallet::balance($user->id);

        $discount=ChatMessage::whereHas('chat', function($chat)use($user,$chat_id){
            $chat->where('customer_id', $user->id);
        })
            ->where('chat_id', $chat_id)
            ->where('type', 'discount')
            ->where('order_id', null)
            ->first();

        $discount_amount=$discount->price??0;

        $grand_total=$total+$service_charge-($discount->price??0);

        return [
            'status'=>'success',
            'message'=>'',
            'data'=>compact('items', 'total', 'service_charge', 'grand_total', 'wallet_balance', 'discount_amount')
        ];

    }
}
