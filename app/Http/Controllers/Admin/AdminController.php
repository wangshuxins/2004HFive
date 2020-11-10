<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function admins(){
	   
	   return view("wetch.admin.admin");
	
	}
	 public function menu(){
	   
	   return view("wetch.admin.menu");
	
	}
	 public function list(){
	   
	   return view("wetch.admin.list");
	
	}
}
