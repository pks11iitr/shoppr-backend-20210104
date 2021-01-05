<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Shoppr;
use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    public function index(){
        $user=auth()->guard('customerapi')->user();
       $shopper= Shoppr::groupBy('location')->select(DB::raw('count(*) as shoppr_count, location'))->where('isactive',1)->get();

       if($shopper->count()>0){
           return [
               'status'=>'success',
               'message'=>'success',
               'data'=>compact('shopper')
           ];
       }else{
           return [
               'status'=>'failed',
               'message'=>'No Record Found'
           ];
       }

    }
}
