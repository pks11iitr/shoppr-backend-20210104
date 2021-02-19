<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\Shoppr;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function index(Request $request){

        $checkins =Checkin::where('id', '>=', 0);

        if(isset($request->fromdate))
            $checkins = $checkins->where('created_at', '>=', $request->fromdate.' 00:00:00');

        if(isset($request->todate))
            $checkins = $checkins->where('created_at', '<=', $request->todate.' 23:59:59');

        if($request->shoppr_id)
            $checkins=$checkins->where('shoppr_id', $request->shoppr_id);

        $checkins =$checkins->orderBy('id', 'desc')->paginate(20);

        $riders = Shoppr::active()->get();

        return view('admin.checkin.view',['checkins'=>$checkins,'riders'=>$riders]);
    }
}
