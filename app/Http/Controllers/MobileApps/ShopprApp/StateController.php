<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function state(Request $request){

  $states=State::with('cities')->orderBy('id','desc')->get();

        return [
            'status'=>'success',
            'message'=>'location updated',
            'data'=>compact('states')
        ];

    }
}
