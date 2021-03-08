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
}
