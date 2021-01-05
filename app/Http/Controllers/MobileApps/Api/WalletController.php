<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->guard('customerapi')->user();
        if (!$user)
            return [
                'status' => 'failed',
                'message' => 'Please login to continue'
            ];
        if ($user) {
            $history = Wallet::where('user_id', $user->id)
                ->where('iscomplete', true)
                ->where('amount_type', 'CASH')
                ->select('amount', 'created_at', 'description', 'refid', 'type')
                ->orderBy('id', 'desc')->get();
            $balance = Wallet::balance($user->id);
        } else {
            $history = [];
            $balance = 0;
        }

        return [
            'status' => 'success',
            'data' => compact('history', 'balance')
        ];
    }
    public function userbalance(){
        $user = auth()->guard('customerapi')->user();
        if (!$user)
            return [
                'status' => 'failed',
                'message' => 'Please login to continue'
            ];
        $balance = Wallet::balance($user->id);
        if($balance){
            return [
                'status' => 'success',
                'message' => 'success',
                'data'=>$balance
            ];
        }else{
            return [
                'status' => 'failed',
                'message' => 'some error Found'
            ];
        }
    }

}
