<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index(Request $request){
        $user=$request->user;

        $ordersobj=Order::with(['reviews','customer'=>function($customer){
                $customer->select('id','name','image');
            }])
            ->where('ratings','>', 0)
        ->where('shoppr_id', $user->id)
        ->where('status', 'Delivered')
        //->exists('reviews')
        ->orderBy('id', 'desc')
        ->get();

        $avgreviewsobj=Order::where('status', 'Delivered')
            ->where('ratings','>', 0)
            ->select(DB::raw('count(*) as count'), DB::raw('avg(ratings) as rating'))->first();

        $avgrating=round($avgreviewsobj['rating']??0, 2);
        $totalreviews=$avgreviewsobj['count']??0;

        $reviews=[];
        foreach ($ordersobj as $order){
            if(!empty($order->review->toArray())){
                $reviews[]=[
                    'reviews'=>$order->review[0]->message??'',
                    'rating'=>$order->review[0]->quantity??0,
                    'name'=>$order->customer->name,
                    'image'=>$order->customer->image,
                ];
            }

        }

        return [
            'status'=>'success',
            'data'=>compact('avgrating','totalreviews','reviews')
        ];
    }
}
