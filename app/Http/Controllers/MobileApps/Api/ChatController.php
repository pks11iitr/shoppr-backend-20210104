<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Shoppr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{

    public function chathistory(Request $request){
        $user=$request->user;

        $users=Chat::with(['shoppr'=>function($user){
            $user->select('id', 'name', 'image');
        }])
            ->where('customer_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        $chatids=[];
        foreach($users as $chatid){
            $chatids[]=$chatid->id;
        }

        $messageids=[];
        if(!empty($chatids)){
            $messages=ChatMessage::whereIn('chat_id', $chatids)
                ->groupBy('chat_id')
                ->select(DB::raw('MAX(id) as message_id'))
                ->get();
            foreach($messages as $message){
                $messageids[]=$message->message_id;
            }
        }

        $messagelist=[];
        $messages=ChatMessage::whereIn('id', $messageids)
            ->get();
        foreach($messages as $message)
            $messagelist[$message->chat_id]=$message;


        $userchats=[];
        foreach($users as $userchat){
            $userchats[]=[
                'id'=>$userchat->id,
                'name'=>$userchat->shoppr->name,
                'image'=>$userchat->shoppr->image,
                'chat'=>$messagelist[$userchat->id]->message??('['.$messagelist[$userchat->id]->type.']'),
                'date'=>$messagelist[$userchat->id]->created_at
            ];
        }

        return [
            'status'=>'success',
            'message'=>'',
            'data'=>compact('userchats')
        ];
    }

    public function startChat(Request $request){

        $user=$request->user;
        $shoppr=Shoppr::first();

        $chat=Chat::create([
            'customer_id'=>$user->id,
            'shoppr_id'=>$shoppr->id
        ]);

        $message='I would like to deliver goods';

        ChatMessage::create([
            'message'=>$message,
            'direction'=>0,
            'type'=>'text',
            'chat_id'=>$chat->id
        ]);

        $share_location='Please share your location';

        ChatMessage::create([
            'message'=>$share_location,
            'direction'=>1,
            'type'=>'address-request',
            'chat_id'=>$chat->id
        ]);

        return [
            'status'=>'success',
            'message'=>'',
            'data'=>[
                'id'=>$chat->id
            ]
        ];
    }


}
