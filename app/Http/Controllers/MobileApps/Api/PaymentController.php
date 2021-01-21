<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function initiatePayment(Request $request, $id){
        $user=$request->user;
        if(!$user)
            return [
                'status'=>'failed',
                'message'=>'Please login to continue'
            ];

        $chat=Chat::with(['messages'=>function($messages){
            $messages->where('status', 'accepted')
            ->where('type', 'product');
        }])
            ->whereHas('messages', function($messages){
                $messages->where('status', 'accepted')
                    ->where('type', 'product');
            })
            ->where('customer_id', $user->id)
            ->findOrFail($id);


        //$timeslot=TimeSlot::getNextDeliverySlot();

//        $items=ChatMessage::whereHas('chat', function($chat)use($user){
//            $chat->where('customer_id', $user->id);
//        })
//            ->where('chat_id', $id)
//            ->where('type', 'product')
//            ->where('status', 'accepted')
//            ->get();

//        if(!count($items))
//            return [
//                'status'=>'failed',
//                'message'=>'Please accept items to buy'
//            ];

        $total=0;
        foreach($chat->messages as $detail){
            $total=$total+$detail->price;
        }

        $service_charge=100;

        $grand_total=$total+$service_charge;

        if(!empty($request->coupon)){
            $coupon=Coupon::active()->where('code', $request->coupon)->first();
            if(!$coupon){
                return [
                    'status'=>'failed',
                    'message'=>'Invalid Coupon'
                ];
            }
            if($coupon && !$coupon->getUserEligibility($user)){
                return [
                    'status'=>'failed',
                    'message'=>'Coupon Has Been Expired'
                ];
            }

            //$order->applyCoupon($coupon);
        }

//        if($request->use_points==1) {
//            $result=$this->payUsingPoints($order);
//            if($result['status']=='success'){
//
//                event(new OrderConfirmed($order));
//
//                return [
//                    'status'=>'success',
//                    'message'=>'Congratulations! Your order at Hallobasket is successful',
//                    'data'=>[
//                        'payment_done'=>'yes',
//                        'ref_id'=>$order->refid,
//                        'order_id'=>$order->id
//                    ]
//                ];
//            }
//        }

//        if($request->use_balance==1) {
//            $result=$this->payUsingBalance($order);
//            if($result['status']=='success'){
//
//                event(new OrderConfirmed($order));
//
//                return [
//                    'status'=>'success',
//                    'message'=>'Congratulations! Your order at Hallobasket is successful',
//                    'data'=>[
//                        'payment_done'=>'yes',
//                        'ref_id'=>$order->refid,
//                        'order_id'=>$order->id
//                    ]
//                ];
//            }
//
//        }
        if($request->type=='cod'){
//            return [
//                'status'=>'failed',
//                'message'=>'Your Account Has Been Blocked'
//            ];
            $result=$this->initiateCODPayment($chat);
        }else{
            $result=$this->initiateGatewayPayment($chat);
        }


        return $result;

    }

    private function initiateCodPayment($order){
        $user=auth()->guard('customerapi')->user();
        if($user->status==2){
            return [
                'status'=>'failed',
                'message'=>'Your Account Has Been Blocked'
            ];
        }

        if ($order->use_points == true) {
            $walletpoints = Wallet::points($order->user_id);
            if ($walletpoints < $order->points_used) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'We apologize, Your order is not successful due to low cashback',
                    'errors' => [

                    ],
                ], 200);
            }
        }

        if ($order->use_balance == true) {
            $balance = Wallet::balance($order->user_id);
            if ($balance < $order->balance_used) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'We apologize, Your order is not successful due to low wallet balance',
                    'errors' => [

                    ],
                ], 200);
            }
        }

        $order->payment_mode='COD';
        $order->status='confirmed';
        $order->save();

        if($order->points_used > 0)
            Wallet::updatewallet($order->user_id, 'Paid For Order ID: '.$order->refid, 'DEBIT',$order->points_used, 'POINT', $order->id);

        if($order->balance_used > 0)
            Wallet::updatewallet($order->user_id, 'Paid For Order ID: '.$order->refid, 'DEBIT',$order->balance_used, 'CASH', $order->id);

        Order::deductInventory($order);

        event(new OrderConfirmed($order));

        Cart::where('user_id', $order->user_id)->delete();


        return [
            'status'=>'success',
            'message'=>'Congratulations! Your order at SuzoDailyNeeds is successful',
            'data'=>[
                'payment_done'=>'yes',
                'refid'=>$order->refid
            ],
        ];
    }


    private function payUsingBalance($order){

        $walletbalance=Wallet::balance($order->user_id);
        if($walletbalance<=0)
            return [
                'status'=>'failed',
                'remaining_amount'=>$order->total_cost
            ];

        if($walletbalance >= $order->total_cost+$order->delivery_charge-$order->coupon_discount-$order->points_used) {
            $order->payment_status='paid';
            $order->status='confirmed';
            $order->use_balance=true;
            $order->balance_used=$order->total_cost+$order->delivery_charge-$order->coupon_discount-$order->points_used;
            $order->payment_mode='online';
            $order->save();

            $order->changeDetailsStatus('confirmed');

            OrderStatus::create([
                'order_id'=>$order->id,
                'current_status'=>$order->status
            ]);

            if($order->points_used)
                Wallet::updatewallet($order->user_id, 'Paid For Order ID: '.$order->refid, 'DEBIT',$order->points_used, 'POINT', $order->id);

            Wallet::updatewallet($order->user_id, 'Paid For Order ID: '.$order->refid, 'DEBIT',$order->balance_used, 'CASH', $order->id);

            Order::deductInventory($order);

            Cart::where('user_id', $order->user_id)->delete();

            return [
                'status'=>'success',
            ];
        }else {
            if($walletbalance>0){
                $order->use_balance=true;
                $order->balance_used=$walletbalance;
                $order->payment_mode='online';
                $order->save();
            }
        }

        return [
            'status'=>'failed',
        ];
    }
}
