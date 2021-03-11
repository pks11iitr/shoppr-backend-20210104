<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Checkin;
use App\Models\Notification;
use App\Models\State;
use App\Models\WorkLocations;
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
            'back_dl_no' => 'required|image',
            'bike_front'=>'required|image',
            'bike_back'=>'required|image'
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

        if($request->bike_front){
            $user->saveBikeFront($request->bike_front, 'shopper');
        }

        if($request->bike_back){
            $user->saveBikeBack($request->bike_back, 'shopper');
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

        if($request->user){
            $type=Checkin::where('shoppr_id', $request->user->id)
                ->orderBy('id', 'desc')
                ->first();

            $type=$type->type??'checkout';
        }else{
            $type='checkout';
        }

        return [
            'status'=>'success',
            'form_step'=>$request->user->form_step??0,
            'type'=>$type
        ];

    }

    public function getProfile(Request $request){

        $user=$request->user;

        return [
            'status'=>'success',
            'data'=>compact('user')
        ];


    }

    public function checkinstatus(Request $request){

        $user=$request->user;

        $type=Checkin::where('shoppr_id', $user->id)
            ->orderBy('id', 'desc')
            ->first();

        $type=$type->type??'checkout';

        $notifications=Notification::where('user_type', 'SHOPPR')
            ->where('user_id', $user->id)
            ->where('seen_at', null)
            ->count();

        $orders=Chat::where('shoppr_id', 0)->count();

        return [
            'status'=>'success',
            'type'=>$type,
            'notifications'=>$notifications,
            'orders'=>$orders
        ];
    }

    public function updateworklocation(Request $request){
        $request->validate([
            'locations'=>'array|required',
            'work_type'=>'required|in:0,1'
        ]);

        $user=$request->user;

        $user->locations()->sync($request->locations);
        $user->work_type=$request->work_type;
        $user->form_step=5;
        $user->save();

        return [
            'status'=>'success',
            'message'=>'Locations have been updated',
            'form_step' => '5',
        ];

    }

    public function updatePersonalDetails(Request $request){
        $request->validate([
            'permanent_address'=>'required|string',
            'permanent_city'=>'required|integer',
            'permanent_state'=>'required|integer',
            'permanent_pin'=>'required|integer',
            'secondary_mobile'=>'required|string',
            'emergency_mobile'=>'required|string',
        ]);

        $user=$request->user;

        $user->update($request->only('permanent_address','permanent_city','permanent_pin','secondary_mobile','emergency_mobile', 'permanent_state'));
        $user->form_step=4;
        $user->save();

        return [
            'status'=>'success',
            'message'=>'Personal details have been updated',
            'form_step' => '4',
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

    public function getDocuments(Request $request){
        $user=$request->user;

        $user=$user->only('pan_card','front_aadhaar_card', 'front_dl_no', 'back_aadhaar_card', 'back_dl_no', 'bike_front', 'bike_back');

        return [
            'status'=>'success',
            'data'=>compact('user')
        ];
    }

    public function updateDocument(Request $request){
        $user=$request->user;

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

        if($request->bike_front){
            $user->saveBikeFront($request->bike_front, 'shopper');
        }

        if($request->bike_back){
            $user->saveBikeBack($request->bike_back, 'shopper');
        }

        return [
            'status'=>'success'
        ];

    }

    public function getWorkInfo(Request $request){
        $user=$request->user;

        $locations=$user->locations;

        $user=$user->only('work_type');

        $work_locations=WorkLocations::active()->select('id','name')->orderBy('name','asc')->get();

        return [
            'status'=>'success',
            'data'=>compact('user', 'locations','work_locations'),
        ];
    }


    public function getBankInfo(Request $request){
        $user=$request->user;

        $user=$user->only('account_no', 'ifsc_code','account_holder', 'bank_name');

        return [
            'status'=>'success',
            'data'=>compact('user')
        ];
    }


    public function getPersonalInfo(Request $request){
        $user=$request->user;

        $user=$user->only('permanent_address','permanent_city','permanent_pin','secondary_mobile','emergency_mobile', 'permanent_state');

        $states=State::with('cities')->orderBy('id','desc')->get();

        return [
            'status'=>'success',
            'data'=>compact('user', 'states')
        ];
    }




}
