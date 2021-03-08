<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Exports\CheckinExport;
use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\Shoppr;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CheckinController extends Controller
{
    public function index(Request $request){

        $checkins =Checkin::with(['shoppr'=>function($shopper){
            $shopper->select('id','name');
        }])->where('id', '>=', 0);

        if(isset($request->fromdate))
            $checkins = $checkins->where('created_at', '>=', $request->fromdate.' 00:00:00');

        if(isset($request->todate))
            $checkins = $checkins->where('created_at', '<=', $request->todate.' 23:59:59');

        if($request->shoppr_id)
            $checkins=$checkins->where('shoppr_id', $request->shoppr_id);

        //var_dump($request->type);die();
        if($request->type=='export')
            return $this->export($checkins);

        $checkins =$checkins->orderBy('id', 'desc')->paginate(60);
        $checkin_count=0;
        $checkout_count=0;
        foreach($checkins as $check){
            if($check->type=='checkin')
                $checkin_count=$checkin_count+1;
            if($check->type=='checkout')
                $checkout_count++;
        }

        $attendences=[];

        foreach($checkins as $check){
            if(!isset($attendences[$check->shoppr->name.'**'.date('Y-m-d', strtotime($check->created_at))])){
                $attendences[$check->shoppr->name.'**'.date('Y-m-d', strtotime($check->created_at))]=[];
            }
            $attendences[$check->shoppr->name.'**'.date('Y-m-d', strtotime($check->created_at))][$check->type]['time']=date('h:ia', strtotime($check->created_at));
            $attendences[$check->shoppr->name.'**'.date('Y-m-d', strtotime($check->created_at))][$check->type]['address']=$check->address;
        }

        $riders = Shoppr::active()->get();

        return view('admin.checkin.view',['attendences'=>$attendences,'riders'=>$riders, 'checkins'=>$checkins, 'checkin_count'=>$checkin_count, 'checkout_count'=>$checkout_count]);
    }

    public function export($checkins)
    {
        $checkins=$checkins->get();

        $attendences=[];

        foreach($checkins as $check){
            if(!isset($attendences[$check->shoppr->name.'**'.date('Y-m-d', strtotime($check->created_at))])){
                $attendences[$check->shoppr->name.'**'.date('Y-m-d', strtotime($check->created_at))]=[];
            }
            $attendences[$check->shoppr->name.'**'.date('Y-m-d', strtotime($check->created_at))][$check->type]['time']=date('h:ia', strtotime($check->created_at));
            $attendences[$check->shoppr->name.'**'.date('Y-m-d', strtotime($check->created_at))][$check->type]['address']=$check->address;
        }
        //var_dump($checkins->toArray());die;
        return Excel::download(new CheckinExport($attendences), 'checkins.xlsx');
    }

}
