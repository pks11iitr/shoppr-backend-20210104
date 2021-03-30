<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Exports\ChatDetailExport;
use App\Exports\ChatExport;
use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Order;
use App\Models\Shoppr;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ChatController extends Controller
{
    public function index(Request $request){

        $chats=chat::with(['shoppr','customer']);

        if(isset($request->search)){
            $chats=$chats->where(function($chat) use ($request){

                $chat->whereHas('customer', function($customer)use( $request){
                        $customer->where('name', 'like', "%".$request->search."%")
                            ->orWhere('email', 'like', "%".$request->search."%")
                            ->orWhere('mobile', 'like', "%".$request->search."%");
                    });
            });

        }

        if($request->ordertype)
            $chats=$chats->orderBy('created_at', $request->ordertype);


        if(isset($request->fromdate))
            $chats = $chats->where('created_at', '>=', $request->fromdate.' 00:00:00');

        if(isset($request->todate))
            $chats = $chats->where('created_at', '<=', $request->todate.' 23:59:59');

        if($request->shoppr_id)
            $chats=$chats->where('shoppr_id', $request->shoppr_id);

        if($request->type=='export'){
            $chats=$chats->get();
            return Excel::download(new ChatExport($chats), 'chats.xlsx');
        }

        $chats=$chats->paginate(20);
        $riders = Shoppr::active()->get();

        return view('admin.chat.view', compact('chats','riders'));

    }


    public function chats(Request $request,$id){

        $chats=ChatMessage::with(['chat.customer','chat.shoppr'])->where('chat_id', $id)
            ->orderBy('id', 'asc')
            ->get();
        if($request->type=='export')
            return Excel::download(new ChatDetailExport($chats), 'chats-details.xlsx');

        return view('admin.chat.chats', compact('chats'));
    }
}
