<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function chathistory(Request $request){
        $user=$request->user;

        $users=Chat::with(['customer'=>function($user){
            $user->select('id', 'name', 'image');
        }])
            ->where('shopper_id', $user->id)
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
}
