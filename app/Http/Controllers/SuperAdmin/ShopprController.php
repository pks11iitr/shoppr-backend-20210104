<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Exports\CustomerExport;
use App\Exports\ShopprExport;
use App\Http\Controllers\Controller;
use App\Models\Shoppr;
use App\Models\ShopprWallet;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ShopprController extends Controller
{
    public function index(Request $request){

        $datas=Shoppr::where(function($datas) use($request){
            $datas->where('name','LIKE','%'.$request->search.'%');
        });

        if($request->ordertype)
            $datas=$datas->orderBy('created_at', $request->ordertype);

        if($request->type=='export')
            return $this->export($datas);

        $datas=$datas->paginate(10);
        return view('admin.shoppr.view',['datas'=>$datas]);
    }

    public function export($datas)
    {
        $datas=$datas->get();

        return Excel::download(new ShopprExport($datas), 'shoppr.xlsx');
    }


    public function create(Request $request){
        return view('admin.shoppr.add');
    }

    public function store(Request $request){
        $request->validate([
            'isactive'=>'required',
            'name'=>'required',
            'mobile'=>'required|digits:10|unique:shoppers',
            'location'=>'required',
            'lat'=>'required',
            'lang'=>'required',
            'status'=>'required',
            'image'=>'required|image',
        ]);

        if($data=Shoppr::create($request->only('name','lat','lang','isactive','mobile','location','status')))
        {
            $data->saveImage($request->image, 'customers');
            return redirect()->route('shoppr.list')->with('success', 'Data has been created');
        }
        return redirect()->back()->with('error', 'Data create failed');
    }

    public function edit(Request $request,$id){
        $data = Shoppr::findOrFail($id);
        return view('admin.shoppr.edit',['data'=>$data]);
    }

    public function update(Request $request,$id){
        $request->validate([
            'isactive'=>'required',
            'name'=>'required',
          //  'mobile'=>'required|digits:10|unique:shoppers',
            'location'=>'required',
            'lat'=>'required',
            'lang'=>'required',
            'status'=>'required',
            'image'=>'image',
        ]);
        $data = Shoppr::findOrFail($id);

        if($data->update($request->only('name','lat','lang','isactive','location','status')))
        {
            if($request->image){
                $data->saveImage($request->image, 'customers');
            }
            return redirect()->route('shoppr.list')->with('success', 'Data has been updated');
        }
        return redirect()->back()->with('error', 'Data update failed');

    }

  /*  public function delete(Request $request, $id){
        Shoppr::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data has been deleted');
    }*/

    public function addMoney(Request $request, $id){

        ShopprWallet::updatewallet($id, 'Amount Credited By Admin', $request->type, $request->amount);

        return redirect()->back()->with('success', 'Amount has been credited to shoppr wallet');

    }
    public function transaction(Request $request,$id){

       $datas= ShopprWallet::where('user_id',$id)->paginate(20);
        return view('admin.shoppr.history',['datas'=>$datas])->with('success', 'Data has been updated');
    }

    public function details(Request $request,$id){
        $shoppr =Shoppr::findOrFail($id);
        return view('admin.shoppr.details',['shoppr'=>$shoppr]);
    }
}

