<?php

namespace App\Http\Controllers\Hfive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HfiveController extends Controller
{
    public function hfive(){

    $echoStr = $_GET["echostr"];

    if($this->checkSignature()){

       echo $echoStr;

       exit;
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
}
