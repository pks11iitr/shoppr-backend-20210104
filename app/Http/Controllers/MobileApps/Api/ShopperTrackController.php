<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ShopperTrackController extends Controller
{
    public function track(Request $request, $chat_id){
        $chat=Chat::with(['customer'=>function($customer){
            $customer->select('id', 'name', 'mobile', 'lat', 'lang');
        }, 'shoppr'=>function($shoppr){
            $shoppr->select('id', 'name', 'mobile', 'lat', 'lang');
        }])->findOrFail($chat_id);


        return [

            'status'=>'success',
            'data'=>[
                'customer'=>[
                    'id'=>$chat->customer->id,
                    'name'=>$chat->customer->name,
                    'mobile'=>$chat->customer->mobile,
                    'lat'=>$chat->lat,
                    'lang'=>$chat->lang,
                ],
                'shoppr'=>$chat->shoppr
            ]

        ];

    }

    public function checkOrderStatus(Request $request, $message_id){
        $message=ChatMessage::with('order')->findOrFail($message_id);
        if($message->order->status=='Delivered')
            return [
                'status'=>'failed',
                'message'=>'This order has been delivered'
            ];

        if($message->order->status=='Cancelled')
            return [
                'status'=>'failed',
                'message'=>'This order has been cancelled'
            ];

        return [
            'status'=>'success'
        ];
    }
}
