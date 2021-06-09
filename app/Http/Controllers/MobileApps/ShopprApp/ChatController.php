<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Checkin;
use App\Models\RejectedChat;
use App\Services\Notification\FCMNotification;
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

        $type=Checkin::where('shoppr_id', $user->id)
            ->orderBy('id', 'desc')
            ->first();

        $type=$type->type??'checkout';

        return [
            'status'=>'success',
            'message'=>'',
            'data'=>compact('userchats', 'type')
        ];
    }

    public function acceptChat(Request $request, $chat_id){
        $user=$request->user;

        if(!$user->isactive)
            return [
                'status'=>'failed',
                'message'=>'You account has been deactivated'
            ];

        if(!$user->is_available)
            return [
                'status'=>'failed',
                'message'=>'Please complete your last order before accepting this'
            ];
        DB::beginTransaction();
            $chat=Chat::where('shoppr_id', 0)
                ->lockForUpdate()
                ->find($chat_id);

            if(!$chat){
                DB::commit();
                return [
                    'status'=>'failed',
                    'message'=>"This booking is no longer available"
                ];
            }
            $chat->shoppr_id=$user->id;
            $chat->save();
        DB::commit();

        $user->is_available=false;
        $user->save();

        $chat->customer->notify(new FCMNotification('Shoppr Assigned', 'A ShopR has been assigned to you. Please upload your list', array_merge(['message'=>'A ShopR has been assigned to you. Please upload your list', 'title'=>'ShopR Assigned'], ['type'=>'chat-assigned', 'chat_id'=>''.$chat->id]),'chat_screen'));

        return [
            'status'=>'success',
            'message'=>'Please start discussion with customer'
        ];

    }

    public function rejectChat(Request $request, $chat_id){
        $user=$request->user;

        if(!$user->isactive)
            return [
                'status'=>'failed',
                'message'=>'You account has been deactivated'
            ];

        if(!$user->is_available)
            return [
                'status'=>'failed',
                'message'=>'Please complete your last order before this'
            ];

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
            'message'=>'Order has been rejected'
        ];

    }


    public function availableChats(Request $request)
    {
        $user = $request->user;

        if ($user->isactive && $user->is_available) {

            $shopper_locations_obj=$user->locations;
            $shopper_locations=[];
            foreach($shopper_locations_obj as $l){
                $shopper_locations[]=$l->id;
            }

            $userchats = [];

            if(!empty($shopper_locations)){
                $chats = Chat::with(['customer' => function ($user) {
                    $user->select('id', 'name', 'image');
                }])
                    ->whereDoesntHave('rejectedby', function ($rejectedby) use ($user) {
                        $rejectedby->where('rejected_chats.shoppr_id', $user->id);
                    })
                    ->where('shoppr_id', 0) // unassigned chats
                    ->orderBy('id', 'desc')
                    ->whereIn('location_id', $shopper_locations)
                    ->where('is_terminated', false)
                    ->get();


                foreach ($chats as $userchat) {

                    $distance = distance($userchat->lat, $userchat->lang, $user->lat, $user->lang);

                    $userchats[] = [
                        'id' => $userchat->id,
                        'name' => $userchat->customer->name,
                        'image' => $userchat->customer->image,
                        'distance' => round($distance, 2),
                        'date' => date('d M', strtotime($userchat->getRawOriginal('created_at')))
                    ];
                }
            }


        }
        else
        {
            $userchats=[];
        }

        $type=Checkin::where('shoppr_id', $user->id)
            ->orderBy('id', 'desc')
            ->first();

        $type=$type->type??'checkout';

        return [
            'status'=>'success',
            'message'=>'',
            'data'=>compact('userchats','type')
        ];
    }


    public function terminateChat(Request $request, $id){

        $user=$request->user;

        if(!$user->isactive)
            return [
                'status'=>'failed',
                'message'=>'You account has been deactivated'
            ];

        $chat=Chat::with('customer')
            ->where('shoppr_id', $user->id)->findOrFail($id);

        $chat->is_terminated=true;
        $chat->save();

        $user->is_available=true;
        $user->save();

        $message=ChatMessage::create([
            'chat_id'=>$chat->id,
            'message'=>'The chat/ order with the shopper has been terminated.
You may again raise a new order with another shopper.',
            'type'=>'text',
            'price'=>0,
            'quantity'=>0,
            'direction'=>1,
        ]);


        $chat->customer->notify(new FCMNotification('New Message from ShopR', 'The chat/ order with the shopper has been terminated.
You may again raise a new order with another shopper.', array_merge(['title'=>'New Message from ShopR','message'=>'The chat/ order with the shopper has been terminated.
You may again raise a new order with another shopper.'], ['type'=>'chat', 'chat_id'=>''.$message->chat_id]),'chat_screen'));

        return [
            'status'=>'success',
            'message'=>'Chat has been terminated'
        ];

    }




}
