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
       $city =  urlencode("北京");
       $key = "2f3d1615c28f0a5bc54da5082c4c1c0c";
       $url = "http://apis.juhe.cn/simpleWeather/query?city=".$city."&key=".$key;
       $user = json_decode($this->http_get($url), true);//跳方法 用get  方式调第三方类库

       $content = $user['result']['city']."天气情况:"."\r\n"."天气:".$user['result']['realtime']['info']."\r\n"."温度:".$user['result']['realtime']['temperature']."\r\n"."湿度:".$user['result']['realtime']['humidity']."\r\n"."风向:".$user['result']['realtime']['direct']."\r\n"."风力:".$user['result']['realtime']['power']."\r\n"."空气质量:".$user['result']['realtime']['aqi'];
       dd($user['reason']);
       exit;
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
