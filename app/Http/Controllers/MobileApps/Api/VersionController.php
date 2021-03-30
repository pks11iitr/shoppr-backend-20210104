<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\AppVersion;
use Illuminate\Http\Request;

class VersionController extends Controller
{

    public function version(Request $request){
        $version=AppVersion::orderBy('id', 'desc')->first();
        return [
            'status'=>'success',
            'data'=>[
                'customer_version'=>$version->customer_version??'',
                'rider_version'=>$version->rider_version??''
            ]
        ];
    }
}
