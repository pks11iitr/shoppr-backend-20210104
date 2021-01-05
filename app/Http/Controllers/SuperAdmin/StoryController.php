<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

class StoryController extends Controller
{
     public function index(Request $request){

               $newsupdates=Story::where(function($newsupdates) use($request){
                $newsupdates->where('title','LIKE','%'.$request->search.'%');
                 });

            if($request->ordertype)
                $newsupdates=$newsupdates->orderBy('created_at', $request->ordertype);

            $newsupdates=$newsupdates->paginate(10);
            return view('admin.story.view',['newsupdates'=>$newsupdates]);
              }


    public function create(Request $request){
            return view('admin.story.add');
               }

   public function store(Request $request){
               $request->validate([
                  			'isactive'=>'required',
                  			'title'=>'required',
                  			'short_description'=>'required',
                  			'description'=>'required',
                  			'image'=>'required|image'
                               ]);

          if($newsupdate=Story::create([
                      'isactive'=>$request->isactive,
                      'description'=>$request->description,
                      'title'=>$request->title,
                      'short_description'=>$request->short_description,
                      'image'=>'a']))
            {
				$newsupdate->saveImage($request->image, 'newsupdate');
             return redirect()->route('story.list')->with('success', 'News has been created');
            }
             return redirect()->back()->with('error', 'News create failed');
          }

    public function edit(Request $request,$id){
             $newsupdate = Story::findOrFail($id);
             return view('admin.story.edit',['newsupdate'=>$newsupdate]);
             }

    public function update(Request $request,$id){
             $request->validate([
                            'isactive'=>'required',
                  			'description'=>'required',
                  			'title'=>'required',
                  			'short_description'=>'required',
                  			'image'=>'image'
                               ]);
             $newsupdate = Story::findOrFail($id);
          if($request->image){
			 $newsupdate->update([
                      'isactive'=>$request->isactive,
                      'description'=>$request->description,
                      'title'=>$request->title,
                      'short_description'=>$request->short_description,
                      'image'=>'a']);
             $newsupdate->saveImage($request->image, 'newsupdate');
        }else{
             $newsupdate->update([
                      'isactive'=>$request->isactive,
                      'title'=>$request->title,
                      'short_description'=>$request->short_description,
                      'description'=>$request->description
                           ]);
             }
          if($newsupdate)
             {
           return redirect()->route('story.list')->with('success', 'News has been updated');
              }
           return redirect()->back()->with('error', 'News update failed');

      }


     public function delete(Request $request, $id){
         Story::where('id', $id)->delete();
           return redirect()->back()->with('success', 'News has been deleted');
        }
  }
