<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\WorkLocation;
use Illuminate\Http\Request;

class WorkLocationController extends Controller
{
    public function index(Request $request){

        $worklocations = WorkLocation::paginate(20);
        return view('admin.work-location.view',['worklocations'=>$worklocations]);
    }

    public function create(Request $request){
        $cities=City::get();
        return view('admin.work-location.add', compact('cities'));
    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required',
            'isactive'=>'required',
            'city_id'=>'required'
        ]);

        if($worklocation=WorkLocation::create([
            'name'=>$request->name,
            'isactive'=>$request->isactive,
            'city_id'=>$request->city_id
        ]))
        {
            return redirect()->route('worklocation.list')->with('success', 'Work Location has been created');
        }
        return redirect()->back()->with('error', 'Work Location create failed');
    }

    public function edit(Request $request,$id){

        $worklocation = WorkLocation::findOrFail($id);
        $cities=City::get();
        return view('admin.work-location.edit',['worklocation'=>$worklocation, 'cities'=>$cities]);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name'=>'required',
            'isactive'=>'required',
            'city_id'=>'required'
        ]);

        $worklocation =WorkLocation::findOrFail($id);

        if($worklocation->update([
            'name'=>$request->name,
            'isactive'=>$request->isactive,
            'city_id'=>$request->city_id
        ]))
        {
            return redirect()->route('worklocation.list')->with('success', 'Work Location has been updated');
        }
        return redirect()->back()->with('error', 'Work Location update failed');
    }

}
