<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
//微信
Route::post("/hfive","Hfive\HfiveController@hfive");
Route::get("/token","Hfive\HfiveController@assecc_token");


//登陆
Route::get("/login","Login\LoginController@login");
Route::post("/dologin","Login\LoginController@dologin");

//练习
Route::get("/index","Index\IndexController@index");
Route::any("/test","Test\TestController@test");
Route::any("/list","Test\TestController@list")->MIddleware("wetchlogin");

