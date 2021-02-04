<?php

namespace App\Http\Controllers\MobileApps\ShopprApp;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;


class NotificationController extends Controller
{
    public function index(Request $request){

        $user=$request->user;

        $notifications=Notification::where('user_type', 'SHOPPR');

        if($user){
            $notifications=$notifications->where(function($query) use($user){
                $query->where('user_id', $user->id)->where('type', 'individual');
            })->orWhere('type','all');

        }else{
            $notifications=$notifications->where('type','all');
        }

        $notifications=$notifications->select('id','title', 'description', 'created_at')
            ->orderBy('id', 'desc')
            ->paginate(1000);

        return [
            'status'=>'success',
            'data'=>compact('notifications')
        ];

    }

}
