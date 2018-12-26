<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Aliyun\DySDKLite\SignatureHelper as Sms;
use DB;
use Illuminate\Http\Request;
use App\Models\Common;
use App\Models\User;
use App\Models\Goods;
use App\Models\Order;
use App\Models\Container;



class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // 初始化

    public function __construct(Container $container, request $request,Common $common,User $user,Goods $goods,Sms $sms,Order $order){

        $this->request = $request;
        $this->common = $common;    // 调用公共方法
        $this->user = $user;        // 调用用户列表方法
        $this->goods = $goods;      // 调用商品信息方法
        $this->order = $order;          // 订单表
        $this->container = $container;  // 货柜商品表
    }

    /*
    * 	操作提示
    * 	参数：$show_info 你应该输入的内容
    * 		  $url 		 接下来要跳转到哪
    *	登录比较特殊	 参数为两个
    */
    public function show_msg( $show_info='',$url='' ){
        $return = array();
        $return['show_info']=$show_info;
        $return['url']=$url;
        exit( json_encode( $return ) );
    }

}
