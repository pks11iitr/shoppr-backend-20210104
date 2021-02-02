<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Customer;
use App\Models\Shoppr;
use App\Services\Agora\RtcTokenBuilder;
use App\Services\Notification\FCMNotification;
use Illuminate\Http\Request;

class CallController extends Controller
{
    public function initiateVideocall(Request $request, $chat_id){

        $user=$request->user;
        //$receiver=Customer::findOrFail($user_id);
        $chat=Chat::with('customer')
            ->findOrFail($chat_id);

        $channel_name='shopprchannel'.$user->id;
        $token=RtcTokenBuilder::buildTokenWithUid($channel_name, $user->id,1,0);

        $chat->customer->notify(new FCMNotification('Calling..', 'Call from '.$user->name, [
            'token'=>$token,
            'channel'=>$channel_name,
            'caller'=>$user->name,
            'image'=>$user->image,
            'id'=>$user->id
        ]));

        return [
            'status'=>'success',
            'data'=>compact('token', 'channel_name')
        ];

    }
}
