<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CustomerAuth
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
        if(Auth::guard('customer-api')->check() && Auth::guard('customer-api')->user()->status==1){
            return $next($request);
        }
        else{
            
            $responseData=[
                'errMsgFlag'=>true,
                'msgFlag'=>false,
                'msg'=>null,
                'errMsg'=>'Unauthorized Access.Please Try Again.',
            ];

            return response()->json($responseData,401);
        }
    }
}
