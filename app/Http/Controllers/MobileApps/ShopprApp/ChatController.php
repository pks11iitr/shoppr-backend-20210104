<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\RejectedChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function chathistory(Request $request){

        $user=$request->user;

        $users=Chat::with(['customer'=>function($user){
            $user->select('id', 'name', 'image');
        }])
            ->where('shoppr_id', $user->id)
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
                'name'=>$userchat->customer->name,
                'image'=>$userchat->customer->image,
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

    public function acceptChat(Request $request, $chat_id){
        $user=$request->user;

        $chat=Chat::where('shoppr_id', 0)
            ->find($chat_id);
        if(!$chat)
            return [
                'status'=>'failed',
                'message'=>"This booking is no longer available"
            ];

        $chat->shoppr_id=$user->id;
        $chat->save();

        return [
            'status'=>'success',
            'message'=>'Please start discussion with customer'
        ];

    }

    public function rejectChat(Request $request, $chat_id){
        $user=$request->user;

        $chat=Chat::where('shoppr_id', 0)
            ->find($chat_id);
        if(!$chat)
            return [
                'status'=>'failed',
                'message'=>"This booking is no longer available"
            ];

        RejectedChat::create([
            'shoppr_id'=>$user->id,
            'chat_id'=>$chat_id
        ]);

        return [
            'status'=>'success',
            'message'=>'Please start discussion with customer'
        ];

    }


    public function availableChats(Request $request){
        $user=$request->user;

        $chats=Chat::with(['customer'=>function($user){
            $user->select('id', 'name', 'image');
        }])
        ->whereDoesntHave('rejectedby', function($rejectedby) use($user){
            $rejectedby->where('rejected_chats.shoppr_id', $user->id);
        })
        ->where('shoppr_id', 0) // unassigned chats
        ->orderBy('id', 'desc')
        ->get();

        $userchats=[];
        foreach($chats as $userchat){

            $distance=distance($userchat->lat, $userchat->lang, $user->lat, $user->lang);

            $userchats[]=[
                'id'=>$userchat->id,
                'name'=>$userchat->customer->name,
                'image'=>$userchat->customer->image,
                'distance'=>round($distance, 2),
                'date'=>$userchat->created_at
            ];
        }

        return [
            'status'=>'success',
            'message'=>'',
            'data'=>compact('userchats')
        ];
    }




}
