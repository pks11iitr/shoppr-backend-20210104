<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
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
        $city=City::active()->where('name', $request->city)->get();

        if(!$city)
            return [
                'status'=>'success',
                'message'=>'Location is serviceble'
            ];

        $json=json_decode($location, true);
        if(count($json)>=4){
            $json=array_reverse($json);
            $locality1=$json[3]['value']??'';
            $locality2=$json[4]['value']??'';

            $location=WorkLocations::active()->where(function($query)use($locality1,$locality2){
                $query->where('name', $locality1)
                    ->orWhere('name',$locality2);
            })
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
