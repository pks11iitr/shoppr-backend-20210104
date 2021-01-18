<?php

namespace App\Http\Middleware;

use Closure;

class ShopprApiAuth
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
        $user=auth()->guard('shopperapi')->user();
        if(!$user)
            return response()->json([
                'status'=>'failed',
                'message'=>'Please login to continue'
            ], 200);

        $request->merge(compact('user'));
        return $next($request);
    }
}
