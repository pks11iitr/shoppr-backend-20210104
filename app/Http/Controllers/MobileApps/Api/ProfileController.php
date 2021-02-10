<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Wallet;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(){
        $user=auth()->guard('customerapi')->user();

        if(!$user){
            return [
                'status'=>'failed',
                'message'=>'Please Login to Continue..',
            ];
        }
        $balance = Wallet::balance($user->id);
        $userdata=array(
            'id'=>$user->id,
            'name'=>$user->name,
            'email'=>$user->email,
            'mobile'=>$user->mobile,
            'image'=>$user->image,
            'balance'=>$balance??0,
        );

        return [
            'status'=>'success',
            'message'=>'success',
            'data'=>$userdata,
        ];
    }
    public function update(Request $request){
        $user=auth()->guard('customerapi')->user();
        if(!$user){
            return [
                'status'=>'failed',
                'message'=>'Please Login to Continue..',
            ];
        }
        $request->validate([
            'name'=>'required|string',
            'email'=>'string'
        ]);

        if($request->email && Customer::where('email', $request->email)->where('id', '!=', $user->id)->first())
            return [
                'status'=>'failed',
                'message'=>'Email already register with other user',
            ];

        $user->name=$request->name;
        $user->email=$request->email;
        if($request->image){
            $user->saveImage($request->image, 'customers');
        }
        if($user->save()){
            return [
                'status'=>'success',
                'message'=>'Profile Updated Successfully',
            ];
        }else{
            return [
                'status'=>'failed',
                'message'=>'Profile Not Update',
            ];
        }

    }

}
