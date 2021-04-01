<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Shoppr;
use App\Models\WorkLocations;
use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    public function index(Request $request){
        $user=auth()->guard('customerapi')->user();
        $location=WorkLocations::extractlocationfromjson($request->location, $request->city);
//        if(!$location)
//            return [
//                'status'=>'failed',
//                'message'=>'Location is not servicable'
//            ];

//       $shopper= Shoppr::groupBy('location')->select(DB::raw('count(*) as shoppr_count, location'))->where('isactive',1)->get();

        $shopprs=Shoppr::active()
            ->whereHas('locations', function($query)use($location) {
                $query->where('shoppr_work_locations.id', $location->id??0);
            })->count();

        $shopper[0]=[
            'shoppr_count'=>$shopprs??0,
            'location'=>''
        ];

        $notifications=Notification::where('user_type', 'CUSTOMER')
            ->where('user_id', $user->id)
            ->where('seen_at', null)
            ->count();


       return [
           'status'=>'success',
           'message'=>'success',
           'data'=>compact('shopper', 'notifications')
       ];


    }
}
