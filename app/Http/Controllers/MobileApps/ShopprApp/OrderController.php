<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request){

        $user=$request->user;

        $orders=Order::with('details')
            ->where('shoppr_id', $user->id)
            ->where('status', '!=', 'Pending')
            ->orderBy('id', 'desc')
            ->select('id','refid', 'total', 'service_charge', 'created_at', 'status')
            ->paginate(10);

        return [
            'status'=>'success',
            'data'=>compact('orders')
        ];

    }

    public function details(Request $request, $order_id){
        $user=$request->user;

        $order=Order::with(['details'])
            ->where('shoppr_id', $user->id)
            ->select('id', 'refid', 'total','service_charge', 'status', 'payment_status', 'balance_used')
            ->findOrFail($order_id);

        return [
            'status'=>'success',
            'data'=>compact('order')
        ];

    }
}
