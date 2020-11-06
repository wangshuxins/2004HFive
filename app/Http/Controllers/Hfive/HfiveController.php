<?php

namespace App\Http\Controllers\Hfive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
class HfiveController extends Controller
{
    public function hfive(){
    if($this->checkSignature()){
          $xml_str = file_get_contents("php://input");
          file_put_contents("wx.log",$xml_str);
    }
  }

    public function checkSignature(){
  
    $signature = $_GET["signature"];

    $timestamp = $_GET["timestamp"];

    $nonce = $_GET["nonce"];
	
    $token = env("WX_Token");

    $tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);
    $tmpStr = implode( $tmpArr );
    $tmpStr = sha1( $tmpStr );
    
    if($tmpStr == $signature ){
        return true;
    }else{
        return false;
    }
  }

    public function assecc_token(){
	  $key = "AccessToken";
	  $get = Redis::get($key);
	  if(!$get){
		  $get = index();
		  Redis::set($key,$get);
		  Redis::expire($key,3600);
	  }

	  echo $get;
  }

}
