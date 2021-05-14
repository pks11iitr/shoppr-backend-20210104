<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
use App\Models\CurrentLocation;
use Illuminate\Http\Request;

class ShopperLocationController extends Controller
{
    public function update(Request $request){

        $user=$request->user;

        $user->lat=$request->lat;
        $user->lang=$request->lang;

        $user->save();

        CurrentLocation::created([
            'lat'=>$request->lat,
            'lang'=>$request->lang,
            'shoppr_id'=>$user->id
        ]);

        return [
            'status'=>'success',
            'message'=>'location updated'
        ];

    }
}
