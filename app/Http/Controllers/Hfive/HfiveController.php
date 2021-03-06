<?php
namespace App\Http\Controllers\Hfive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Model\User;
use App\Model\PWxMedia;
use App\Model\HistoryModel;
class HfiveController extends Controller
{
    public function hfive()
    {
        if ($this->checkSignature()) {
            $str = file_get_contents("php://input");

			file_put_contents("ddd.txt",$str);
         
            $obj = simplexml_load_string($str, "SimpleXMLElement", LIBXML_NOCDATA);

            switch ($obj->MsgType) {
                case 'event':
                    if ($obj->Event == "subscribe") {
                        //用户扫码的 openID
                        $openid = $obj->FromUserName;//获取发送方的 openid
                        $access_token = $this->assecc_token();//获取token,
                        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
                        //掉接口
                        $user = json_decode($this->http_get($url), true);//跳方法 用get  方式调第三方类库
                        
                        // $this->writeLog($fens);
                        if (isset($user["errcode"])) {
                            file_put_contents("bbb.txt",$user["errcode"]);
                            $this->writeLog("获取用户信息失败");
                        } else {
                            //说明查找成功 //可以加入数据库
                            $first =  User::where("openid",$user['openid'])->first();
                            if($first){
                             $data =[
                                    "subscribe"=>1,
                                    "openid"=>$user["openid"],
                                    "nickname"=>$user["nickname"],
                                    "sex"=>$user["sex"],
                                    "city"=>$user["city"],
                                    "country"=>$user["country"],
                                    "province"=>$user["province"],
                                    "language"=>$user["language"],
                                    "headimgurl"=>$user["headimgurl"],
                                    "subscribe_time"=>$user["subscribe_time"],
                                    "subscribe_scene"=>$user["subscribe_scene"],
                              ];

                                     User::where("openid",$user['openid'])->update($data);
                                     $content ="欢迎回来";
                             }else{
                             $users = new User();
                             $data =[
                                    "subscribe"=>$user["subscribe"],
                                    "openid"=>$user["openid"],
                                    "nickname"=>$user["nickname"],
                                    "sex"=>$user["sex"],
                                    "city"=>$user["city"],
                                    "country"=>$user["country"],
                                    "province"=>$user["province"],
                                    "language"=>$user["language"],
                                    "headimgurl"=>$user["headimgurl"],
                                    "subscribe_time"=>$user["subscribe_time"],
                                    "subscribe_scene"=>$user["subscribe_scene"],
                              ];
                            $users->insert($data);
                            $content = "您好!感谢您的关注";
                          }
                        }
                    }
                    if ($obj->Event == "unsubscribe") {
                          //用户扫码的 openID
                        $openid = $obj->FromUserName;//获取发送方的 openid
                        $access_token = $this->assecc_token();//获取token,
                        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
                        //掉接口
                        $user = json_decode($this->http_get($url), true);//跳方法 用get  方式调第三方类库
                        User::where("openid",$user['openid'])->update(['subscribe'=>0]);
                        $content = "取消关注成功,期待您下次关注";
                    }
					if($obj->Event == "pic_photo_or_album"){
					
					   $content = "";
					}
					if($obj->Event == "pic_weixin"){
					
					   $content = "";
					}
					if($obj->Event == "VIEW"){
					
					   $content = "";
					}
					
					 if ($obj->Event == "CLICK") {

						 if($obj->EventKey=="wx_521"){
                              $key = $obj->FromUserName;
							  $times = date("Y-m-d",time());
                              $date = Redis::zrange($key,0,-1);
							  if($date){
							      $date = $date[0];
							  }
							  
						       if($date==$times){   
									 $content = "您今日已经签到过了!";
								 }else{
									 $zcard = Redis::zcard($key);
									 if($zcard>=1){
										 Redis::zremrangebyrank($key,0,0);
									}
									 $keys = array_xml($str);
                                     $keys = $keys['FromUserName'];
									 $zincrby = Redis::zincrby($key,1,$keys);
							         $zadd = Redis::zadd($key,$zincrby,$times);
									 
	                                 $score = Redis::incrby($keys."_score",100);
	                             
					            	 $content="签到成功您以积累签到".$zincrby."天!"."您以积累获得".$score."积分";  
							   }
						 }else{
						 
						    $city =  urlencode("北京");
                            $key = "2f3d1615c28f0a5bc54da5082c4c1c0c";
                            $url = "http://apis.juhe.cn/simpleWeather/query?city=".$city."&key=".$key;
                            $user = json_decode($this->http_get($url), true);//跳方法 用get  方式调第三方类库
                            if($user['reason']=="查询成功!"){
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
                         } 
					  }
                    }
                    break;
                    case 'text':

						$key = "284fc0755b050a79ab2895c9a5566588";
						$text = $obj->Content;

						$url = "http://api.tianapi.com/txapi/pinyin/index?key=".$key."&text=".$text;

						$contents = json_decode(file_get_contents($url),true);

						if($contents["code"]=='200'){
						
						  $content = $contents['newslist'][0]['pinyin'];
						}
						$touser = $obj->FromUserName;
						$data = [
						   "touser"=>$touser,
						   'contents'=>$content,
						   'time'=>time($data)
						];
                        HistoryModel::insert($data);
						/*
                        if ($obj->Content == "天气") {
                            $content = "您好,请输入您想查询的您的地区的天气，比如:'北京'";
                        }else{
                            $city =  urlencode($obj->Content);
                            $key = "2f3d1615c28f0a5bc54da5082c4c1c0c";
                            $url = "http://apis.juhe.cn/simpleWeather/query?city=".$city."&key=".$key;
                            $user = json_decode($this->http_get($url), true);//跳方法 用get  方式调第三方类库
                            if($user['reason']=="查询成功!"){
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
                            }else{
                               
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


								$datas = json_decode($this->curl($url,$data),true);

								$content = $datas['results'][0]['values']['text'];

                            }
                        }
                        */
                    break;
					case "voice":
					
					  $apiKey="3537d051f0ec483e86f81fbc8689ec9d";
	                  $perception = $obj->Recognition;
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
								$datas = json_decode($this->curl($url,$data),true);

                                 $access_token = $this->assecc_token();
					             $url="https://api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$obj->MediaId;
					             $get = file_get_contents($url);
					             file_put_contents("voice.amr",$get);


								$content = $datas['results'][0]['values']['text'];

                    
				  
				break;
				case "image":
				    
				    			$data = [
		                              "tousername"=>$obj->ToUserName,  
									  "fromusername"=>$obj->FromUserName,
									  "msgtype"=>$obj->MsgType,
									  "content"=>$obj->Content,
									  "msgid" =>$obj->MsgId,
									  "createtime"=>$obj->CreateTime,
									  "mediaid"=>$obj->MediaId,
									  "format"=>$obj->Format,
									  "recognition"=>$obj->Recognition,
									  "picurl"=>$obj->PicUrl,
									  "event"=>$obj->Event,
									  "eventkey"=>$obj->EventKey
	                              ];
					$access_token = $this->assecc_token();
                    PWxMedia::insert($data);
                    $url="https://api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$obj->MediaId;
					$get = file_get_contents($url);
					file_put_contents("image.jpg",$get);
				    $content ="此功能暂时还未开放，您可以发消息与图灵机器人'小柯'进行交流或者输入'天气'查询某地区的天气状况，更多功能正在火速进行中，尽请期待。。。";
				break;
				case "video":
					$access_token = $this->assecc_token();
					$url="https://api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$obj->MediaId;
					$get = file_get_contents($url);
					file_put_contents("video.mp4",$get);
				    $content ="此功能暂时还未开放，您可以发消息与图灵机器人'小柯'进行交流或者输入'天气'查询某地区的天气状况，更多功能正在火速进行中，尽请期待。。。";

				break;
				default:
                 $content="表达式的值不等于 label1 及 label2 时执行的代码";
            }
            
            echo $this->xiaoxi($obj, $content);
        }
    }
    public function checkSignature(){
    $signature = $_GET["signature"];

    $timestamp = $_GET["timestamp"];

    $nonce = $_GET["nonce"];
	
    $token = env("WX_Token");

    $tmpArr = array($token, $timestamp, $nonce);
    sort($tmpArr, SORT_STRING);
    $tmpStr = implode( $tmpArr );
    $tmpStr = sha1( $tmpStr );
    
    if($tmpStr == $signature ){
        return true;
    }else{
        return false;
    }
  }
    public function assecc_token(){
	
	  $key = "AccessToken";
	  $get = Redis::get($key);
	  if(!$get){
		  $get = index();
		  Redis::set($key,$get);
		  Redis::expire($key,3600);
	  }
	  return $get;
  }
    private  function writeLog($data){
        if(is_object($data) ||is_array($data)){
            $data=json_encode($data);
        }
        file_put_contents("aaa.txt",$data);die;
    }
    function xiaoxi($obj,$content){
        //我们可以恢复一个文本|图片|视图|音乐|图文列如文本
        //接收方账号
        $toUserName=$obj->FromUserName;
        //开发者微信号
        $fromUserName=$obj->ToUserName;
        //时间戳
        $time=time();
        //返回类型
        $msgType="text";

        $xml = "<xml>
                      <ToUserName><![CDATA[%s]]></ToUserName>
                      <FromUserName><![CDATA[%s]]></FromUserName>
                      <CreateTime>%s</CreateTime>
                      <MsgType><![CDATA[%s]]></MsgType>
                      <Content><![CDATA[%s]]></Content>
                    </xml>";
        //替换掉上面的参数用 sprintf
        echo sprintf($xml,$toUserName,$fromUserName,$time,$msgType,$content);
    }
    function http_get($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);//向那个url地址上面发送
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);//设置发送http请求时需不需要证书
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置发送成功后要不要输出1 不输出，0输出
        $output = curl_exec($ch);//执行
        curl_close($ch);    //关闭
        return $output;
    }
    //过滤https请求
    public function curl($url,$menu){
        //1.初始化
        $ch = curl_init();
        //2.设置
        curl_setopt($ch,CURLOPT_URL,$url);//设置提交地址
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);//设置返回值返回字符串
        curl_setopt($ch,CURLOPT_POST,1);//post提交方式
        curl_setopt($ch,CURLOPT_POSTFIELDS,$menu);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        //3.执行
        $output = curl_exec($ch);
        //关闭
        curl_close($ch);
        return $output;
    }
}
