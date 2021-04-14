<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Events\OrderConfirmed;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\Order;
use App\Models\Wallet;
use App\Services\Notification\FCMNotification;
use App\Services\Payment\RazorPayService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(RazorPayService $pay){
        $this->pay=$pay;
    }

    public function initiatePayment(Request $request, $order_id){
        $user=$request->user;

        $order=Order::with('customer')
            ->where('user_id', $user->id)
            ->where('status', 'Pending')
            ->findOrFail($order_id);

//        if(!empty($request->coupon)){
//            $coupon=Coupon::active()->where('code', $request->coupon)->first();
//            if(!$coupon){
//                return [
//                    'status'=>'failed',
//                    'message'=>'Invalid Coupon'
//                ];
//            }
//            if($coupon && !$coupon->getUserEligibility($user)){
//                return [
//                    'status'=>'failed',
//                    'message'=>'Coupon Has Been Expired'
//                ];
//            }
//
//            //$order->applyCoupon($coupon);
//        }


        if($request->use_balance==1) {
            $result=$this->payUsingBalance($order);
            if($result['status']=='success'){


                event(new OrderConfirmed($order));
                $this->sendTrackNotification($order);
                return [
                    'status'=>'success',
                    'message'=>'Congratulations! Your order at Hallobasket is successful',
                    'data'=>[
                        'payment_done'=>'yes',
                        'ref_id'=>$order->refid,
                        'order_id'=>$order->id
                    ]
                ];
            }
        }
        if($request->type=='cod'){
            $result=$this->initiateCODPayment($order);
        }else{
            $result=$this->initiateGatewayPayment($order);
        }

        return $result;

    }

    private function initiateGatewayPayment($order){
        $data=[
            "amount"=>$order->grandTotalForPayment()*100,
            "currency"=>"INR",
            "receipt"=>$order->refid,
        ];

        $response=$this->pay->generateorderid($data);

//        LogData::create([
//            'data'=>($response.' orderid:'.$order->id. ' '.json_encode($data)),
//            'type'=>'order'
//        ]);

        $responsearr=json_decode($response);
        //var_dump($responsearr);die;
        if(isset($responsearr->id)){
            $order->pg_order_id=$responsearr->id;
            $order->pg_order_response=$response;
            $order->save();
            return [
                'status'=>'success',
                'message'=>'success',
                'data'=>[
                    'payment_done'=>'no',
                    'razorpay_order_id'=> $order->pg_order_id,
                    'total'=>$order->grandTotalForPayment()*100,
                    'email'=>$user->email??'',
                    'mobile'=>$user->mobile??'',
                    'description'=>'Product Purchase at Shopr',
                    'name'=>$user->name??'',
                    'currency'=>'INR',
                    //'merchantid'=>$this->pay->merchantkey,
                ],
            ];
        }else{
            return [
                'status'=>'failed',
                'message'=>'Payment cannot be initiated',
                'data'=>[
                ],
            ];
        }
    }


    private function initiateCodPayment($order){
        $user=auth()->guard('customerapi')->user();
        if($user->status==2){
            return [
                'status'=>'failed',
                'message'=>'Your Account Has Been Blocked'
            ];
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
        $order->status='Confirmed';
        $order->save();

        ChatMessage::where('chat_id', $order->chat_id)
            ->where('order_id', null)
            ->update(['order_id'=>$order->id]);

        if($order->balance_used > 0)
            Wallet::updatewallet($order->user_id, 'Paid For Order ID: '.$order->refid, 'DEBIT',$order->balance_used, 'CASH', $order->id);


        event(new OrderConfirmed($order));
        $this->sendTrackNotification($order);
        return [
            'status'=>'success',
            'message'=>'Congratulations! Your order at Shopr is successful',
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
                'remaining_amount'=>$order->grandTotal()
            ];

        if($walletbalance >= $order->grandTotal()) {
            $order->payment_status='Paid';
            $order->status='Confirmed';
            $order->use_balance=true;
            $order->balance_used=$order->grandTotal();
            $order->payment_mode='Online';
            $order->save();

            ChatMessage::where('chat_id', $order->chat_id)
                ->where('order_id', null)
                ->update(['order_id'=>$order->id]);

//            OrderStatus::create([
//                'order_id'=>$order->id,
//                'current_status'=>$order->status
//            ]);

            Wallet::updatewallet($order->user_id, 'Paid For Order ID: '.$order->refid, 'DEBIT',$order->balance_used, 'CASH', $order->id);

            //payment done chat
            $message=ChatMessage::create([
                'chat_id'=>$order->chat_id,
                'message'=>'Payment Received',
                'type'=>'paid',
                'order_id'=>$order->id
            ]);

            $order->shoppr->notify(new FCMNotification('Payment Done', 'Payment of Rs.'.($order->total+$order->service_charge).'has been completed for Order ID:'.$order->refid, ['title'=>'Payment Done', 'message'=>'Payment of Rs.'.($order->total+$order->service_charge).'has been completed for Order ID:'.$order->refid, 'type'=>'chat', 'chat_id'=>''.$order->chat_id], 'chat_screen'));

            return [
                'status'=>'success',
            ];
        }else if($walletbalance>0){
                $order->use_balance=true;
                $order->balance_used=$walletbalance;
                $order->payment_mode='Online';
                $order->save();
        }

        return [
            'status'=>'failed',
        ];
    }


    public function verifyPayment(Request $request){

        $request->validate([
            'razorpay_order_id'=>'required',
            'razorpay_signature'=>'required',
            'razorpay_payment_id'=>'required'

        ]);


//        LogData::create([
//            'data'=>(json_encode($request->all())??'No Payment Verify Data Found'),
//            'type'=>'verify'
//        ]);

        $order=Order::with('details')->where('pg_order_id', $request->razorpay_order_id)->first();

        if(!$order || $order->status!='Pending')
            return [
                'status'=>'failed',
                'message'=>'Invalid Operation Performed'
            ];

        $paymentresult=$this->pay->verifypayment($request->all());
        if($paymentresult) {
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
            $order->status = 'confirmed';
            $order->pg_payment_id = $request->razorpay_payment_id;
            $order->pg_payment_response = $request->razorpay_signature;
            $order->payment_status = 'Paid';
            $order->payment_mode = 'Online';
            $order->save();

            ChatMessage::where('chat_id', $order->chat_id)
                ->where('order_id', null)
                ->update(['order_id'=>$order->id]);

//            OrderStatus::create([
//                'order_id'=>$order->id,
//                'current_status'=>$order->status
//            ]);

            if($order->balance_used > 0)
                Wallet::updatewallet($order->user_id, 'Paid For Order ID: '.$order->refid, 'DEBIT',$order->balance_used, 'CASH', $order->id);

            //event(new OrderSuccessfull($order));
            event(new OrderConfirmed($order));
            $this->sendTrackNotification($order);

            //payment done chat
            $message=ChatMessage::create([
                'chat_id'=>$order->chat_id,
                'message'=>'Payment Received',
                'type'=>'paid',
                'order_id'=>$order->id
            ]);

            $order->shoppr->notify(new FCMNotification('Payment Done', 'Payment of Rs.'.($order->total+$order->service_charge).'has been completed for order id: '.$order->refid, array_merge(['title'=>'Payment Done', 'message'=>'Payment of Rs.'.($order->total+$order->service_charge).'has been completed for order id: '.$order->refid], ['type'=>'chat', 'chat_id'=>''.$order->chat_id]),'chat_screen'));

            return [
                'status'=>'success',
                'message'=> 'Congratulations! Your order at Shopr is successful',
                'data'=>[
                    'ref_id'=>$order->refid,
                    'order_id'=>$order->id,
                    'refid'=>$order->refid,
                ]
            ];
        }else{
            return [
                'status'=>'failed',
                'message'=>'We apologize, Your payment cannot be verified',
                'data'=>[

                ],
            ];
        }
    }

    private function sendTrackNotification($order){

        $message=ChatMessage::create([
            'message'=>'Track Your Order',
            'type'=>'track',
            'chat_id'=>$order->chat_id,
            'order_id'=>$order->id
        ]);

        $order->customer->notify(new FCMNotification('Track location', 'Track our delivery boy location', ['title'=>'Track Location', 'message'=>'Track our delivery boy location', 'type'=>'chat', 'chat_id'=>''.$message->chat_id],'chat_screen'));
    }
}
