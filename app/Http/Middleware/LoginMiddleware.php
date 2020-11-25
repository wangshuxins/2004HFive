<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
class LoginMiddleware
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
       $token = request()->token;

	   $key = "hsah:xcx_token_".$token;

	   $userinfo = Redis::hgetall($key);
	   if($userinfo){
            
	       $_SERVER['user_id'] = $userinfo['user_id'];
		  
	   }else{
	       $response = [
			   
		     'error'=>3000001,
		     'msg' => "未授权"
		   ];
		   die(json_encode($response));
	   
	   }
        return $next($request);
    }
}
