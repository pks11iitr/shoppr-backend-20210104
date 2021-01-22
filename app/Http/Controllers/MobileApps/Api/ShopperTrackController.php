<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
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
                'customer'=>$chat->customer,
                'shoppr'=>$chat->shoppr
            ]

        ];

    }
}
