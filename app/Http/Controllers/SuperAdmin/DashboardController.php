<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Shoppr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $orders=Order::whereIn('status', ['Pending', 'Confirmed', 'Delivered'])
            ->groupBy('status')
            ->selectRaw('count(*) as count, sum(total)+sum(service_charge) as total_cost, sum(balance_used) as balance')
            ->get();

        $product_orders_array=[];
        $total_order=0;
        //echo '<pre>';
        //var_dump($product_orders);die;
        //echo '<pre>';
        $orders_array=[];
        foreach($orders as $o){
            //echo $o->count??0;
            if(!isset($orders_array[$o->status]))
                $orders_array[$o->status]=0;
            $orders_array[$o->status]=$o->count??0;
            $total_order=$total_order+($o->count??0);
            //var_dump($therapy_orders_array);
        }

        $orders_array['total']=$total_order;

        $revenue=Order::whereIn('status', ['Delivered'])
            ->selectRaw('sum(total) as total_cost, sum(balance_used) as balance')
            ->get();

        $revenue_array=[];
        $revenue_array['total']=$revenue[0]['total_cost']??0;
        $revenue_array['balance']=$revenue[0]['balance']??0;
        //$revenue_product['coupon']=$product_revenue[0]['coupon']??0;
        //return $revenue_product;

        //var_dump($therapy_orders_array);die;

        $customers=Customer::count();
        $shopprs=Shoppr::count();


        return view('admin.home', [
            'orders'=>$orders_array,
            'revenue'=>$revenue_array,
            'customers'=>$customers,
            'shopprs'=>$shopprs
        ]);
    }
}
