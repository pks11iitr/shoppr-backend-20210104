<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Exports\ShopprExport;
use App\Exports\StoreExport;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Document;
use App\Models\Store;
use App\Models\WorkLocations;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StoreController extends Controller
{
    public function index(Request $request){

        $datas=Store::where(function($datas) use($request){
            $datas->where('store_name','LIKE','%'.$request->search.'%');
        });

        if($request->ordertype)
            $datas=$datas->orderBy('created_at', $request->ordertype);

        if($request->type=='export')
            return $this->export($datas);

        $datas=$datas->paginate(10);
        return view('admin.store.view',['datas'=>$datas]);
    }

    public function export($datas)
    {
        $datas=$datas->get();

        return Excel::download(new StoreExport($datas), 'store.xlsx');
    }


    public function create(Request $request){
        $categories=Category::active()->get();
        $worklocation=WorkLocations::active()->get();
        return view('admin.store.add', compact('categories', 'worklocation'));
    }

    public function store(Request $request){
        $request->validate([
            'isactive'=>'required',
            'store_name'=>'required',
            'store_type'=>'required',
            'mobile'=>'digits:10',
            'email'=>'required',
            'opening_time'=>'required',
            'address'=>'string',
            'about_store'=>'string',
            //'lat'=>'required',
            //'lang'=>'required',
            'is_sale'=>'required',
            'image'=>'required|image',
            'location_id'=>'required|integer'
        ]);

        if($data=Store::create($request->only('store_name','store_type','email','lat','lang','isactive','mobile','opening_time','address','about_store','is_sale', 'location_id')))
        {
            $data->saveImage($request->image, 'stores');
            $data->categories()->sync($request->categories);
            return redirect()->route('store.list')->with('success', 'Data has been created');
        }
        return redirect()->back()->with('error', 'Data create failed');
    }

    public function edit(Request $request,$id){
        $data = Store::with('images')->findOrFail($id);
        $categories=Category::active()->get();
        return view('admin.store.edit',['data'=>$data, 'categories'=>$categories]);
    }

    public function update(Request $request,$id){
        $request->validate([
            'isactive'=>'required',
            'store_name'=>'required',
            'store_type'=>'required',
            'mobile'=>'digits:10',
            'email'=>'required',
            'opening_time'=>'required',
            'address'=>'string',
            'about_store'=>'string',
            //'lat'=>'required',
            //'lang'=>'required',
            'is_sale'=>'required',
            'image'=>'image',
            'location_id'=>'required|integer'
        ]);
        $data = Store::with('categories')->findOrFail($id);

        if($data->update($request->only('store_name','store_type','email','lat','lang','isactive','mobile','opening_time','address','about_store','is_sale', 'location_id')))
        {
            $data->categories()->sync($request->categories);
            if($request->image){
                $data->saveImage($request->image, 'stores');
            }
            return redirect()->route('store.list')->with('success', 'Data has been updated');
        }
        return redirect()->back()->with('error', 'Data update failed');

    }

    public function images(Request  $request,$id){
        $request->validate([
            'images'=>'required|array',
        ]);
        $data=Store::find($id);

        foreach($request->images as $image){
            $data=Document::create([
                'store_id'=>$id,
                'image'=>'a']);
            $data=$data->saveImage($image, 'stores');
        }
           // var_dump($image);; die();

        return redirect()->back()->with('success', 'Store images uploaded Successfully');
    }
    public function deleteimage (Request $request,$id){
        Document::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Images has been deleted');

    }
    /*  public function delete(Request $request, $id){
          Shoppr::where('id', $id)->delete();
          return redirect()->back()->with('success', 'Data has been deleted');
      }*/
}

