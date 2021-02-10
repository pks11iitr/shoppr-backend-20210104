<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\MerchantApplication;
use App\Models\Shoppr;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function register(Request $request){

        $user=$request->user;

        $application=MerchantApplication::where('customer_id', $user->id)
            ->first();
        if($application)
            return [
                'status'=>'failed',
                'message'=>'You have already applied for merchant'
            ];

        $request->validate([
            'store_name'=>'required|max:150',
            'store_type'=>'required|max:100',
            'image'=>'required',
            'about_store'=>'required',
            'mobile'=>'required|digits:10',
            'opening_time'=>'required',
            'address'=>'required',
            'lat'=>'required',
            'lang'=>'required'
        ]);

        $application=MerchantApplication::create(array_merge($request->only('store_name','store_type','about_store','opening_time','mobile', 'email','lat','lang','address'), ['customer_id'=>$user->id]));

        $application->saveImage($request->image,'store');

        return [
            'status'=>'success',
            'message'=>'Your application has ben submitted. We will contact you soon'
        ];
    }

    public function view(Request $request){

        $user=$request->user;

        $application=MerchantApplication::where('customer_id', $user->id)
            ->first();

        if(!$application)
            return [
                'status'=>'failed',
                'data'=>[]
            ];
        //var_dump($application->toArray());die;

        if($application->is_active==1){
            $message='Your application has been approved';
        }elseif($application->is_active==0){
            $message='We are processing your application';
        }else{
            $message='Your application has been rejected';
        }

        $application=$application->only('store_name','store_type','email','mobile','address','about_store','opening_time', 'image');



        return [
            'status'=>'success',
            'data'=>compact('application', 'message')
        ];

    }

}
