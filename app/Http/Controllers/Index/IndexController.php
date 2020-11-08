<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Hfive\HfiveController;
class IndexController extends HfiveController
{
   public function index(){
       urlencode
       $a="<xml><ToUserName><![CDATA[gh_2bdc7cc9336f]]></ToUserName>
              <FromUserName><![CDATA[oM539vhyM4XQe1cp194eOWPJZl6M]]></FromUserName>
              <CreateTime>1604716746</CreateTime>
            <MsgType><![CDATA[event]]></MsgType>
            <Event><![CDATA[subscribe]]></Event>
            <EventKey><![CDATA[]]></EventKey></xml>";
       $obj = simplexml_load_string($a, "SimpleXMLElement", LIBXML_NOCDATA);
       dd($obj);
       $openid = $obj->FromUserName;
       $access_token = $this->assecc_token();//获取token,
       $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
       //掉接口
       $user = json_decode($this->http_get($url), true);

       dd($user);


   }
}
