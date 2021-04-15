<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\MerchantApplication;
use Illuminate\Http\Request;

class MerchantApplicationController extends Controller
{
    public function index(Request $request){

        $merchants = MerchantApplication::paginate(20);
        return view('admin.merchant-application.view',['merchants'=>$merchants]);
    }

}
