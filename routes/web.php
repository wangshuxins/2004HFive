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
Route::get("/login","Login\LoginController@login");;
Route::post("/dologin","Login\LoginController@dologin");

//练习
Route::get("/index","Index\IndexController@index");
Route::any("/test","Test\TestController@test");
Route::any("/list","Test\TestController@list")->MIddleware("wetchlogin");
Route::get("/admins","Admin\AdminController@admins");
Route::get("/menu","Admin\AdminController@menu");
Route::get("/menu/list","Admin\AdminController@list");

//小程序
Route::prefix('/xcx')->group(function(){
    Route::get('/','Xcx\XcxController@xcx');
    Route::post('/openid','Xcx\XcxController@openid');
    Route::get("/navigation",'Xcx\XcxController@navigation');
    Route::get("/cate",'Xcx\XcxController@cate');
    Route::get("/goods",'Xcx\XcxController@goods');
    Route::get("/detail",'Xcx\XcxController@detail');
});

