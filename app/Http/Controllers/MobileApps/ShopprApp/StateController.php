<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\WorkLocations;
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

    public function worklocations(Request $request){
        $locationsobj=WorkLocations::active()->with('city')->select('id','name', 'city_id')->orderBy('name','asc')->get();

        $locations=[];
        foreach($locationsobj as $l) {
            $locations[] = [
                'id' => $l->id,
                'name' => $l->name . '-' . ($l->city->name ?? '')
            ];
        }

        return [
            'status'=>'success',
            'data'=>compact('locations')
        ];
    }

}
