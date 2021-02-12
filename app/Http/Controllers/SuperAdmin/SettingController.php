<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

class SettingController extends Controller
{
    public function index(Request $request){

        $datas=Settings::get();
        return view('admin.setting.view',['datas'=>$datas]);
    }



    public function edit(Request $request,$id){
        $data = Settings::findOrFail($id);
        return view('admin.setting.edit',['data'=>$data]);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name'=>'required',
            'value'=>'required',
        ]);
        $data = Settings::findOrFail($id);

        if($data->update($request->only('name','value')))
        {
            return redirect()->route('setting.list')->with('success', 'Setting has been updated');
        }
        return redirect()->back()->with('error', 'Setting failed');

    }


    public function delete(Request $request, $id){
        Story::where('id', $id)->delete();
        return redirect()->back()->with('success', 'News has been deleted');
    }
}
