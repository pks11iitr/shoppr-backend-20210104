<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request){

        $chats=Chat::with(['shoppr','customer'])->paginate(10);

        return view('admin.chats.index', compact('chats'));

    }


    public function chats(Request $request,$id){

        $chats=ChatMessage::with(['chat.customer','chat.shoppr'])->where('chat_id', $id)
            ->orderBy('id', 'asc')
            ->get();
        return view('admin.order.chats', compact('chats'));
    }
}
