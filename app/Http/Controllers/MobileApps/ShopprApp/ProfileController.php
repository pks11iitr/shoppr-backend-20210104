<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function uploaddocument(Request $request)
    {

        $request->validate([
            'pan_card' => 'required|image',
            'aadhaar_card' => 'required|image',
            'dl_no' => 'required|image'
        ]);
        $user = $request->user;
        if ($request->pan_card) {
            $user->savePanCard($request->pan_card, 'shopper');
        }
        if ($request->aadhaar_card) {
            $user->saveAadhaarCard($request->aadhaar_card, 'shopper');
        }
        if ($request->dl_no) {
            $user->saveDlNo($request->dl_no, 'shopper');
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
}
