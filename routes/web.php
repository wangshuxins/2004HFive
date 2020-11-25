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
    Route::post('/openid','Xcx\XcxController@openid');//登陆获取openid存取用户信息
    Route::get("/navigation",'Xcx\XcxController@navigation')->middleware("login");;//商城幻灯片
    Route::get("/cate",'Xcx\XcxController@cate');//导航栏
    Route::get("/goods",'Xcx\XcxController@goods');//商品列表
    Route::get("/detail",'Xcx\XcxController@detail');//商品详情
	Route::get("/cart",'Xcx\XcxController@cart')->middleware("login");//加入购物车
	Route::get("/shoucang",'Xcx\XcxController@shoucang')->middleware("login");
	Route::get("/catshoucang",'Xcx\XcxController@catshoucang')->middleware("login");
});

