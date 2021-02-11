<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
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


}
