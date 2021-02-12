<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;

class CommissionController extends Controller
{
    public function index(Request $request){

        $datas=Settings::get();
        return view('admin.commission.view',['datas'=>$datas]);
    }

}
