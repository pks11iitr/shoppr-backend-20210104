<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\WorkLocations;
use Illuminate\Http\Request;

class AvailableLocationController extends Controller
{
    public function locations(Request $request){
        $locationsobj=WorkLocations::active()->with('city')->select('id','name')->get();
        foreach($locationsobj as $l){
            $locations[]=[
                'id'=>$l->id,
                'name'=>$l->name.'-'.($l->city->name??'')
            ];
        }
        return [
            'status'=>'success',
            'data'=>compact('locations')
        ];

    }

    public function checkServiceAvailability(Request $request){
        $location=$request->location;
        $city=City::active()->where('name', $request->city)->first();

        if(!$city)
            return [
                'status'=>'failed',
                'message'=>'Location is not servicable'
            ];

        $json=json_decode($location, true);
        if(count($json)>=4){
            $json=array_reverse($json);
            $locality1=$json[3]['value']??'';
            $locality2=$json[4]['value']??'';

            $location=WorkLocations::active()
                ->where(function($query)use($locality1,$locality2){
                        $query->where('name', $locality1)
                            ->orWhere('name',$locality2);
                    })
                ->where('city_id', $city->id)
                ->first();
            if($location){
                return [
                    'status'=>'success',
                    'message'=>'Location is serviceble'
                ];
            }
        }

        return [
            'status'=>'failed',
            'message'=>'Location is not serviceble'
        ];

    }
}
