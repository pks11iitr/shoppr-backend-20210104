<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\State;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function uploaddocument(Request $request)
    {

        $request->validate([
            'pan_card' => 'required|image',
            'front_aadhaar_card' => 'required|image',
            'back_aadhaar_card' => 'required|image',
            'front_dl_no' => 'required|image',
            'back_dl_no' => 'required|image'
        ]);
        $user = $request->user;
        if ($request->pan_card) {
            $user->savePanCard($request->pan_card, 'shopper');
        }
        if ($request->front_aadhaar_card) {
            $user->saveFrontAadhaarCard($request->front_aadhaar_card, 'shopper');
        }
        if ($request->back_aadhaar_card) {
            $user->saveBackAadhaarCard($request->back_aadhaar_card, 'shopper');
        }
        if ($request->front_dl_no) {
            $user->saveFrontDlNo($request->front_dl_no, 'shopper');
        }

        if ($request->back_dl_no) {
            $user->saveBackDlNo($request->back_dl_no, 'shopper');
        }
        $user->form_step = 2;
        if ($user->save()) {
            return [
                'status' => 'success',
                'form_step' => $user->form_step,
                'message' => 'updated',
            ];
        } else {
            return [
                'status' => 'failed',
                'form_step' => '1',
                'message' => 'updated',
            ];
        }

    }
    public function bankdetails(Request $request){
        $request->validate([
            'account_no' => 'required|string',
            'ifsc_code' => 'required|string',
            'account_holder' => 'required|string',
            'bank_name' => 'required|string'
        ]);

        $user = $request->user;
        if($user->update($request->only('account_no','ifsc_code','account_holder','bank_name'))){
            $user->form_step=3;
            $user->save();
            return [
                'status' => 'success',
                'form_step' => $user->form_step,
                'message' => 'updated',
            ];
        }else{
            return [
                'status' => 'failed',
                'form_step' => '2',
                'message' => 'updated',
            ];
        }

    }

    public function getProfileCompletionStatus(Request $request){

        return [
            'status'=>'success',
            'form_step'=>$request->user->form_step??0
        ];

    }


    public function checkin(Request $request){

        $user=$request->user;

        $type=Checkin::where('shoppr_id', $user->id)
            ->orderBy('id', 'desc')
            ->first();

        if(($type->type??'')=='checkin')
            return [
                'status'=>'failed',
                'message'=>'Already checked in'
            ];

        Checkin::create([
            'shoppr_id'=>$user->id,
            'lat'=>$request->lat,
            'lang'=>$request->lang,
            'type'=>'checkin',
            'address'=>$request->address
        ]);

        return [
            'status'=>'success',
        ];

    }

    public function checkout(Request $request){

        $user=$request->user;

        $type=Checkin::where('shoppr_id', $user->id)
            ->orderBy('id', 'desc')
            ->first();

        if(($type->type??'')=='checkout')
            return [
                'status'=>'failed',
                'message'=>'Already checked out'
            ];

        Checkin::create([
            'shoppr_id'=>$user->id,
            'lat'=>$request->lat,
            'lang'=>$request->lang,
            'type'=>'checkout',
            'address'=>$request->address
        ]);

        return [
            'status'=>'success',
        ];

    }

    public function attendencelist(Request $request)
    {

        $from_date=$request->from_date??date('Y-m-01');
        $to_date=$request->to_date??date('Y-m-t');

        $user = $request->user;

        $attendencesobj = Checkin::where('shoppr_id', $user->id)
            ->orderBy('id', 'desc')
            ->where('created_at', '>=', $from_date.' 00:00:00')
            ->where('created_at', '<=', $to_date.' 23:59:59')
            ->get();

        $attendences1 = [];
        foreach ($attendencesobj as $at) {
            if ($at->type == 'checkin') {
                $attendences1[date('Y-m-d', strtotime($at->created_at))]['checkin'] = date('h:ia', strtotime($at->created_at));
                $attendences1[date('Y-m-d', strtotime($at->created_at))]['checkin-address'] = $at->address;
            } else {
                $attendences1[date('Y-m-d', strtotime($at->created_at))]['checkout'] = date('h:ia', strtotime($at->created_at));
                $attendences1[date('Y-m-d', strtotime($at->created_at))]['checkout-address'] = $at->address;
            }
        }
        $attendences = [];
        foreach ($attendences1 as $key => $val) {
            $attendences[] = [
                'date' => $key,
                'checkin' => $val['checkin'] ?? '-',
                'checkin-address' => $val['checkin-address'] ?? '-',
                'checkout' => $val['checkout'] ?? '-',
                'checkout-address' => $val['checkout-address'] ?? '-',
            ];
        }

        return [
            'status' => 'success',
            'data' => compact('attendences')
        ];

    }


}
