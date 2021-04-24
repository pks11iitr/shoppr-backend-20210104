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

    public function checkServiceAvailability(Request $request)
    {
        $location = $request->location;
        $city = City::active()->where('name', $request->city)->first();

        if (!$city)
            return [
                'status' => 'failed',
                'message' => 'Location is not serviceable'
            ];

        if (!empty($location)) {

            $json = json_decode($location, true);
            if (count($json) >= 4) {
                $json = array_reverse($json);
                $locality1 = $json[2]['value'] ?? '';
                $locality2 = $json[3]['value'] ?? '';
                $locality3 = $json[4]['value'] ?? '';
                $locality4 = $json[5]['value'] ?? '';
                $locality5 = $json[6]['value'] ?? '';

                $location = WorkLocations::active()
                    ->where(function ($query) use ($locality1, $locality2, $locality3, $locality4, $locality5)
                    {
                        $query->where('name', $locality1)
                            ->orWhere('name', $locality2)
                            ->orWhere('name', $locality3)
                            ->orWhere('name', $locality4)
                            ->orWhere('name', $locality5);
                    })
                    ->where('city_id', $city->id)
                    ->first();
                if ($location) {
                    return [
                        'status' => 'success',
                        'message' => 'Location is serviceable'
                    ];
                }
            }
        }

        return [
            'status'=>'failed',
            'message'=>'Location is not serviceble'
        ];

    }
}
