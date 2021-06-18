<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Order;
use App\Models\Settings;
use PDF;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index(Request $request){

        $user=$request->user;

        $orders=Order::with('details')
            ->where('user_id', $user->id)
            ->where('status', '!=', 'Pending')
            ->orderBy('id', 'desc')
            ->select('id','refid', 'total', 'service_charge', 'created_at', 'status')
            ->get();

        return [
            'status'=>'success',
            'data'=>compact('orders')
        ];

    }

    public function initiateOrder(Request $request, $chat_id){
        $user=$request->user;

        $chat=Chat::with('customer','shoppr')
            ->where('customer_id', $user->id)
            ->findOrFail($chat_id);

        $items=ChatMessage::whereHas('chat', function($chat)use($user,$chat_id){
            $chat->where('customer_id', $user->id);
        })
            ->where('chat_id', $chat_id)
            ->where('type', 'product')
            ->where('status', 'accepted')
            ->where('order_id', null)
            ->get();

        $discount=ChatMessage::whereHas('chat', function($chat)use($user,$chat_id){
            $chat->where('customer_id', $user->id);
        })
            ->where('chat_id', $chat_id)
            ->where('type', 'discount')
            ->where('order_id', null)
            ->first();

        $discount_amount=$discount->price??0;

        $total=0;
        foreach($items as $i){
            $total=$total+$i->price;
        }

        $refid=env('MACHINE_ID').time();

        $settings=Settings::where('name', 'First Delivery Free')->orWhere('name', 'Free Delivery Dates')->get();
        $is_free_delivery=false;
        foreach($settings as $s){
            if($s->name=='First Delivery Free' && $s->value=='yes'){
                $past=Order::whereIn('status', ['Delivered','Confirmed'])
                    ->where('user_id', $user->id)->get();
                if(!count($past))
                    $is_free_delivery=true;
            }
            if($s=='Free Delivery Dates'){
                $dates=explode('***', $s->value);
                if($dates[0] >= date('Y-m-d') && $dates[1] <= date('Y-m-d')){
                    $is_free_delivery=true;
                }
            }
        }

        if($is_free_delivery){
            $service_charge=0;
        }else{
            $service_charge=Settings::where('name', 'Service Fee')->first();
            $service_charge=$service_charge->value??0;
        }

        $grand_total=$total+$service_charge;

        $order=Order::create([
            'user_id'=>$user->id,
            'shoppr_id'=>$chat->shoppr_id,
            'chat_id'=>$chat_id,
            'refid'=>$refid,
            'total'=>$total,
            'service_charge'=>$service_charge,
            'discount'=>$discount_amount
        ]);

        return [
            'status'=>'success',
            'message'=>'',
            'data'=>[
                'order_id'=>$order->id
            ]
        ];
    }



    public function details(Request $request, $order_id){
        $user=$request->user;

        $order=Order::with(['details', 'reviews'])
            ->where('user_id', $user->id)
            ->select('id', 'refid', 'total','service_charge', 'status', 'payment_status', 'balance_used', 'discount')
            ->findOrFail($order_id);

        if($order->status=='Delivered'){
            $show_invoice_link=1;
        }else
            $show_invoice_link=0;

        $payment_text=($order->payment_status=='Paid')?'Total Paid':'To Be Paid';


        return [
                'status'=>'success',
                'data'=>compact('order', 'show_invoice_link', 'payment_text')
            ];

    }


    public function downloadInvoice(Request $request, $order_refid){
        $orders = Order::with(['details','customer'])->where('refid', $order_refid)
        ->firstOrFail();
        // var_dump($orders);die();
        $pdf = PDF::loadView('admin.orders.newinvoice', compact('orders'))->setPaper('a4', 'portrait');
        return $pdf->download('invoice.pdf');
        //return view('admin.contenturl.newinvoice',['orders'=>$orders]);
    }
}
