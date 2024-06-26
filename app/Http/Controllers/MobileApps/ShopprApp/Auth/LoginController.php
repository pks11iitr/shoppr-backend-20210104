<?php

namespace App\Http\Controllers\MobileApps\ShopprApp\Auth;

use App\Events\SendOtp;
use App\Models\Customer;
use App\Models\OTPModel;
use App\Models\Shoppr;
use App\Services\SMS\Msg91;
use App\Services\SMS\Nimbusit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function userId(Request $request, $type='password')
    {
        if(filter_var($request->user_id, FILTER_VALIDATE_EMAIL))
            return 'email';
        else
            return 'mobile';
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'user_id' => $this->userId($request)=='email'?'required|email|string|exists:shoppers,email':'required|digits:10|string|exists:shoppers,mobile',
            'password' => 'required|string',
        ], ['user_id.exists'=>'This account is not registered with us. Please signup to continue']);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($token=$this->attemptLogin($request)) {
            return $this->sendLoginResponse($this->getShopper($request), $token);
        }
        return [
            'status'=>'failed',
            'form_step'=>'',
            'token'=>'',
            'message'=>'Credentials are not correct'
        ];

    }


    protected function attemptLogin(Request $request)
    {
        return Auth::guard('shopperapi')->attempt(
            [$this->userId($request)=>$request->user_id, 'password'=>$request->password]
        );
    }

    protected function getShopper(Request $request){
        $customer=Shoppr::where($this->userId($request),$request->user_id)->first();
        $customer->notification_token=$request->notification_token;
        $customer->save();
        return $customer;
    }

    protected function sendLoginResponse($user, $token){
        if($user->status==0){
            $otp=OTPModel::createOTP('shopper', $user->id, 'login');
            $msg=str_replace('{{otp}}', $otp, config('sms-templates.login'));
            Nimbusit::send($user->mobile,$msg, env('OTP_TEMPLATE_ID'));
            return ['status'=>'success','form_step'=>$user->form_step, 'message'=>'otp verify', 'token'=>''];
        }
        else if($user->status==1)
            return ['status'=>'success','form_step'=>$user->form_step, 'message'=>'Login Successfull', 'token'=>$token];
        else
            return ['status'=>'failed','form_step'=>'', 'message'=>'This account has been blocked', 'token'=>''];
    }


    /**
     * Handle a login request to the application with otp.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function loginWithOtp(Request $request){
        $this->validateOTPLogin($request);

        $user=Shoppr::where('mobile', $request->mobile)->first();
        if(!$user)
            return ['status'=>'failed','form_step'=>'', 'message'=>'This account is not registered with us. Please signup to continue'];

        if(!in_array($user->status, [0,1]))
            return ['status'=>'failed','form_step'=>'', 'message'=>'This account has been blocked'];

        $otp=OTPModel::createOTP('shopper', $user->id, 'login');
        $msg=str_replace('{{otp}}', $otp, config('sms-templates.login'));
        event(new SendOtp($user->mobile, $msg));

        return ['status'=>'success','form_step'=>$user->form_step, 'message'=>'Please verify OTP to continue'];
    }


    protected function validateOTPLogin(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10|string|exists:shoppers,mobile',
        ], ['mobile.*'=>'Account is not registered. Please register to continue']);
    }

    public function gmailLogin(Request $request){

    }

    public function facebookLogin(Request $request){

    }

    public function logout(Request $request){
        $user=$request->user;
        $user->notification_token=null;
        $user->save();
        return [
            'status'=>'success'
        ];
    }

}
