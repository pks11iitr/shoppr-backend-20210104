<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Events\RechargeConfirmed;
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
            $history = Wallet::where('user_id', $user->id)
                ->where('iscomplete', true)
                ->where('amount_type', 'CASH')
                ->select('amount', 'created_at', 'description', 'refid', 'type')
                ->orderBy('id', 'desc')
                ->get()
                ->groupBy(function($date) {
                    return Carbon::parse($date->created_at)->format('l, M d, Y');
                });
          /*  $history = Wallet::where('user_id', $user->id)
                ->where('iscomplete', true)
                ->where('amount_type', 'CASH')
                ->select('amount', 'created_at', 'description', 'refid', 'type')
                ->orderBy('id', 'desc')->get();*/
            $balance = Wallet::balance($user->id);

        } else {
            $history = [];
            $balance = 0;
        }

        return [
            'status' => 'success',
            'data' => compact('history', 'balance')
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
            $wallet=Wallet::create(['refid'=>env('MACHINE_ID').time(), 'type'=>'Credit', 'amount_type'=>'CASH', 'amount'=>$request->amount, 'description'=>'Wallet Recharge','user_id'=>$user->id]);

            $response=$this->pay->generateorderid([
                "amount"=>$wallet->amount*100,
                "currency"=>"INR",
                "receipt"=>$wallet->refid.'',
            ]);
            $responsearr=json_decode($response);
            if(isset($responsearr->id)){
                $wallet->order_id=$responsearr->id;
                $wallet->order_id_response=$response;
                $wallet->save();
                return [
                    'status'=>'success',
                    'data'=>[
                        'id'=>$wallet->id,
                        'order_id'=>$wallet->order_id,
                        'amount'=>$wallet->amount*100
                    ]
                ];
            }else{
                return response()->json([
                    'status'=>'failed',
                    'message'=>'Payment cannot be initiated',
                    'data'=>[
                    ],
                ], 200);
            }
        }

        return response()->json([
            'status'=>'failed',
            'message'=>'logout',
            'data'=>[
            ],
        ], 200);

    }

    public function verifyRecharge(Request $request){
        $user=auth()->guard('customerapi')->user();
        if(!$user)
            return [
                'status'=>'failed',
                'message'=>'Please login to continue'
            ];
        $wallet=Wallet::where('order_id', $request->razorpay_order_id)->first();
        if(!$wallet){
            return [
                'status'=>'failed',
                'message'=>'No Record found'
            ];
        }
        $paymentresult=$this->pay->verifypayment($request->all());
        if($paymentresult){
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


}
