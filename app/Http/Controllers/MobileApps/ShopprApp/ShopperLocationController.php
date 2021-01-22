<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShopperLocationController extends Controller
{
    public function update(Request $request){

        $user=$request->user;

        $user->lat=$request->lat;
        $user->lang=$request->lang;

        $user->save();

        return [
            'status'=>'success',
            'message'=>'location updated'
        ];

    }
}
