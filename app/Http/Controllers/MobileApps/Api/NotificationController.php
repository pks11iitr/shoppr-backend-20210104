<?php

namespace App\Http\Controllers\MobileApps\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request){

        $user=auth()->guard('customerapi')->user();

        Notification::where('user_type', 'CUSTOMER')
            ->where('user_id', $user->id??0)
            ->update(['seen_at'=> date('Y-m-d H:i:s')]);

        $notifications=Notification::where('user_type', 'CUSTOMER');

        if($user){
            $notifications=$notifications->where(function($query) use($user){
                $query->where('user_id', $user->id)
                    ->where('type', 'individual');
            })
                ->orWhere('type','all');

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
