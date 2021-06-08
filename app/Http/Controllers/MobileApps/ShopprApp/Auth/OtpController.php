<?php

namespace App\Http\Controllers\MobileApps\ShopprApp\Auth;

use App\Events\SendOtp;
use App\Models\Customer;
use App\Models\NotificationToken;
use App\Models\OTPModel;
use App\Models\Shoppr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{

    /**
     * Handle a login request to the application with otp.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function verify(Request $request){
        $request->validate([
            'type'=>'required|string|max:15',
            'mobile'=>'required|string|digits:10|exists:shoppers',
            'otp'=>'required|digits:6'
        ]);

        switch($request->type){
            case 'register': return $this->verifyRegister($request);
            case 'login': return $this->verifyLogin($request);
            case 'reset': return $this->verifyResetPassword($request);
        }

        return [
            'status'=>'failed',
            'message'=>'Request is not valid'
        ];
    }

    protected function verifyRegister(Request $request){
        $user=Shoppr::where('mobile', $request->mobile)->first();
        if($user->status==0){
            if(OTPModel::verifyOTP('shopper',$user->id,$request->type,$request->otp)){

                $user->notification_token=$request->notification_token;
                $user->status=1;
                $user->form_step=1;
                $user->save();

                if($request->notification_token){
                    Shoppr::where('notification_token', $request->notification_token)
                        ->where('id', '!=', $user->id)
                        ->update(['notification_token'=>null]);

                    NotificationToken::updateOrCreate(['notification_token'=>$request->notification_token, 'entity_type'=>'App\Models\Shoppr'], ['entity_id'=> $user->id]);
                }

                return [
                    'status'=>'success',
                    'form_step'=>$user->form_step??0,
                    'message'=>'OTP has been verified successfully',
                    'token'=>Auth::guard('shopperapi')->fromUser($user),
                    'user_id'=>'Shoppr-'.$user->id,
                    'sendbird_token'=>$user->sendbird_token
                ];
            }

            return [
                'status'=>'failed',
                'form_step'=>'',
                'message'=>'OTP is not correct',
                'token'=>''
            ];

        }
        return [
            'status'=>'failed',
            'form_step'=>'',
            'message'=>'Request is not valid',
            'token'=>''
        ];
    }


    protected function verifyLogin(Request $request){
        $user=Shoppr::where('mobile', $request->mobile)->first();
        if(in_array($user->status, [0,1])){
            if(OTPModel::verifyOTP('shopper',$user->id,$request->type,$request->otp)){

                $user->notification_token=$request->notification_token;
                $user->status=1;
                $user->form_step=($user->form_step>0?$user->form_step:1);
                $user->save();

                if($request->notification_token){
                    Shoppr::where('notification_token', $request->notification_token)
                        ->where('id', '!=', $user->id)
                        ->update(['notification_token'=>null]);

                    NotificationToken::updateOrCreate(['notification_token'=>$request->notification_token, 'entity_type'=>'App\Models\Shoppr'], ['entity_id'=> $user->id]);
                }

                if(empty($user->sendbird_token)){
                    $sendbird=app('App\Services\SendBird\SendBird');
                    $response=$sendbird->createUser($user);

                    if(isset($response['user_id'])){
                        $user->sendbird_token=$response['access_token']??null;
                        $user->save();
                    }
                }

                return [
                    'status'=>'success',
                    'form_step'=>$user->form_step??0,
                    'message'=>'OTP has been verified successfully',
                    'token'=>Auth::guard('shopperapi')->fromUser($user),
                    'user_id'=>'Shoppr-'.$user->id,
                    'sendbird_token'=>$user->sendbird_token
                ];
            }

            return [
                'status'=>'failed',
                'form_step'=>'',
                'message'=>'OTP is not correct',
                'token'=>''
            ];

        }
        return [
            'status'=>'failed',
            'form_step'=>'',
            'message'=>'Account has been blocked',
            'token'=>''
        ];
    }


//    protected function verifyResetPassword(Request $request){
//        $user=Customer::where('mobile', $request->mobile)->first();
//        if(in_array($user->status, [0,1])){
//            if(OTPModel::verifyOTP('customer',$user->id,$request->type,$request->otp)){
//
//                $user->status=1;
//                $user->save();
//
//                return [
//                    'status'=>'success',
//                    'form_step'=>$user->form_step??0,
//                    'message'=>'OTP Has Been Verified',
//                    'token'=>Auth::guard('customerapi')->fromUser($user),
//                    'user_id'=>'Shoppr-'.$user->id,
//                    'sendbird_token'=>$user->sendbird_token
//                ];
//            }
//
//            return [
//                'status'=>'failed',
//                'form_step'=>'',
//                'message'=>'OTP is not correct',
//                'token'=>''
//            ];
//
//        }
//        return [
//            'status'=>'failed',
//            'form_step'=>'',
//            'message'=>'Account has been blocked',
//            'token'=>''
//        ];
//    }


    public function resend(Request $request){
        $request->validate([
            'type'=>'required|string|max:15',
            'mobile'=>'required|string|digits:10|exists:shoppers',
        ]);

        $user=Shoppr::where('mobile', $request->mobile)->first();
        if(in_array($user->status, [0,1])){
                $otp=OTPModel::createOTP('shopper', $user->id, $request->type);
                $msg=str_replace('{{otp}}', $otp, config('sms-templates.'.$request->type));
                event(new SendOtp($user->mobile, $msg));
                return [
                    'status'=>'success',
                    'message'=>'Please verify OTP to continue',
                ];
        }

        return [
            'status'=>'failed',
            'message'=>'Account has been blocked',
        ];

    }

}
