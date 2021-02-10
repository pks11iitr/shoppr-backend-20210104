<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function state(Request $request){

        $request->validate([
            'pan_card'=>'required|image',
            'pan_card'=>'required|image',
            'pan_card'=>'required|image'
        ]);
        $user=$request->user;

        $states=State::with('cities')->orderBy('id','desc')->get();

        return [
            'status'=>'success',
            'message'=>'location updated',
            'data'=>compact('states')
        ];

    }
}
