<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(){
	  //dd($_POST);
	  $bb = file_get_contents("php://input");
	 $obj = simplexml_load_string($bb,"SimpleXMLElement", LIBXML_NOCDATA);
	 $obj = json_encode($obj,true);
	 $obj = json_decode($obj,true);
	 dd($obj);
	}
	public function list(){
	   
	   echo "列表";
	
	}
}
