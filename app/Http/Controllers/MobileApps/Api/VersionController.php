<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VersionController extends Controller
{

    public function version(Request $request){
        $version=AppVersion::orderBy('id', 'desc')->first();
        return [
            'status'=>'success',
            'data'=>[
                'android_version'=>$version->android_version??'',
                'ios_version'=>$version->ios_version??''
            ]
        ];
    }
}
