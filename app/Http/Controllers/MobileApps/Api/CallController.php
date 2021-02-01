<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Shoppr;
use App\Services\Agora\RtcTokenBuilder;
use App\Services\Notification\FCMNotification;
use Illuminate\Http\Request;

class CallController extends Controller
{

    public function audiocall(Request $request){

    }

    public function initiateVideocall(Request $request, $user_id){

        $user=$request->user;
        $receiver=Shoppr::findOrFail($user_id);

        $channel_name='customerchannel'.$user->id;
        $token=RtcTokenBuilder::buildTokenWithUid($channel_name, $user->id,1,0);

        $receiver->notify(new FCMNotification('Calling..', 'Call from '.$user->name, [
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
