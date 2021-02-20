<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Http\Request;
use DB;

class StoreController extends Controller
{
    public function index(Request $request){
        $latitude=   $request->lat??'28.56834';
        $longitude= $request->lang??'77.56834';
        $category_id=$request->category_id??[];
        $search=$request->search;
        $sortby=$request->sortby??'name';
        $stores = Store::active()
            ->select(DB::raw('*, ROUND(( 6367 * acos( cos( radians(' . $latitude . ') ) * cos( radians( stores.lat ) ) * cos( radians( stores.lang ) - radians(' . $longitude . ') ) + sin( radians(' . $latitude . ') ) * sin( radians( stores.lat ) ) ) ),2) AS distance'));

        if(!empty($category_id)){
            $stores=$stores->whereHas('categories', function($category) use($category_id){
                $category->whereIn('categories.id', $category_id);
            })   ;
        };
        if($search){
            $stores=$stores->where(function($query) use ($search){
                $query->where('store_name', 'LIKE', "%".$search."%")
                ->orWhereHas('categories', function($categories) use($search){
                    $categories->where('name', 'LIKE', "%".$search."%");
                });
            });
        }

        if($sortby){
            if($request->sortby=='name')
                $stores=$stores->orderBy('store_name', 'ASC');
            else
                $stores=$stores->orderBy('distance', 'ASC');
        }

        $stores=$stores->get();
       // $stores=Store::active()->get();

        $categories=Category::active()->select('id','name')->get();

        return [
            'status'=>'success',
            'message'=>'success',
            'data'=>compact('stores', 'categories')
        ];

    }

    public function details(Request $request,$id){

        if(empty($id)){
            return [
                'status'=>'failed',
                'message'=>'Store Id parameter Missing',
            ];
        }
        $stores_details=Store::active()->with('images')->where('id',$id)->first();
        if($stores_details){
            return [
                'status'=>'success',
                'message'=>'success',
                'data'=>compact('stores_details')
            ];
        }else{
            return [
                'status'=>'failed',
                'message'=>'Some error found',
            ];
        }
    }
}
