<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request){
        $cities = City::get();
        return view('admin.city.view',['cities'=>$cities]);
    }

    public function create(Request $request){
        $states = State::active()->get();
        return view('admin.city.add',['states'=>$states]);
    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required',
            'isactive'=>'required'
        ]);

        if($city=City::create([
            'name'=>$request->name,
            'isactive'=>$request->isactive,
            'state_id'=>$request->state_id,
        ]))

        {
            return redirect()->route('city.list')->with('success', 'City has been created');
        }
        return redirect()->back()->with('error', 'City create failed');
    }

    public function edit(Request $request,$id){
        $city = City::findOrFail($id);
        $states = State::active()->get();
        return view('admin.city.edit',['city'=>$city,'states'=>$states]);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name'=>'required',
            'isactive'=>'required'
        ]);

        $city = City::findOrFail($id);

        if($city->update([
            'name'=>$request->name,
            'isactive'=>$request->isactive,
            'state_id'=>$request->state_id,
        ]))

        {
            return redirect()->route('city.list')->with('success', 'City has been updated');
        }
        return redirect()->back()->with('error', 'City update failed');
    }
}
