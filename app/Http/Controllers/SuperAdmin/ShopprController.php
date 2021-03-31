<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Exports\CustomerExport;
use App\Exports\ShopprExport;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\City;
use App\Models\Shoppr;
use App\Models\ShopprWallet;
use App\Models\State;
use App\Models\WorkLocation;
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
        $worklocations=WorkLocation::active()->get();
        return view('admin.shoppr.add', compact('worklocations'));
    }

    public function store(Request $request){
        $request->validate([
            'isactive'=>'required',
            'name'=>'required',
            'mobile'=>'required|digits:10|unique:shoppers',
            //'location'=>'required',
            //'lat'=>'required',
            //'lang'=>'required',
            //'status'=>'required',
            'image'=>'required|image',
        ]);

        if($data=Shoppr::create($request->only('name','lat','lang','isactive','mobile','location','status')))
        {
            if($request->image){
                $data->saveImage($request->image, 'customers');
            }

            if($request->location_id){
                $data->locations()->sync($request->location_id);
            }

            return redirect()->route('shoppr.list')->with('success', 'Data has been created');
        }
        return redirect()->back()->with('error', 'Data create failed');
    }

    public function edit(Request $request,$id){
        $data = Shoppr::with(['cityname','statename','locations'])->findOrFail($id);
//       return $data->locations[0]->id;
        $States = State::active()->get();
        $cities=City::get();
        $locations = WorkLocation::active()->get();

        return view('admin.shoppr.edit',['data'=>$data,'States'=>$States,'locations'=>$locations, 'cities'=>$cities]);
    }

    public function update(Request $request,$id){
        $request->validate([
            'isactive'=>'required',
            'name'=>'required',
            'mobile'=>'required|digits:10|unique:shoppers,mobile,'.$id,
            //'status'=>'required',
            'image'=>'image',
        ]);
        $data = Shoppr::findOrFail($id);

        //var_dump($request->pay_per_km);
        //var_dump($request->pay_commission);die();

        if($data->update($request->only('name','isactive','status','permanent_address', 'permanent_pin','permanent_city','permanent_state', 'secondary_mobile','emergency_mobile','work_type','account_no','ifsc_code','account_holder','bank_name','address','state','city','email')))
        {
            if($request->image){
                $data->saveImage($request->image, 'customers');
            }
            if($request->location_id){
                $data->locations()->sync($request->location_id);
            }

            $per_km = (int)$request->pay_per_km??0;
            $commission = (int)$request->pay_commission??0;
            $delivery = (int)$request->pay_delivery??0;
           // var_dump($per_km);die();
//            var_dump($per_km);die();
            $data->pay_per_km=$per_km;
            $data->pay_commission=$commission;
            $data->pay_delivery=$delivery;
            $data->save();

            return redirect()->route('shoppr.list')->with('success', 'Data has been updated');
        }
        return redirect()->back()->with('error', 'Data update failed');

    }

  /*  public function delete(Request $request, $id){
        Shoppr::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data has been deleted');
    }*/

    public function addMoney(Request $request, $id){

        if($request->type='Credit')
            ShopprWallet::updatewallet($id, 'Amount Credited By Admin', $request->type, $request->amount);
        else
            ShopprWallet::updatewallet($id, 'Amount Deducted By Admin', $request->type, $request->amount);


        return redirect()->back()->with('success', 'Amount has been updated to shoppr wallet');

    }
    public function transaction(Request $request,$id){

       $datas= ShopprWallet::where('user_id',$id)->paginate(20);
        return view('admin.shoppr.history',['datas'=>$datas]);
    }

    public function details(Request $request,$id){
        $shoppr =Shoppr::findOrFail($id);
        return view('admin.shoppr.details',['shoppr'=>$shoppr]);
    }

    public function stateAjax(Request $request){
        $citys = City::active()
            ->where('state_id',$request->permanent_state)
            ->pluck("name","id");

        return json_encode($citys);
    }

    public function reviews(Request $request, $id){

        $reviews=ChatMessage::whereHas('chat', function($chat)use($id){
            $chat->where('shoppr_id', $id);
        })->where('chatmessages.type', 'rating')
            ->orderBy('chatmessages.id', 'desc')
            ->paginate(10);

        return view('admin.shoppr.reviews', compact('reviews'));
    }

    public function uploads(Request $request,$id){
        $document = Shoppr::findOrFail($id);
        return view('admin.shoppr.document-upload',['document'=>$document]);
    }

    public function uploadsUpdate(Request $request,$id){

        $document = Shoppr::findOrFail($id);

        if($request->pan_card){
            $document->savePanCard($request->pan_card, 'shopper');
        }
        if($request->front_aadhaar_card){
            $document->saveFrontAadhaarCard($request->front_aadhaar_card, 'shopper');
        }
        if($request->back_aadhaar_card){
            $document->saveBackAadhaarCard($request->back_aadhaar_card, 'shopper');
        }
        if($request->front_dl_no){
            $document->saveFrontDlNo($request->front_dl_no, 'shopper');
        }
        if($request->back_dl_no){
            $document->saveBackDlNo($request->back_dl_no, 'shopper');
        }
        if($request->bike_front){
            $document->saveBikeFront($request->bike_front, 'shopper');
        }
        if($request->bike_back){
            $document->saveBikeBack($request->bike_back, 'shopper');
        }
        $document->save();

        if($document)
        {
            return redirect()->route('shoppr.details',['id'=>$document->id])->with('success', 'Document has been updated');
        }
        return redirect()->back()->with('error', 'Document update failed');
    }
}

