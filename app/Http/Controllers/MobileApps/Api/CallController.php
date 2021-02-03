<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Shoppr;
use App\Services\Agora\RtcTokenBuilder;
use App\Services\Notification\FCMNotification;
use Illuminate\Http\Request;

class CallController extends Controller
{

    public function audiocall(Request $request){

    }

    public function initiateVideocall(Request $request, $chat_id){

        $user=$request->user;

        $chat=Chat::with('shoppr')
            ->findOrFail($chat_id);
        //$receiver=Shoppr::findOrFail($user_id);
        if(!empty($request->channel_name)){
            $channel_name=$request->channel_name;
        }else {
            $channel_name = 'customerchannel' . $user->id.'_'.$chat->shoppr->id;
        }
        $token=RtcTokenBuilder::buildTokenWithUid($channel_name, $user->id,1,0);

        if(empty($request->channel_name)){
            $chat->shoppr->notify(new FCMNotification('Calling..', 'Call from '.$user->name, [
                'token'=>$token,
                'channel'=>$channel_name,
                'caller'=>$user->name,
                'image'=>$user->image,
                'id'=>''.$user->id,
                'type'=>'call'
            ]));
        }

//        if($chat->shoppr->id==$user->id)
//            $user_id=$user->id.rand(11,99);
//        else
//            $user_id=$user->id;

        $user_id=1;

        return [
            'status'=>'success',
            'data'=>compact('token', 'channel_name', 'user_id')
        ];

    }
}
