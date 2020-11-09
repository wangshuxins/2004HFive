<?php

namespace App\Http\Middleware;

use Closure;

class WetchLogin
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
		$users = session('users');
        if(!$users){
            $cookie_login = Request()->cookie('rember');
            //dd($cookie_admin);
            if($cookie_login){
                //echo 'cookie';
                session(['users'=>unserialize($cookie_login)]);
            }else{
                return redirect('/login');
            }
        }
		return $next($request);
        
    }
}
