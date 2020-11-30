<?php

namespace App\Http\Controllers\Xcx;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Model\XcxUser;
use App\Model\ShopNav;
use App\Model\ShopCate;
use App\Model\Goods;
use App\Model\Brand;
use App\Model\ShopCart;
use App\Model\Collect;
class XcxController extends Controller
{
	//登陆获取openid存储用户信息
    public function openid(){
        $appid = 'wx38a0b89cb32b272b'; // 小程序APPID
        $secret = '58426119d67e249aa4e4c2f8082db5f0'; // 小程序secret
        $code=$_GET['code'];
        $post = request()->u;
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $appid . '&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';
        $array = json_decode(file_get_contents($url),true);
        if(isset($array['errcode'])){
            $response = [
                'error_no'=>'100001',
                'error_msg'=>'登陆失败',
            ];
        }else{
            if(empty(XcxUser::where("open_id",$array['openid'])->first())){
                $openid = $array['openid'];
				$datas = [
				   'open_id'=>$openid,
				   'nickname'=>$post["nickName"],
					'gender'=>$post['gender'],
					'language'=>$post['language'],
					'city'=>$post['city'],
					'province'=>$post['province'],
					'country'=>$post['country'],
					'avatarUrl'=>$post['avatarUrl'],
					'type'=>3,
					'add_time'=>time(),
				];
                XcxUser::insert($datas);
            }
			 $openid = $array['openid'];
			$redis = [
				   'open_id'=>$openid,
				   'nickname'=>$post["nickName"],
					'gender'=>$post['gender'],
					'language'=>$post['language'],
					'city'=>$post['city'],
					'province'=>$post['province'],
					'country'=>$post['country'],
					'avatarUrl'=>$post['avatarUrl'],
					'type'=>3,
					'add_time'=>time(),
			];
            $token = sha1($array['openid'].$array['session_key'].mt_rand(0,999999));
            $key = "hsah:xcx_token_".$token;
            Redis::hMset($key,$redis);
			//Redis::expire($key,7200);
               $response = [
                   'error_no'=>'0',
                   'error_msg'=>'登陆成功',
                   'token'=>$token
               ];
        }
        return $response;
    }
	//首页幻灯片
    public function navigation(){
		
        $key = "slide";
        $slide = Redis::get($key);
        $slide = unserialize($slide);

        if(empty($slide)){
            $slide  = ShopNav::select("nav_url","nav_id","nav_detail","goods_id")->orderBy("nav_weight","desc")->where("is_show",0)->get()->toArray();
            $slides = serialize($slide);
            Redis::set($key,$slides);
        }
        Redis::expire($key,7200);
        return $slide;
    }
	//导航栏
    public function cate(){
		//Redis::flushall();exit;
        $key = "cate";
        $shopcate = Redis::get($key);
        $shopcate = unserialize($shopcate);
        if(empty($shopcate)) {
            $where = [
                ["is_show", 1],
                ["is_nav_show", 1],
                ["is_del", 1],
                ["parent_id", 0]
            ];
            $shopcate = ShopCate::select("cate_id", "cate_name")->where($where)->get()->toArray();
            $shopcates = serialize($shopcate);
            Redis::set($key,$shopcates);
        }
        Redis::expire($key,7200);
        return $shopcate;
    }
	//商品列表
    public function goods(){
		 if(!request()->get("page")){
            $page = 1;
        }else{
            $page = request()->get("page");
        }
		$cate_id=request()->cate_id;
		
		if($cate_id==0){

		    $key = "key_".$page."_".$cate_id;

			$goods = Redis::get($key);
			$goods = unserialize($goods);
			if(empty($goods)){
			   $goods = Goods::where("shop_goods.is_del", 1)
                ->select("goods_id", "goods_name", "goods_img", "goods_price", "brand_name","goods_store")
                ->leftjoin("shop_brand", "shop_goods.brand_id", "=", "shop_brand.brand_id")
                ->orderBy("shop_goods.goods_id","asc")
                ->paginate(10);
               $goodx = serialize($goods);
			   Redis::set($key,$goodx);
			}
			$response=[
					'data'=>[
					   'list'=>$goods->items()
					]
				];
		     		
		}else{
			$key = "key_".$page."_".$cate_id;
			$goods = Redis::get($key);
			$goods = unserialize($goods);
			if(empty($goods)){
		    $res=ShopCate::select("cate_id")->where("parent_id",$cate_id)->get()->toArray();
			//dd($res);
			$arr=[];
			foreach($res as $k=>$v){
				foreach($v as $l=>$a){
					$arr[]=$a;
				}
			}
            $goods = Goods::where("shop_goods.is_del", 1)
                ->select("goods_id", "goods_name", "goods_img", "goods_price", "brand_name","goods_store")
                ->leftjoin("shop_brand", "shop_goods.brand_id", "=", "shop_brand.brand_id")
                ->orderBy("shop_goods.goods_id","asc")
				 ->whereIn("cate_id",$arr)
                ->paginate(5);
			     $goodx = serialize($goods);
			     Redis::set($key,$goodx);
			 }
				$response=[
					'data'=>[
					   'list'=>$goods->items()
					]
				];
		} 
       return $response;
    }
	//商品详情
    public function detail(){
		
      // Redis::flushall();exit;
        $goods_id = Request()->get("goods_id");
        $key = "detail_".$goods_id;
        $detail = Redis::get($key);
        $detail = unserialize($detail);
        if(empty($detail)){
            $detail =  Goods::select("goods_id","goods_imgs","goods_img","goods_name","goods_price","goods_store","goods_desc")->where("goods_id",$goods_id)->where("is_del",1)->first()->toArray();
            $details = serialize($detail);
            Redis::set($key,$details);
        }
		$detail = [
		    "goods_imgs"=>explode(",",$detail["goods_imgs"]),
			"goods_name"=>$detail["goods_name"],
			"goods_price"=>$detail['goods_price'],
            "goods_store"=>$detail['goods_store'],
            "goods_desc"=>explode(",",$detail["goods_desc"]),
            "goods_id"=>$detail["goods_id"],
			"goods_img"=>$detail["goods_img"]
		];
        return $detail;
    }
	//加入购物车
	public function cart(){

	  $goods_id = request()->goods_id;

	  $goods_totall = request()->goods_totall;

	  $nums = request()->nums;
      
      $user_id=$_SERVER['user_id'];
	 


      	     $tiaojian = ShopCart::where("user_id", $user_id)->where("goods_id", $goods_id)->first();
			 
			//查看之前有没有添加过此商品
			if (!$tiaojian) {
				 $data = [
					"goods_id"=>$goods_id,
					"goods_totall"=>$goods_totall,
					"buy_number"=>$nums,
					"user_id"=>$user_id,
					"add_time"=>time(),
					
	             ];

               $data = ShopCart::insert($data); 
			   if($data){
				   $sum = ShopCart::where("user_id",$user_id)->where("is_del",1)->count();
			       $array = [
					   "error_no"=>'000000',
					   "error_msg"=>"添加成功",
					   "tubiao"=>"success",
					   "sum"=>$sum

				];
			   }
			}else {
				$data = [
					"goods_id"=>$goods_id,
					"goods_totall"=>$goods_totall+$tiaojian->goods_totall,//总价,
					"buy_number"=>$nums+$tiaojian->buy_number,
					"user_id"=>$user_id,
					"add_time"=>time(),
					"is_del"=>1
	             ];
				$sums = Goods::select("goods_store")->where("goods_id",$goods_id)->first()->toArray();
				$goods_sum = ($sums['goods_store']);
				if(empty($sumx = ShopCart::select("buy_number")->where("is_del",1)->where("user_id",$user_id)->where("goods_id", $goods_id)->first()->toArray()){
				
				     $sumx = [
						"buy_number"=>0 
					 ];
				}
				$buy_numberx = ($sumx['buy_number']);
				$goods_count = $nums+$buy_numberx;
				 $sum = ShopCart::where("user_id",$user_id)->where("is_del",1)->count();
				if($goods_count>$goods_sum){
					$array = [
					   "error_no"=>'200001',
					   "error_msg"=>"库存不足",
					   "tubiao"=>"loading",
					   "sum"=>$sum,
					];

					return $array;
	
				}
				$res = ShopCart::where("cart_id", $tiaojian->cart_id)->update($data);

				if($res){
					$sum = ShopCart::where("user_id",$user_id)->where("is_del",1)->count();
					$array = [
						   "error_no"=>'000000',
						   "error_msg"=>"添加成功",
					       "tubiao"=>"success",
						   "sum"=>$sum
					];
				}
			}
			return $array;

	}
	public function shoucang(){

		$goods_id = request()->goods_id;
	
	    $user_ids=$_SERVER['user_id'];

        $user_id = $user_ids."_".$goods_id;

		$key = "ZADD:shoucang_".$user_id;

		$zrange = Redis::zrange($key,0,-1);
		if(empty($zrange)){
		   Collect::insert(['goods_id'=>$goods_id,'user_id'=>$user_ids,'is_collect'=>0,'add_time'=>time()]);
		   Redis::zadd($key,0,$user_id);
		   $array = [
			  "error_no"=>0,
			  "error_msg"=>"已收藏",
		   ];
		   return $array;
		}
		$zscore = Redis::zscore($key,$user_id);

		if($zscore==0){
		  Collect::where("user_id",$user_ids)->where('goods_id',$goods_id)->update(['is_collect'=>1,'add_time'=>time()]);
		   Redis::zadd($key,1,$user_id);
		   $array = [
			  "error_no"=>1,
			  "error_msg"=>"收藏",
		   ];
		
		}else{
		Collect::where("user_id",$user_ids)->where('goods_id',$goods_id)->update(['is_collect'=>0,'add_time'=>time()]);
		   Redis::zadd($key,0,$user_id);
		   $array = [
			  "error_no"=>0,
			  "error_msg"=>"已收藏",
		   ];
		}

		return $array;
	}
	public function catshoucang(){

		$goods_id = request()->goods_id;
	
	    $user_id=$_SERVER['user_id'];

        $user_id = $user_id."_".$goods_id;

		$key = "ZADD:shoucang_".$user_id;

		$zrange = Redis::zrange($key,0,-1);
		if(empty($zrange)){
		   $array = [
			  "error_no"=>1,
			  "error_msg"=>"收藏",
		   ];
		   return $array;
		}
		$zscore = Redis::zscore($key,$user_id);

		if($zscore==0){
		   $array = [
			  "error_no"=>0,
			  "error_msg"=>"已收藏",
		   ];
		
		}else{
		   
		   $array = [
			  "error_no"=>1,
			  "error_msg"=>"收藏",
		   ];
		}

		return $array;
	}
	public function cartlist(){

	   $user_id=$_SERVER['user_id'];
	   
	   $shopcartlist = ShopCart::leftjoin("shop_goods","shop_cart.goods_id","=","shop_goods.goods_id")
		   ->leftjoin("shop_brand","shop_goods.brand_id","=","shop_brand.brand_id")
		   ->where("user_id",$user_id)
		   ->where("shop_cart.is_del","=",1)
		   ->orderBy("shop_cart.add_time","desc")
		   ->get()->toArray();

	   return $shopcartlist;
	   
	}
	//商品数量
	public function cartsum(){
	
	
	   $user_id=$_SERVER['user_id'];

	   $sum = ShopCart::where("user_id",$user_id)->where("is_del",1)->count();

	   $array = [
			  "error_no"=>0,
			  "error_msg"=>"商品数量查询完毕",
		      "sum"=> $sum
		   ];

       return $array;

	}
 
}
