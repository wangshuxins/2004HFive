<?php

namespace App\Http\Controllers\Hfive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HfiveController extends Controller
{
    public function hfive(){

    $timestamp = time();

    $nonce = rand();

    $token = 'hfive';

    $tmpArr = array($token, $timestamp, $nonce);

    sort($tmpArr, SORT_STRING);

    $tmpStr = implode( $tmpArr );

    $tmpStr = sha1( $tmpStr );
    
    $signature = $tmpStr;

    if( $tmpStr == $signature ){
        echo "12";
    }else{
        echo "34";
    }
  }
}
