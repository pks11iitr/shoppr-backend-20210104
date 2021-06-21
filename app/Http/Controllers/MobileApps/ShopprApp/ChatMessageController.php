<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Services\Notification\CustomerFCMNotification;
use App\Services\Notification\FCMNotification;
use Illuminate\Http\Request;

class ChatMessageController extends Controller
{
    public function send(Request $request, $chat_id){

        $request->validate([
            'type'=>'required|in:text,audio,image,product,payment,address-request,address,review,add-money,payment,discount',
            'message'=>'string',
            'file'=>'file'
        ]);

        $user=$request->user;

        if(!$user->isactive)
            return [
                'status'=>'failed',
                'message'=>'You account has been deactivated'
            ];

        $chat=Chat::with('customer')
            ->where('shoppr_id', $user->id)
            ->where('id', $chat_id)
            ->firstOrFail();

        if($chat->is_terminated){
            return [
                'status'=>'Failed',
                'message'=>'This chat has been terminated. No more communication can be done on this order'
            ];
        }

        switch($request->type){

            case 'text':
                $message=ChatMessage::create([
                    'chat_id'=>$chat_id,
                    'message'=>$request->message,
                    'type'=>'text',
                    'direction'=>1,
                ]);

                break;

            case 'audio':
                $message=ChatMessage::create([
                    'chat_id'=>$chat_id,
                    'message'=>'',
                    'type'=>'audio',
                    'direction'=>1,
                ]);
                $message->saveFile($request->file, 'chats');
                break;

            case 'image':
                $message=ChatMessage::create([
                    'chat_id'=>$chat_id,
                    'message'=>'',
                    'type'=>'image',
                    'direction'=>1,
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
                    'direction'=>1,
                ]);
                $message->saveFile($request->file, 'chats');
                break;
            case 'payment':
                $message=ChatMessage::create([
                    'chat_id'=>$chat_id,
                    'message'=>'Pay Now',
                    'type'=>'payment',
                    //'price'=>$request->price,
                    'quantity'=>0,
                    'direction'=>1,
                ]);
                //$message->saveFile($request->file, 'chats');
                break;
            case 'add-money':
                $message=ChatMessage::create([
                    'chat_id'=>$chat_id,
                    'message'=>'Please Add Rs.'.$request->message.' to your wallet',
                    'type'=>'add-money',
                    //'price'=>$request->price,
                    'quantity'=>0,
                    'direction'=>1,
                ]);
                //$message->saveFile($request->file, 'chats');
                break;
            case 'discount':
                $message=ChatMessage::create([
                    'chat_id'=>$chat_id,
                    'message'=>'You have received discount of Rs.'.$request->amount.' on this order',
                    'type'=>'discount',
                    'price'=>$request->amount,
                    //'quantity'=>0,
                    'direction'=>1,
                ]);
        }

        //send notification
        $message->refresh();

        $displaymessage=($message->type=='text')?$message->message:(in_array($message->type, ['audio', 'image', 'product'])?('['.$message->type.']'):($message->type=='add-money'?'Add Money To Wallet':($message->type=='payment'?'Please make payment for your order':'New Message')));

        $chat->customer->notify(new FCMNotification('New Message from ShopR', $displaymessage??'', array_merge(['title'=>'New Message from ShopR','message'=>$displaymessage], ['type'=>'chat', 'chat_id'=>''.$message->chat_id]),'chat_screen'));

        return [
            'status'=>'success',
            'message'=>'Message has been submitted',
            'data'=>[
                'message_id'=>$message->id
            ]
        ];
    }

    public function chatDetails(Request $request, $chat_id){

        $chat=Chat::with(['customer'=>function($customer){
            $customer->select('id','name','image');
        }])->findOrFail($chat_id);

        $customer=$chat->customer;


        $chats=ChatMessage::where('chat_id', $chat_id)
            ->orderBy('id', 'asc')
            ->get();

        ChatMessage::where('chat_id', $chat_id)
            ->where('seen_at', null)
            ->where('direction', 0)
            ->update(['seen_at'=>date('Y-m-d H:i:s')]);



        return [

            'status'=>'success',
            'message'=>'',
            'data'=>compact('chats', 'chat_id', 'customer')

        ];

    }

    public function delete(Request $request, $message_id){
        $user=$request->user;

        $message=ChatMessage::whereHas('chat', function($chat) use($user){
            $chat->where('shoppr_id', $user->id);
        })
            ->where('type', 'product')
            ->findOrFail($message_id);

        if(!(empty($message->order_id) || $message->order->status=='pending'))
            return [
                'status'=>'failed',
                'message'=>'Product cannot be deleted after order confirmation'
            ];

        $message->delete();

        return [
            'status'=>'success',
            'message'=>'Product has been deleted'
        ];
    }


}
