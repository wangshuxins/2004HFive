<?php
 function index(){
        $grant_type = env("GRANT_TYPE");
		$appid = env("APP_Id");
		$secret = env("SECRET");
	    $http="https://api.weixin.qq.com/cgi-bin/token?grant_type=".$grant_type."&appid=".$appid."&secret=".$secret;
		$aa = file_get_contents($http);
		$aa = json_decode($aa,true);
	    $array = $aa['access_token'];
		return $array;
	}

	function success($error_msg){
	
	     echo json_encode(['error_no'=>0,'error_msg'=>$error_msg]);
	
	}
    function error($error_msg){
	
	     echo json_encode(['error_no'=>1,'error_msg'=>$error_msg]);exit;
	
    }