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
 Route::post('/xcx/openid','Xcx\XcxController@openid');//登陆获取openid存取用户信息
Route::prefix('/xcx')->group(function(){
    Route::get("/navigation",'Xcx\XcxController@navigation');//商城幻灯片
    Route::get("/cate",'Xcx\XcxController@cate');//导航栏
    Route::get("/goods",'Xcx\XcxController@goods');//商品列表
    Route::get("/detail",'Xcx\XcxController@detail');//商品详情
	Route::get("/cart",'Xcx\XcxController@cart')->middleware("login");//加入购物车
	Route::get("/shoucang",'Xcx\XcxController@shoucang')->middleware("login");;//收藏;
	Route::get("/catshoucang",'Xcx\XcxController@catshoucang')->middleware("login");//加载收藏;
	Route::get("/cartlist",'Xcx\XcxController@cartlist')->middleware("login");//购物车列表
	Route::get("/cartsum",'Xcx\XcxController@cartsum')->middleware("login");//商品数量
	Route::get("/counts",'Xcx\XcxController@counts')->middleware("login");//商品个数
	Route::get("/addlist",'Xcx\XcxController@addlist')->middleware("login");//商品添加
	Route::any("/deletes",'Xcx\XcxController@deletes')->middleware("login");//删除购物车列表
	Route::any("/input",'Xcx\XcxController@input')->middleware("login");//购物车input框
	Route::any("/settleaccount",'Xcx\XcxController@settleaccount')->middleware("login");//确认结算
});

