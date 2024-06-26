<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Exports\CheckinExport;
use App\Exports\CommissionExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Settings;
use App\Models\Shoppr;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Storage;

class CommissionController extends Controller
{

        public function index(Request $request){

            $historyobj=Order::select('refid', 'created_at', 'rider_commission','shoppr_id')
                ->where('status', 'Delivered');

            if(isset($request->search)){
                $historyobj=Order::where('status', 'Delivered')->where(function($historyobj) use ($request){

                    $historyobj->where('refid', 'like', "%".$request->search."%")
                        ->orWhereHas('shoppr', function($shoppr)use( $request){
                            $shoppr->where('name', 'like', "%".$request->search."%");
                        });
                });

            }

            if(isset($request->from_date)){
                $historyobj=$historyobj->where('created_at', '>=', $request->from_date.' 00:00:00');
            }

            if(isset($request->to_date)){
                $historyobj=$historyobj->where('created_at', '<=', $request->to_date.' 23:59:59');
            }
            if($request->shoppr_id) {
                $historyobj = $historyobj->where('shoppr_id', $request->shoppr_id);

            }

            if($request->type=='export')
                return $this->export($historyobj);

            $total_commission=$historyobj->sum('rider_commission');
            $delivery_charge=$historyobj->sum('rider_delivery_charge');

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
                foreach($date_transactions as $t)
                    $t->created_at=date('h:iA', strtotime($h->getRawOriginal('created_at')));
                $tlist[]=$t;

                $commission_transactions[]=[
                    'date'=>$date,
                    'transactions'=>$tlist,
                ];
            }

            $commission=Order::where('status', 'Delivered');

            if(isset($request->from_date)){
                $commission=$commission->where('created_at', '>=', $request->from_date.' 00:00:00');
            }

            if(isset($request->to_date)){
                $commission=$commission->where('created_at', '<=', $request->to_date.' 23:59:59');
            }

            $commission=$commission->sum('rider_commission');

            $riders = Shoppr::active()->get();

            return view('admin.commission.view',['commission_transactions'=>$historyobj,'commission'=>$commission,'riders'=>$riders, 'delivery_charge'=>$delivery_charge, 'total_commission'=>$total_commission]);

        }

    public function export($historyobj)
    {
        $historyobj=$historyobj->get();

        return Excel::download(new CommissionExport($historyobj), 'commission.xlsx');
    }


}
