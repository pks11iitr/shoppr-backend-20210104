<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request){
        $customers=Customer::where(function($customers) use($request){
            $customers->where('name','LIKE','%'.$request->search.'%')
                ->orWhere('mobile','LIKE','%'.$request->search.'%')
                ->orWhere('email','LIKE','%'.$request->search.'%');
        });

        if($request->fromdate)
            $customers=$customers->where('created_at', '>=', $request->fromdate.' 00:00:00');

        if($request->todate)
            $customers=$customers->where('created_at', '<=', $request->todate.' 23:59:50');

        if($request->status)
            $customers=$customers->where('status', $request->status);

        if($request->ordertype)
            $customers=$customers->orderBy('created_at', $request->ordertype);

        $customers=$customers->paginate(20);
        return view('admin.customer.view',['customers'=>$customers]);
    }

    public function edit(Request $request,$id){
        $customer = Customer::findOrFail($id);
        return view('admin.customer.edit',['customer'=>$customer]);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name'=>'required',
            'status'=>'required',
            'email'=>'required',
            'image'=>'image'
        ]);

        $customer = Customer::findOrFail($id);

        if($customer->update([
            'name'=>$request->name,
            'mobile'=>$request->mobile,
            'email'=>$request->email,
            'status'=>$request->status,
        ]))
        {
            if($request->image){
                $customer->saveImage($request->image,'customer');
            }
            return redirect()->route('customer.list')->with('success','customer has been updated');
        }
        return redirect()->back()->with('error','customer has been failed');

    }
}
