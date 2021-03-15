<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\WorkLocation;
use Illuminate\Http\Request;

class WorkLocationController extends Controller
{
    public function index(Request $request){

        $worklocations = WorkLocation::paginate(20);

        return view('admin.work-location.view',['worklocations'=>$worklocations]);
    }

    public function create(Request $request){
        return view('admin.work-location.add');
    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required',
            'isactive'=>'required'
        ]);

        if($worklocation=WorkLocation::create([
            'name'=>$request->name,
            'isactive'=>$request->isactive
        ]))
        {
            return redirect()->route('worklocation.list')->with('success', 'Work Location has been created');
        }
        return redirect()->back()->with('error', 'Work Location create failed');
    }

    public function edit(Request $request,$id){

        $worklocation = WorkLocation::findOrFail($id);

        return view('admin.work-location.edit',['worklocation'=>$worklocation]);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name'=>'required',
            'isactive'=>'required'
        ]);

        $worklocation =WorkLocation::findOrFail($id);

        if($worklocation->update([
            'name'=>$request->name,
            'isactive'=>$request->isactive
        ]))
        {
            return redirect()->route('worklocation.list')->with('success', 'Work Location has been updated');
        }
        return redirect()->back()->with('error', 'Work Location update failed');
    }

}
