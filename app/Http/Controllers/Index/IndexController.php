<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Hfive\HfiveController;
use GuzzleHttp\Client;
class IndexController extends HfiveController
{
   public function index(){
	   $client = new Client();
	    $city =  urlencode("北京");
       $key = "2f3d1615c28f0a5bc54da5082c4c1c0c";
       $url = "http://apis.juhe.cn/simpleWeather/query?city=".$city."&key=".$key;
        $response = $client->request("GET",$url);
		$json_str = $response->getBody();
		echo $json_str;
	   exit;
       $a="<xml><ToUserName><![CDATA[gh_2bdc7cc9336f]]></ToUserName>
              <FromUserName><![CDATA[oM539vhyM4XQe1cp194eOWPJZl6M]]></FromUserName>
              <CreateTime>1604716746</CreateTime>
            <MsgType><![CDATA[event]]></MsgType>
            <Event><![CDATA[subscribe]]></Event>
            <EventKey><![CDATA[]]></EventKey></xml>";
       $obj = simplexml_load_string($a, "SimpleXMLElement", LIBXML_NOCDATA);
	    $apiKey="3537d051f0ec483e86f81fbc8689ec9d";
	   $perception = $obj->Content;
	   
		 $url = "http://openapi.tuling123.com/openapi/api/v2";

                               $data  = [
									"reqType"=>2,
                                    'perception'=>[
                                        'inputText'=>[
								      
                                            'inputMedia'=>'hello'
                                        ],
                                    ],
                                    'userInfo'=>[
                                        'apiKey'=>$apiKey,
                                        'userId'=>'520',
                                    ],
                                ];
                                $data = json_encode($data);
								 $aa = $this->curl($url,$data);
								dd($aa);
								exit;





       $apiKey="3537d051f0ec483e86f81fbc8689ec9d";
       $perception = $obj->Content;
       $url = "http://openapi.tuling123.com/openapi/api/v2";
       $data  = [
               'perception'=>[
                   'inputText'=>[
                       'text'=>$perception
                   ],
               ],
                 'userInfo'=>[
                    'apiKey'=>$apiKey,
                    'userId'=>'520',
               ],
       ];
       $data = json_encode($data);


      $aa = $this->curl($url,$data);

	  $aa = json_decode($aa,true);


dd($aa['results'][0]['values']['text']);
       exit;
       $city =  urlencode("北京");
       $key = "2f3d1615c28f0a5bc54da5082c4c1c0c";
       $url = "http://apis.juhe.cn/simpleWeather/query?city=".$city."&key=".$key;
       $user = json_decode($this->http_get($url), true);//跳方法 用get  方式调第三方类库
       $content = $user['result']['city']."天气情况:".
           "\r\n"."天气:".$user['result']['realtime']['info'].
           "\r\n"."温度:".$user['result']['realtime']['temperature'].
           "\r\n"."湿度:".$user['result']['realtime']['humidity'].
           "\r\n"."风向:".$user['result']['realtime']['direct'].
           "\r\n"."风力:".$user['result']['realtime']['power'].
           "\r\n"."空气质量:".$user['result']['realtime']['aqi'].
           "\r\n"."近五天天气情况如下:".
           "\r\n".$user['result']['future'][0]['date'].":".
           "\r\n"."天气:".$user['result']['future'][0]['weather'].
           "\r\n"."温度:".$user['result']['future'][0]['temperature'].
           "\r\n"."风向:".$user['result']['future'][0]['direct'].
           "\r\n".$user['result']['future'][1]['date'].":".
           "\r\n"."天气:".$user['result']['future'][1]['weather'].
           "\r\n"."温度:".$user['result']['future'][1]['temperature'].
           "\r\n"."风向:".$user['result']['future'][1]['direct'].
           "\r\n".$user['result']['future'][2]['date'].":".
           "\r\n"."天气:".$user['result']['future'][2]['weather'].
           "\r\n"."温度:".$user['result']['future'][2]['temperature'].
           "\r\n"."风向:".$user['result']['future'][2]['direct'].
           "\r\n".$user['result']['future'][3]['date'].":".
           "\r\n"."天气:".$user['result']['future'][3]['weather'].
           "\r\n"."温度:".$user['result']['future'][3]['temperature'].
           "\r\n"."风向:".$user['result']['future'][3]['direct'].
           "\r\n".$user['result']['future'][4]['date'].":".
           "\r\n"."天气:".$user['result']['future'][4]['weather'].
           "\r\n"."温度:".$user['result']['future'][4]['temperature'].
           "\r\n"."风向:".$user['result']['future'][4]['direct'];
           dd($content);
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
