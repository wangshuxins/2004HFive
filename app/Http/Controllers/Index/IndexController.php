<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Redis;
class IndexController extends Controller
{
    public function index(){
	   $key = "key"; 
	   $set = Redis::set($key,"456");
	   $get = Redis::get($key);
	   dd($get);
	   $get = DB::table("test")->get()->toArray();
	   dd($get);
	}
}
