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
Route::post("/hfive","Hfive\HfiveController@hfive");
Route::get("/token","Hfive\HfiveController@assecc_token");
Route::get("/index","Index\IndexController@index");