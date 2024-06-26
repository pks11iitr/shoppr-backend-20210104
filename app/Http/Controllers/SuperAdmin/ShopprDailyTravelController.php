<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\Shoppr;
use App\Models\ShopprDailyTravel;
use Illuminate\Http\Request;

class ShopprDailyTravelController extends Controller
{
    public function index(Request $request){
        $dailytravels =ShopprDailyTravel::with(['shoppr'=>function($shopper){
            $shopper->select('id','name');
        }])->where('id', '>=', 0);

        //var_dump($request->fromdate);die();

        if(isset($request->fromdate))
            $dailytravels = $dailytravels->where('date', '>=', $request->fromdate.' 00:00:00');

        if(isset($request->todate))
            $dailytravels = $dailytravels->where('date', '<=', $request->todate.' 23:59:59');

        if($request->shoppr_id)
            $dailytravels=$dailytravels->where('shoppr_id', $request->shoppr_id);

        $total_km=$dailytravels->sum('km');
        $total_commission=$dailytravels->sum('rider_commission');

        $dailytravels =$dailytravels->orderBy('id', 'desc')->paginate(20);

        $riders = Shoppr::active()->get();

        return view('admin.shoppr-daily-travel.view',['dailytravels'=>$dailytravels,'riders'=>$riders, 'total_km'=>$total_km, 'total_commission'=>$total_commission]);

    }

    public function create(Request $request){

        $shopprs =Shoppr::active()->get();

        return view('admin.shoppr-daily-travel.add',['shopprs'=>$shopprs]);

    }

    public function store(Request $request){
        $request->validate([
            'shoppr_id'=>'required',
            'date'=>'required',
            'km'=>'required',
        ]);

        $settings=Settings::where('name', 'Per Km Charge')->first();

        if($dailytravel=ShopprDailyTravel::create([
            'shoppr_id'=>$request->shoppr_id,
            'date'=>$request->date,
            'km'=>$request->km,
            'rider_commission'=>$request->km*($settings->value??0.0)

        ]))
        {
            return redirect()->route('dailytravel.list')->with('success', 'Shoppr Daily Travel has been created');
        }
        return redirect()->back()->with('error', 'Shoppr Daily Travel create failed');
    }

    public function edit(Request $request,$id){
        $dailytravel =ShopprDailyTravel::findOrFail($id);

        $shopprs =Shoppr::active()->get();
        return view('admin.shoppr-daily-travel.edit',['dailytravel'=>$dailytravel,'shopprs'=>$shopprs]);
    }

    public function update(Request $request,$id){
        $request->validate([
            'shoppr_id'=>'required',
            'date'=>'required',
            'km'=>'required',
        ]);

        $dailytravel =ShopprDailyTravel::findOrFail($id);

        $settings=Settings::where('name', 'Per Km Charge')->first();

        if($dailytravel->update([
            'shoppr_id'=>$request->shoppr_id,
            'date'=>$request->date,
            'km'=>$request->km,
            'rider_commission'=>$request->km*($settings->value??0.0)

        ]))
        {
            return redirect()->route('dailytravel.list')->with('success', 'Shoppr Daily Travel has been updated');
        }
        return redirect()->back()->with('error', 'Shoppr Daily Travel update failed');
    }
}
