<?php

namespace App\Http\Middleware;

use Closure;

class CustomerApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user=auth()->guard('customerapi')->user();
        if(!$user)
            return response()->json([
                'status'=>'failed',
                'message'=>'Please login to continue'
            ], 200);

        if($user->notification_token==null)
            return response()->json([
                'status'=>'failed',
                'message'=>'logout'
            ], 200);

        $request->merge(compact('user'));
        return $next($request);
    }
}
