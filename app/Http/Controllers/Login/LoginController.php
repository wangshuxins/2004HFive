<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\WetchLogin;
use Illuminate\Support\Facades\Cookie;
class LoginController extends Controller
{
    public function login(){
	    
	    return view("wetch.login.login");
	
	}
	public function dologin(){
	
	   $wetch_user = request()->wetch_user;

	   $wetch_pwd = request()->wetch_pwd;

	   $rember = [

		 'wetch_user'=>$wetch_user,
		 'wetch_pwd'=>$wetch_pwd

	   ];
	   if(empty($wetch_user)){
	        error("用户名不能为空!"); 
	   }
	    if(empty($wetch_pwd)){
	   
	        error("密码不能为空!"); 
	   }

	  $login=WetchLogin::where('wetch_user',$wetch_user)->first();
	  if(!$login){
	     error("用户名或者密码错误!");
	  }
	  if(password_verify($wetch_pwd,$login['wetch_pwd'])){
		   if(isset(request()->rember)){
                    Cookie::queue('rember',serialize($rember),60*24*7);
            }
		   session(['users'=>$rember]);
           success("登陆成功");
	}else{
	       error("用户名或者密码错误!");
	}
  }
}
