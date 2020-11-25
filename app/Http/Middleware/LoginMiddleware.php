<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
use App\Model\XcxUser;
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
	   $openid = $userinfo["open_id"];
	   
      $user_id = XcxUser::select("id")->where("open_id",$openid)->get()->toArray();

	  $user_id = $user_id[0]["id"];

	   if($userinfo){

	       $_SERVER['user_id'] = $user_id;
		  
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
