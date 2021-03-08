<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\WorkLocations;
use Illuminate\Http\Request;

class AvailableLocationController extends Controller
{
    public function locations(Request $request){
        $locations=WorkLocations::active()->select('id','name')->get();
        return [
            'status'=>'success',
            'data'=>compact('locations')
        ];

    }

    public function checkServiceAvailability(Request $request){
        $location=$request->location;
        $location=WorkLocations::where('name', $location)->first();
        if(!$location){
            return [
                'status'=>'failed',
                'message'=>'Location is not serviceble'
            ];
        }

        return [
            'status'=>'success',
            'message'=>'Location is serviceble'
        ];
    }
}
