<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shoppr;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request){
        if(isset($request->search)){
            $orders=Order::where(function($orders) use ($request){

                $orders->where('refid', 'like', "%".$request->search."%")
                    ->orWhereHas('customer', function($customer)use( $request){
                        $customer->where('name', 'like', "%".$request->search."%")
                            ->orWhere('email', 'like', "%".$request->search."%")
                            ->orWhere('mobile', 'like', "%".$request->search."%");
                    });
            });

        }else{
            $orders =Order::where('id', '>=', 0);
        }
        if($request->ordertype)
            $orders=$orders->orderBy('created_at', $request->ordertype);

        if($request->status)
            $orders=$orders->where('status', $request->status);

        if(isset($request->fromdate))
            $orders = $orders->where('created_at', '>=', $request->fromdate.' 00:00:00');

        if(isset($request->todate))
            $orders = $orders->where('created_at', '<=', $request->todate.' 23:59:59');

        if($request->shoppr_id)
            $orders=$orders->where('shoppr_id', $request->shoppr_id);

        $orders =$orders->where('status', '!=', 'Pending')
            ->orderBy('id', 'desc')
            ->paginate(20);
        $riders = Shoppr::active()->get();

        return view('admin.order.view',['orders'=>$orders,'riders'=>$riders]);
    }

    public function details(Request $request,$id){
        $order = Order::with('details','deliveryaddress')->where('id',$id)->first();

        return view('admin.order.details',['order'=>$order]);
    }
}
