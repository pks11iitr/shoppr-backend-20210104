<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\Shoppr;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function index(Request $request){

        $checkins =Checkin::with('shoppr')->where('id', '>=', 0);

        if(isset($request->fromdate))
            $checkins = $checkins->where('created_at', '>=', $request->fromdate.' 00:00:00');

        if(isset($request->todate))
            $checkins = $checkins->where('created_at', '<=', $request->todate.' 23:59:59');

        if($request->shoppr_id)
            $checkins=$checkins->where('shoppr_id', $request->shoppr_id);

        $checkins =$checkins->orderBy('id', 'desc')->paginate(60);

        $attendences=[];

        foreach($checkins as $check){
            if(!isset($attendences[$check->shoppr->name.'**'.date('Y-m-d', strtotime($check->created_at))])){
                $attendences[$check->shoppr->name.'**'.date('Y-m-d', strtotime($check->created_at))]=[];
            }
            $attendences[$check->shoppr->name.'**'.date('Y-m-d', strtotime($check->created_at))][$check->type]['time']=date('h:ia', strtotime($check->created_at));
            $attendences[$check->shoppr->name.'**'.date('Y-m-d', strtotime($check->created_at))][$check->type]['address']=$check->address;
        }

        $riders = Shoppr::active()->get();

        return view('admin.checkin.view',['attendences'=>$attendences,'riders'=>$riders, 'checkins'=>$checkins]);
    }
}
