<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Shoppr;
use App\Services\Notification\FCMNotification;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{

    public function chatDetails(Request $request, $chat_id){

        $chat=Chat::findOrFail($chat_id);

        $chats=ChatMessage::where('chat_id', $chat_id)
            ->orderBy('id', 'asc')
            ->get();

        ChatMessage::where('chat_id', $chat_id)
                ->where('seen_at', null)
                ->where('direction', 1)
                ->update(['seen_at'=>date('Y-m-d H:i:s')]);

        $shoppr=Shoppr::select('name','image','id')
        ->find($chat->shoppr_id);

        return [

            'status'=>'success',
            'message'=>[],
            'data'=>compact('chats', 'chat_id', 'shoppr')

        ];

    }

    public function send(Request $request, $chat_id){

        $request->validate([
            'type'=>'required|in:text,audio,image,product,payment,address-request,address,review',
            'message'=>'string',
            'file'=>'file'
        ]);

        $user=$request->user;

        $chat=Chat::with('shoppr')
            ->where('customer_id', $user->id)
            ->where('id', $chat_id)
            ->firstOrFail();

        switch($request->type){

            case 'text':
                $message=ChatMessage::create([
                    'chat_id'=>$chat_id,
                    'message'=>$request->message,
                    'type'=>'text',
                    'direction'=>0,
                ]);
                break;

            case 'audio':
                $message=ChatMessage::create([
                    'chat_id'=>$chat_id,
                    'message'=>'',
                    'type'=>'audio',
                    'direction'=>0,
                ]);
                $message->saveFile($request->file, 'chats');
                break;

            case 'image':
                $message=ChatMessage::create([
                    'chat_id'=>$chat_id,
                    'message'=>'',
                    'type'=>'image',
                    'direction'=>0,
                ]);
                $message->saveFile($request->file, 'chats');
                break;
            case 'product':
                $message=ChatMessage::create([
                    'chat_id'=>$chat_id,
                    'message'=>$request->name,
                    'type'=>'product',
                    'price'=>$request->price,
                    'quantity'=>$request->quantity,
                    'direction'=>0,
                ]);
                $message->saveFile($request->file, 'chats');
                break;
            case 'rating':
                $message=ChatMessage::create([
                    'chat_id'=>$chat_id,
                    'message'=>'',
                    'type'=>'rating',
                    //'price'=>$request->price,
                    'quantity'=>0,
                    'direction'=>0,
                ]);
                //$message->saveFile($request->file, 'chats');
                break;
            case 'address':
                $message=ChatMessage::create([
                    'chat_id'=>$chat_id,
                    'message'=>$request->address,
                    'type'=>'address',
                    //'price'=>$request->price,
                    'quantity'=>0,
                    'direction'=>0,
                    'lat'=>$request->lat,
                    'lang'=>$request->lang
                ]);

                $request->user->lat=$request->lat;
                $request->user->lang=$request->lang;
                $request->user->save();
                //$message->saveFile($request->file, 'chats');
                break;

        }

        //send notification
        $message->refresh();

        $chat->shoppr->notify(new FCMNotification('New Message', $message->message??'', array_merge($message->only('message'), ['type'=>'chat', 'chat_id'=>''.$message->chat_id]), 'chat_screen'));

        return [
            'status'=>'success',
            'message'=>'Message has been submitted',
            'data'=>[
                'message_id'=>$message->id,
            ]
        ];
    }

    public function acceptProduct(Request $request, $message_id){

        $user=$request->user;
        $message=ChatMessage::with('chat')
        ->whereHas('chat', function($chat)use($user){
            $chat->where('customer_id', $user->id);
        })->findOrFail($message_id);

        $message->status='accepted';
        $message->save();

        $message->chat->shoppr->notify(new FCMNotification('Item Accepted', $message->message??'', array_merge(['message'=>'Item Accepted'], ['type'=>'chat', 'chat_id'=>''.$message->chat_id]), 'chat_screen'));

        return [
            'status'=>'success',
            'message'=>'Product has been accepted'
        ];

    }

    public function rejectProduct(Request $request, $message_id){
        $user=$request->user;
        $message=ChatMessage::with('chat')
        ->whereHas('chat', function($chat)use($user){
            $chat->where('customer_id', $user->id);
        })->findOrFail($message_id);

        $message->status='rejected';
        $message->save();

        $message->chat->shoppr->notify(new FCMNotification('Item Rejected', $message->message??'', array_merge(['message'=>'Item Rejected'], ['type'=>'chat', 'chat_id'=>''.$message->chat_id]),'chat_screen'));
        return [
            'status'=>'success',
            'message'=>'Product has been rejected'
        ];
    }


    public function cancelProduct(Request $request, $message_id){
        $user=$request->user;
        $message=ChatMessage::with(['chat','order'])
        ->whereHas('chat', function($chat)use($user){
            $chat->where('customer_id', $user->id);
        })->findOrFail($message_id);

        if($message->order)
            return [
                'status'=>'failed',
                'message'=>'Item Cannot Be Cancelled Now'
            ];
        $message->status='cancelled';
        $message->save();

        $message->chat->shoppr->notify(new FCMNotification('New Message', $message->message??'', array_merge($message->only('message'), ['type'=>'chat', 'chat_id'=>''.$message->chat_id]),'chat_screen'));

        return [
            'status'=>'success',
            'message'=>'Product has been cancelled'
        ];
    }

    public function rateService(Request $request, $message_id){

        $request->validate([
           'ratings'=>'integer|required|in:1,2,3,4,5'
        ]);

        $user=$request->user;
        $message=ChatMessage::with('order')->whereHas('chat', function($chat)use($user){
            $chat->where('customer_id', $user->id);
        })->findOrFail($message_id);


        $message->quantity=$request->ratings;
        $message->status='accepted';
        $message->save();

        $message->order->ratings=$request->ratings;
        $message->order->save();

        $message->chat->shoppr->notify(new FCMNotification('New Message', $message->message??'', array_merge($message->only('message'), ['type'=>'chat', 'chat_id'=>''.$message->chat_id]),'chat_screen'));

        return [
            'status'=>'success',
            'message'=>'Ratings has been submitted'
        ];
    }

    public function calculateTotal(Reqest $request, $chat_id){
        $request->validate([
            'ratings'=>'integer|required|in:1,2,3,4,5'
        ]);

        $user=$request->user;

        Chat::where('type', 'total')
            ->where('chat_id',$chat_id)
            ->delete();

        $items=ChatMessage::where('chat_id', $chat_id)
            ->where('shoppr_id', $user->id)
            ->where('type', 'product')
            ->where('status', 'accepted')
            ->get();
        if(!count($items))
            return [
                'status'=>'failed',
                'message'=>'No items in the cart'
            ];
        $total=0;
        foreach($items as $i){
            $total=$total+$i->price;
        }
        $service_charge=100;

        $grand_total=$total+$service_charge;

        $message=ChatMessage::create([
            'chat_id'=>$chat_id,
            'message'=>$total.','.$service_charge.','.$grand_total,
            'type'=>'total',
            'quantity'=>0,
            'direction'=>0,
            'price'=>$grand_total
        ]);

        return [
            'status'=>'success',
            'message'=>'Ratings has been submitted',
            'data'=>compact('message')
        ];
    }

}
