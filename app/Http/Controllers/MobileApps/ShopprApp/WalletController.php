<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ShopprDailyTravel;
use App\Models\ShopprWallet;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function getWalletBalance(Request $request)
    {
        return [
            'status'=>'success',
            'message'=>'',
            'data'=>[
                'balance'=>ShopprWallet::balance($request->user->id)
            ]
        ];
    }

    public function index(Request $request)
    {
        $user=$request->user;
        $historyobj = ShopprWallet::where('user_id', $user->id)
            ->select('amount', 'created_at', 'description', 'refid', 'type')
            ->orderBy('id', 'desc')
            ->get();

        $history=[];
        foreach($historyobj as $h){

            if(!isset($history[date('D, M d, Y',strtotime($h->created_at))])){
                $history[date('D, M d, Y',strtotime($h->created_at))]=[];
            }
            $history[date('D, M d, Y',strtotime($h->created_at))][]=$h;
        }

        $wallet_transactions=[];
        foreach($history as $date=>$date_transactions){

            $tlist=[];
            foreach($date_transactions as $t)
                $tlist[]=$t;

            $wallet_transactions[]=[
                'date'=>$date,
                'transactions'=>$tlist,
            ];
        }

        $balance = ShopprWallet::balance($user->id);

        return [
            'status' => 'success',
            'data' => compact('wallet_transactions', 'balance')
        ];
    }

    public function commissions(Request $request){

        $user=$request->user;
        $historyobj=Order::where('shoppr_id', $user->id)
            ->select('refid', 'created_at', 'rider_commission', 'rider_delivery_charge')
            ->where('status', 'Delivered');

        if($request->from_date){
            $historyobj=$historyobj->where('created_at', '>=', $request->from_date.' 00:00:00');
        }

        if($request->to_date){
            $historyobj=$historyobj->where('created_at', '<=', $request->to_date.' 23:59:59');
        }

        $historyobj=$historyobj->orderBy('id', 'desc')
            ->get();

        $history=[];
        foreach($historyobj as $h){

            if(!isset($history[date('D, M d, Y',strtotime($h->getRawOriginal('created_at')))])){
                $history[date('D, M d, Y',strtotime($h->getRawOriginal('created_at')))]=[];
            }
            $history[date('D, M d, Y',strtotime($h->getRawOriginal('created_at')))][]=$h;
        }

        $commission_transactions=[];
        foreach($history as $date=>$date_transactions){

            $tlist=[];
            foreach($date_transactions as $t){
                $t->time=date('h:iA', strtotime($h->getRawOriginal('created_at')));
                $tlist[]=$t;
            }


            $commission_transactions[]=[
                'date'=>$date,
                'transactions'=>$tlist,
            ];
        }

        $commission=Order::where('shoppr_id', $user->id)
            ->where('status', 'Delivered');

        if($request->from_date){
            $commission=$commission->where('created_at', '>=', $request->from_date.' 00:00:00');
        }

        if($request->to_date){
            $commission=$commission->where('created_at', '<=', $request->to_date.' 23:59:59');
        }

        $delivery_charge=$commission->sum('rider_delivery_charge');
        $commission=$commission->sum('rider_commission');

        return [
            'status' => 'success',
            'data' => compact('commission_transactions','delivery_charge', 'commission')
        ];

    }

    public function kmCommissions(Request $request){
        $user=$request->user;
        $historyobj=ShopprDailyTravel::where('shoppr_id', $user->id)
            ->select('date', 'shoppr_id', 'rider_commission', 'km');
            //->where('status', 'Delivered');

        if($request->from_date){
            $historyobj=$historyobj->where('date', '>=', $request->from_date.' 00:00:00');
        }

        if($request->to_date){
            $historyobj=$historyobj->where('date', '<=', $request->to_date.' 23:59:59');
        }

        $historyobj=$historyobj->orderBy('id', 'desc')
            ->get();

        $history=[];
        foreach($historyobj as $h){

            if(!isset($history[date('D, M d, Y',strtotime($h->getRawOriginal('date')))])){
                $history[date('D, M d, Y',strtotime($h->getRawOriginal('date')))]=[];
            }
            $history[date('D, M d, Y',strtotime($h->getRawOriginal('date')))][]=$h;
        }

        $commission_transactions=[];
        foreach($history as $date=>$date_transactions){

            $tlist=[];
            foreach($date_transactions as $t){
                $t->time=date('h:iA', strtotime($h->getRawOriginal('date')));
                $tlist[]=$t;
            }


            $commission_transactions[]=[
                'date'=>$date,
                'transactions'=>$tlist,
            ];
        }

        $historyobj=ShopprDailyTravel::where('shoppr_id', $user->id)
            ->select('date', 'shoppr_id', 'rider_commission');
            //->where('status', 'Delivered');

        if($request->from_date){
            $historyobj=$historyobj->where('date', '>=', $request->from_date.' 00:00:00');
        }

        if($request->to_date){
            $historyobj=$historyobj->where('date', '<=', $request->to_date.' 23:59:59');
        }

        $total_km=$historyobj->sum('km');
        $commission=$historyobj->sum('rider_commission');

        return [
            'status' => 'success',
            'data' => compact('commission_transactions','total_km', 'commission')
        ];
    }


}
