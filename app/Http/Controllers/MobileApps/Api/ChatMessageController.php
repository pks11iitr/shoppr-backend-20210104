<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{

    public function chatDetails(Request $request, $chat_id){

        $chats=ChatMessage::where('chat_id', $chat_id)
            ->orderBy('id', 'asc')
            ->get();

        return [

            'status'=>'success',
            'message'=>[],
            'data'=>compact('chats', 'chat_id')

        ];

    }

    public function send(Request $request, $chat_id){

        $request->validate([
            'type'=>'required|in:text,audio,image,product,payment,address-request,address,review',
            'message'=>'string',
            'file'=>'file'
        ]);

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
                    'message'=>'',
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

        }

        return [
            'status'=>'success',
            'message'=>'Message has been submitted',
            'data'=>[
                'message_id'=>$message->id
            ]
        ];
    }

    public function acceptProduct(Request $request, $message_id){

        $user=$request->user;
        $message=ChatMessage::whereHas('chat', function($chat)use($user){
            $chat->where('customer_id', $user->id);
        })->findOrFail($message_id);

        $message->status='accepted';
        $message->save();

        return [
            'status'=>'success',
            'message'=>'Product has been accepted'
        ];

    }

    public function rejectProduct(Request $request, $message_id){
        $user=$request->user;
        $message=ChatMessage::whereHas('chat', function($chat)use($user){
            $chat->where('customer_id', $user->id);
        })->findOrFail($message_id);

        $message->status='rejected';
        $message->save();

        return [
            'status'=>'success',
            'message'=>'Product has been rejected'
        ];
    }


    public function cancelProduct(Request $request, $message_id){
        $user=$request->user;
        $message=ChatMessage::whereHas('chat', function($chat)use($user){
            $chat->where('customer_id', $user->id);
        })->findOrFail($message_id);

        $message->status='cancelled';
        $message->save();

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
        $message=ChatMessage::whereHas('chat', function($chat)use($user){
            $chat->where('customer_id', $user->id);
        })->findOrFail($message_id);

        $message->quantity=$request->ratings;
        $message->status='accepted';
        $message->save();

        return [
            'status'=>'success',
            'message'=>'Ratings has been submitted'
        ];
    }

}
