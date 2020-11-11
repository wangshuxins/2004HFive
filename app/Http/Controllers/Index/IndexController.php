<?php

namespace App\Http\Controllers\Index;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Hfive\HfiveController;
use GuzzleHttp\Client;
use App\Model\PWxMedia;
class IndexController extends HfiveController
{
   public function index(){ 


	                           $key = "123456";
							   
							   
							 
						     
						    echo "签到成功您以积累签到".$zincrby."天";
							exit;
   //$pw = PWxMedia::select("mediaid")->where("msgtype","image")->get()->toArray();  
   //$access_token = $this->assecc_token();
   //foreach($pw as $k=>$v){
   //	    $url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$v['mediaid'];
   //		dump($url);
   //}
  
 //exit;
  /*
    $ip="192.168.162.1";
        $durl = 'http://ip.taobao.com/service/getIpInfo.php?ip='.$ip;
    // 初始化
    $curl = curl_init();
    // 设置url路径
    curl_setopt($curl, CURLOPT_URL, $durl);
    // 将 curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true) ;
    // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
    curl_setopt($curl, CURLOPT_BINARYTRANSFER, true) ;
    // 执行
    $data = curl_exec($curl);
    // 关闭连接
    curl_close($curl);
    // 返回数据
    //return $data;   
   dd($data);

$a = "<xml><ToUserName><![CDATA[gh_2bdc7cc9336f]]></ToUserName>
<FromUserName><![CDATA[oM539vl7WgtGfPqbW3nYOTTT6HNQ]]></FromUserName>
<CreateTime>1604991376</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[CLICK]]></Event>
<EventKey><![CDATA[wx_520]]></EventKey>
</xml>";

$a = array_xml($a);
dd($a);
exit;

       
   $city =  urlencode("北京");
 
    $menu =[
	 "button"=>[
       [	
          "type"=>"click",
          "name"=>"今日歌曲",
          "key"=>"wx_2020"
       ],
       [       	
           "type"=>"view",
           "name"=>"今日天气",
           "url"=>"http://apis.juhe.cn/simpleWeather/query?city=".$city."&key=2f3d1615c28f0a5bc54da5082c4c1c0c"
       ],      
      ]
    ];
	*/



     $menu =[
     "button"=>[
     [	
          "type"=>"click",
          "name"=>"北京天气状况",
          "key"=>"wx_520"
      ],
      [
           "name"=>"菜单",
           "sub_button"=>[


            [   
              "type"=>"click",
              "name"=>"签到",
              "key"=>"wx_521"
           ],

           [	
               "type"=>"view",
               "name"=>"搜索",
               "url"=>"http://www.baidu.com/"
            ], 
            [
                 "type"=> "pic_sysphoto", 
                 "name"=> "系统拍照发图", 
                 "key"=> "rselfmenu_1_0", 
                 "sub_button"=> [ ]
             ],
            [
                  "type"=> "pic_photo_or_album", 
                  "name"=> "拍照或者相册发图", 
                  "key"=> "rselfmenu_1_1", 
                  "sub_button"=> [ ]
            ],
            [
             "type"=> "pic_weixin", 
                    "name"=> "微信相册发图", 
                    "key"=> "rselfmenu_1_2", 
                    "sub_button"=> [ ]
			],
       ],
     ],
   ],
];

	$access_token = $this->assecc_token();
    $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
	client_menu($url,$menu);
    exit;


      $key="97523726128a559ff65855dfd1fdd9bc";
                            $url="http://v.juhe.cn/joke/content/list.php?key=".$key."&page=".rand(1,20)."&pagesize=15&sort=desc&time=".time();
                            $res=json_decode($this->http_get($url),true);// 调用的笑话结果  并转化为了数组
							$data = $res["result"]["data"];
							$content = "";
							foreach($data as $k=>$v){
							
							   $content.=$v["content"]."\r\n";
							
							}
							echo $content;


        exit;

	   $a="<xml><ToUserName><![CDATA[gh_2bdc7cc9336f]]></ToUserName>
            <FromUserName><![CDATA[oM539vl7WgtGfPqbW3nYOTTT6HNQ]]></FromUserName>
            <CreateTime>1604919519</CreateTime>
            <MsgType><![CDATA[image]]></MsgType>
            <PicUrl><![CDATA[http://mmbiz.qpic.cn/mmbiz_jpg/7Fqb8H4ib0b5km9x7mdGiccyfF3H3qggWLqyicibgibG7OVLH5CsQNr3uWmmctsvCiayzEnXzMicIicm5mRuzcYjwrHprw/0]]></PicUrl>
            <MsgId>22976919836180757</MsgId>
            <MediaId><![CDATA[KjkAh2uI_nlWCu2swwjcWQFdSp-s7zFd1OZuZpQEEJ2drNFc43vQ6O5C3dENfbkB]]></MediaId>
       </xml>";
	  $array = array_xml($a);
	  dd($array);
	  exit;

	   

	   $data = [
		      "tousername"=>$datat['ToUserName'],  
		      "fromusername"=>$datat['FromUserName'],
		      "createtime"=>$datat['CreateTime'],
		      "msgtype"=>$datat['MsgType'],
		      "picurl"=>$datat['PicUrl'],
		      "msgid" =>$datat['MsgId'],
		      "mediaid"=>$datat['MediaId']
	   ];
       PWxMedia::insert($data);
	   exit;
	  




       
/*
		$access_token = $this->assecc_token();

        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$access_token."&type=image";

		$clent_post = clent_post($url,'aaa.png');
        
		

		exit;

*/		 






	  /*
	    $city =  urlencode("北京");
       $key = "2f3d1615c28f0a5bc54da5082c4c1c0c";
       $url = "http://apis.juhe.cn/simpleWeather/query?city=".$city."&key=".$key;

	  clent_get($url);
	 
	 
	   exit;
	    */
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
