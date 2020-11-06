<?php

namespace App\Http\Controllers\Hfive;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
class HfiveController extends Controller
{
    public function hfive()
    {
        if ($this->checkSignature()) {
            $xml_str = file_get_contents("php://input");
            $data = simplexml_load_string($xml_str, 'SimpleXMLElement', LIBXML_NOCDATA);

            $data = $data->Event;
            if ($data[0] == "subscribe") {
                $openid = $data->FromUserName;
                $access_token = $this->assecc_token();
                $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $access_token . "&openid=" . $openid . "&lang=zh_CN";
                $fens = json_decode($this->http_get($url), true);
                if (isset($fens["errcode"])) {//不为空，说明获取信息失败了
                    $this->writeLog("获取用户信息失败");
                } else {
                    $content = "您好!感谢您的关注";
                }
            } else {
                echo "false";
                exit;
            }
            echo $this->xiaoxi($data, $content);
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

	  echo $get;
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
}
