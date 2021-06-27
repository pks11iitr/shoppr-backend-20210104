<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Events\RechargeConfirmed;
use App\Models\LogData;
use App\Models\Wallet;
use App\Services\Payment\RazorPayService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WalletController extends Controller
{
    public function __construct(RazorPayService $pay){
        $this->pay=$pay;
    }
    public function index(Request $request)
    {
        $user = auth()->guard('customerapi')->user();
        if (!$user)
            return [
                'status' => 'failed',
                'message' => 'Please login to continue'
            ];
        if ($user) {
            $historyobj = Wallet::where('user_id', $user->id)
                ->where('iscomplete', true)
                ->where('amount_type', 'CASH')
                ->select('amount', 'created_at', 'description', 'refid', 'type')
                ->orderBy('id', 'desc')
                ->get();
          /*  $history = Wallet::where('user_id', $user->id)
                ->where('iscomplete', true)
                ->where('amount_type', 'CASH')
                ->select('amount', 'created_at', 'description', 'refid', 'type')
                ->orderBy('id', 'desc')->get();*/
            $history=[];
            foreach($historyobj as $h){

                if(!isset($history[date('D, M d, Y',strtotime($h->created_at))])){
                    $history[date('D, M d, Y',strtotime($h->created_at))]=[];
                }
                $history[date('D, M d, Y',strtotime($h->created_at))][]=$h;
            }

            $wallet_transactions=[];
            foreach($history as $date=>$date_transactions){

                $tlist=[];
                foreach($date_transactions as $t)
                    $tlist[]=$t;
                /*$tcdlist=[];
                $tcclist=[];
                foreach ($tlist as $tc)
                    if($tc->type=='Credit'){
                      $tcclist[]=$tc;
                    }else{
                        $tcdlist[]=$tc;
                    }*/

                $wallet_transactions[]=[
                    'date'=>$date,
                    'transactions'=>$tlist,
                   // 'transcredit'=>$tcclist,
                  //  'transdebit'=>$tcdlist
                ];

            }


            $balance = Wallet::balance($user->id);

        } else {
            $wallet_transactions = [];
            $balance = 0;
        }

        return [
            'status' => 'success',
            'data' => compact('wallet_transactions', 'balance')
        ];
    }
    public function userbalance(){
        $user = auth()->guard('customerapi')->user();
        if (!$user)
            return [
                'status' => 'failed',
                'message' => 'Please login to continue'
            ];
        $balance = Wallet::balance($user->id);
        if($balance){
            return [
                'status' => 'success',
                'message' => 'success',
                'data'=>$balance
            ];
        }else{
            return [
                'status' => 'failed',
                'message' => 'some error Found'
            ];
        }
    }

    public function addMoney(Request $request){
        $request->validate([
            'amount'=>'required|integer|min:1'
        ]);

        $user=auth()->guard('customerapi')->user();
        if(!$user)
            return [
                'status'=>'failed',
                'message'=>'Please login to continue'
            ];
        if($user){
            //delete all incomplete attempts
            Wallet::where('user_id', $user->id)->where('iscomplete', false)->delete();

            //start new attempt
            $wallet=Wallet::create(['refid'=>env('MACHINE_ID').time(), 'type'=>'Credit', 'amount_type'=>'CASH', 'amount'=>$request->amount, 'description'=>'Wallet Recharge','user_id'=>$user->id, 'chat_id'=>$request->chat_id]);

            $data=[
                "amount"=>$request->amount,
                //"currency"=>"INR",
                "refid"=>$wallet->refid,
                "product"=>"Wallet Recharge at Shopr",
                //"email"=>$order->customer->email,
                "email"=>'lnkt56@gmail.com',
                "name"=>$user->name
            ];

            $response=$this->pay->generateHash($data);

            if($response){
//            $order->pg_order_id=$responsearr->id;
//            $order->pg_order_response=$response;
//            $order->save();
                return [
                    'status'=>'success',
                    'message'=>'success',
                    'data'=>[
                        'payment_done'=>'no',
                        //'razorpay_order_id'=> $order->pg_order_id,
                        'total'=>$data['amount'],
                        'email'=>$data['email'],
                        'mobile'=>$user->mobile??'',
                        'product'=>$data['product'],
                        'name'=>$data['name'],
                        'refid'=>$data['refid'],
                        'hashdata'=>$response,
                        'order_id'=>$wallet->id
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

        return response()->json([
            'status'=>'failed',
            'message'=>'logout',
            'data'=>[
            ],
        ], 200);

    }

//    public function addMoney(Request $request){
//        $request->validate([
//            'amount'=>'required|integer|min:1'
//        ]);
//
//        $user=auth()->guard('customerapi')->user();
//        if(!$user)
//            return [
//                'status'=>'failed',
//                'message'=>'Please login to continue'
//            ];
//        if($user){
//            //delete all incomplete attempts
//            Wallet::where('user_id', $user->id)->where('iscomplete', false)->delete();
//
//            //start new attempt
//            $wallet=Wallet::create(['refid'=>env('MACHINE_ID').time(), 'type'=>'Credit', 'amount_type'=>'CASH', 'amount'=>$request->amount, 'description'=>'Wallet Recharge','user_id'=>$user->id, 'chat_id'=>$request->chat_id]);
//
//            $response=$this->pay->generateorderid([
//                "amount"=>$wallet->amount*100,
//                "currency"=>"INR",
//                "receipt"=>$wallet->refid.'',
//            ]);
//            $responsearr=json_decode($response);
//            if(isset($responsearr->id)){
//                $wallet->order_id=$responsearr->id;
//                $wallet->order_id_response=$response;
//                $wallet->save();
//                return [
//                    'status'=>'success',
//                    'data'=>[
//                        'id'=>$wallet->id,
//                        'order_id'=>$wallet->order_id,
//                        'amount'=>$wallet->amount*100,
//                        'email'=>$user->email??'',
//                        'mobile'=>$user->mobile??''
//                    ]
//                ];
//            }else{
//                return response()->json([
//                    'status'=>'failed',
//                    'message'=>'Payment cannot be initiated',
//                    'data'=>[
//                    ],
//                ], 200);
//            }
//        }
//
//        return response()->json([
//            'status'=>'failed',
//            'message'=>'logout',
//            'data'=>[
//            ],
//        ], 200);
//
//    }

    public function verifyRecharge(Request $request){

        $originalcontent=Request::createFromGlobals()->getContent();

        //$content=json_encode($content, true);
        $refid='';
        $status='';
        $hash='';
        $email='';
        $firstname='';
        $productinfo='';
        $amount='';
        $content=explode('&', $originalcontent);
        foreach($content as $c){
            $c1=explode('=', $c);
            if(isset($c1[0]) && $c1[0]=='txnid'){
                $refid=$c1[1]??'';
            }
            if(isset($c1[0]) && $c1[0]=='status'){
                $status=$c1[1]??'';
            }
            if(isset($c1[0]) && $c1[0]=='hash'){
                $hash=$c1[1]??'';
            }
            if(isset($c1[0]) && $c1[0]=='email'){
                $email=$c1[1]??'';
            }
            if(isset($c1[0]) && $c1[0]=='productinfo'){
                $productinfo=$c1[1]??'';
            }
            if(isset($c1[0]) && $c1[0]=='firstname'){
                $firstname=$c1[1]??'';
            }
            if(isset($c1[0]) && $c1[0]=='amount'){
                $amount=$c1[1]??'';
            }
        }

        if($status!='success'){
            return [
                'status'=>'failed',
                'message'=>'Payment Failed'
            ];
        }

        LogData::create([
            'data'=>$originalcontent,
            'type'=>'verify'
        ]);

        $wallet=Wallet::where('refid', $refid)->first();
        if(!$wallet){
            return [
                'status'=>'failed',
                'message'=>'No Record found'
            ];
        }

        $data=[
            "amount"=>$amount,
            //"currency"=>"INR",
            "refid"=>$refid,
            "product"=>urldecode($productinfo),
            "email"=>urldecode($email),
            "name"=>urldecode($firstname),
            "status"=>$status
        ];

        LogData::create([
            'data'=>json_encode($data),
            'type'=>'verify'
        ]);

        $paymentresult=$this->pay->verifyhash($data);
        if(strtolower($paymentresult)==strtolower($hash)){
            $wallet->payment_id=$request->razorpay_payment_id;
            $wallet->payment_id_response=$request->razorpay_signature;
            $wallet->iscomplete=true;
            $wallet->save();

            event(new RechargeConfirmed($wallet));

            return response()->json([
                'status'=>'success',
                'message'=>'Payment is successfull',
                'errors'=>[

                ],
            ], 200);
        }else{
            return response()->json([
                'status'=>'failed',
                'message'=>'Payment is not successfull',
                'errors'=>[

                ],
            ], 200);
        }
    }

//    public function verifyRecharge(Request $request){
//        $user=auth()->guard('customerapi')->user();
//        if(!$user)
//            return [
//                'status'=>'failed',
//                'message'=>'Please login to continue'
//            ];
//        $wallet=Wallet::where('order_id', $request->razorpay_order_id)->first();
//        if(!$wallet){
//            return [
//                'status'=>'failed',
//                'message'=>'No Record found'
//            ];
//        }
//        $paymentresult=$this->pay->verifypayment($request->all());
//        if($paymentresult){
//            $wallet->payment_id=$request->razorpay_payment_id;
//            $wallet->payment_id_response=$request->razorpay_signature;
//            $wallet->iscomplete=true;
//            $wallet->save();
//
//            event(new RechargeConfirmed($wallet));
//
//            return response()->json([
//                'status'=>'success',
//                'message'=>'Payment is successfull',
//                'errors'=>[
//
//                ],
//            ], 200);
//        }else{
//            return response()->json([
//                'status'=>'failed',
//                'message'=>'Payment is not successfull',
//                'errors'=>[
//
//                ],
//            ], 200);
//        }
//    }


}
