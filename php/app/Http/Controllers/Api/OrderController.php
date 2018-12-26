<?php

namespace App\Http\Controllers\Api;

use App\Models\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class OrderController extends Controller
{
	/**
	 * 订单列表接口
	 * @author j
	 * @DateTime 2018-07-05T17:17:40+0800
	 * @param    use_id                  用户id
	 * @return   [type]                            [description]
	 */
    public function index(Request $Request)
    {
    	$userId = $Request->user_id;
        $language = $Request->language;
    	if(empty($userId)){
    		return publicReturn(
    			'2',
    			'参数错误'
//                $language
    		);
    	}
      //语言1中文，2英文
    	$data = [
            'user_id' => $userId,
        ];
    	$result = $this->order->orderList($data,$language);
//        p($result);die;
    	if($result == 3){
    		return publicReturn(
    			'3',
    			'无数据'
    		);
    	}else{
    		return publicReturn(
    			'1',
    			'成功',
    			$result
    		);
    	}
    }

	/**
	 * 订单详情接口
	 * @author j
	 * @DateTime 2018-07-05T17:44:45+0800
	 * @param    Request                  $Request [description]
	 * @return   [type]                            [description]
	 */
    public function orderDetails(Request $Request)
    {
    	$orderId = $Request->order_id;
    	$languages = $Request->languagess ? $Request->languagess : 1;
    	if(empty($orderId)){
    		return publicReturn(
    			'2',
    			'参数错误'
    		);
    	}
    	$data = [
            'order.order_id' => $orderId,
            'languages'      => $languages
        ];
        $result = $this->order->orderDetails($data,$languages);
        if(!$result){
        	return publicReturn(
    			'2',
    			'失败'
    		);
        }else{
    		return publicReturn(
    			'1',
    			'成功',
    			$result
    		);
    	}
    }
    /**
     * 软删除订单接口
     * @author j
     * @DateTime 2018-07-06T13:49:41+0800
     * @param    [type]                   $id [description]
     * @return   [type]                       [description]
     */
    public function delOrder(Request $Request)
    {
        $where = [
            'order_id' => $Request->order_id
        ];
        $result = $this->order->delOrders($where);
        if(!$result){
            return publicReturn(
                '2',
                '失败'
            );
        }else{
            return publicReturn(
                '1',
                '删除成功'
            );
        }
    }
    /**
     * 恢复软删除的订单
     * @author j
     * @DateTime 2018-08-14T10:01:19+0800
     * @param    Request                  $Request [description]
     * @return   [type]                            [description]
     */
    public function recoveryOrder(Request $Request)
    {
        $where = [
            'order_id' => $Request->order_id
        ];
        $result = $this->order->recoveryOrder($where);
        if(!$result){
            return publicReturn(
                '2',
                '失败'
            );
        }else{
            return publicReturn(
                '1',
                '恢复成功'
            );
        }
    }
    /**
     *  创建订单接口
     * @author j
     * @DateTime 2018年7月6日11:00:55
     * @param    Request                  $Request [description]
     * @return   [type]                            [description]
     */
    public function creatOrder( Request $request ,Order $order){
        try{
            // 获取到所有的值
            $data = $request->all();
            // 判断参数是否为空
            if($data['user_id']&&$data['c_id']&&$data['h_id']&&$data['r_id']&&$data['goods_id'] && $data['languagess']) {

                $goods = explode(',',$data['goods_id']);
                // 根据商品id 来查询出对应商品详情
                $goodsDetail = $order->findGoods($goods,$data['languagess']);

                // 计算总和
                foreach ($goodsDetail as $key => $value) {
                    $sum[] = $value['present_price'];
                }
                $order_amount = array_sum($sum);

                // 获取到订单地址 根据酒店id 房间id 货柜id拼接起来
                $roomName = $this->common->datasFind(
                    'room',
                    array(
                        'id' => $data['r_id']
                    ),
                    'room_number'
                );

                if($data['languagess']== 2){
                    $hotelName = $this->common->datasFind(
                        'hotel',
                        array(
                            'id' => $data['h_id']
                        ),
                        'name_en'
                    );
                    $order_address = $hotelName->name_en.$roomName->room_number;
                }
                if($data['languagess']== 1){
                    $hotelName = $this->common->datasFind(
                        'hotel',
                        array(
                            'id' => $data['h_id']
                        ),
                        'name'
                    );
                    $order_address = $hotelName->name.$roomName->room_number.'室';

                }

                // 获取到手机号
                $tel = $this->common->datasFind(
                    'users',
                    array(
                        'user_id' => $data['user_id']
                    ),
                    array(
                        'tel'
                    )
                );

                // 获取到入订单主表的数据
                $orderArray = array(
                    'order_number' => orderNum(),                         // 订单编号
                    'user_id' => $data['user_id'],                       // 用户id
                    'c_id' => $data['c_id'],                             // 货柜id
                    'h_id' => $data['h_id'],                              // 酒店id
                    'r_id' => $data['r_id'],                              // 房间id
                    'tel' => $tel->tel,                                   // 手机号
                    'order_amount' => $order_amount,                     // 订单总额
                    'real_amount' => $order_amount,                      // 订单实付金额
                    'order_address' => $order_address,                   // 订单地址信息
                    'creat_time' => time(),                              // 下单时间
                    'created_at' => date('Y-m-d H:i:s',time()),        // 下单时间
                );

                DB::beginTransaction();  // 开启事物
                try {
                    // 入订单主表
                    $orderId = $this->common->addCommon('order',$orderArray);
                    // 根据订单id来查询出订单编号
                    $order_number = $this->common->datasFind(
                        'order',
                        array(
                            'order_id' => $orderId
                        ),
                        'order_number'
                    );
                    // 获取到入订单附表的数据
                    foreach($goodsDetail as $key => $value) {
                        $orderSideArray = array(
                            'order_id' => $orderId,
                            'goods_id' => $value['goods_id'],
                            'goods_price' => $value['present_price'],
                            'goods_img' => $value['main_img'],
                        );
                        // 入订单附表
                        $orderSide = $this->common->addCommon('order_side',$orderSideArray);

//                        // 把货柜商品表里对应的商品状态该为已锁定状态
//                        $updateContainer = $this->common->UpdateCommon(
//                            'container_goods',
//                            array(
//                                'c_id' => $data['c_id'],
//                                'goods_id' => $value['goods_id'],
//                            ),
//                            array(
//                                'inventory_status' => 3
//                            )
//                        );
                    }


                    if( $orderId && $orderSide ) {
//                    if( $orderId && $orderSide ) {
                        DB::commit();
                        return publicReturn(
                            '1',
                            '成功',
                            $order_number->order_number
                        );
                    }
                }catch(\Exception $e) {
                    DB::rollBack();
                    echo 'error';
                }


            }else{
                return publicReturn(
                    '2',
                    '参数不能为空'
                );
            }

        }catch (\Exception $e) {
            echo $e->getMessage();
        }
    }


    /**
     * lj
     * 确认订单
     * @param Request $request
     * @param Common $common
     */
    public function confirmOrder(Request $request,Common $common,Order $order) {
        // 获取到所有的值
        $data = $request->all();
        // 判断参数是否为空
        if(empty($data['user_id']) || empty($data['c_id']) || empty($data['h_id']) || empty($data['r_id']) || empty($data['goods_id']) || empty($data['languagess'])) {
            return publicReturn(
                '2',
                '参数不能为空'
            );
        }

        // 把字符串转化为数组
//        $data['goods_id'] = '2,4,5';
        $goods = explode(',',$data['goods_id']);

        // 根据商品id 来查询出对应商品详情
        $goodsDetail = $order->findGoods($goods,$data['languagess']);

        // 获取到订单地址 根据酒店id 房间id 货柜id拼接起来
        $roomName = $this->common->datasFind(
            'room',
            array(
                'id' => $data['r_id']
            ),
            'room_number'
        );

        if($data['languagess']== 2){
            $hotelName = $this->common->datasFind(
                'hotel',
                array(
                    'id' => $data['h_id']
                ),
                'name_en'
            );
            $order_address = $hotelName->name_en.$roomName->room_number;
        }
        if($data['languagess']== 1){
            $hotelName = $this->common->datasFind(
                'hotel',
                array(
                    'id' => $data['h_id']
                ),
                'name'
            );
            $order_address = $hotelName->name.$roomName->room_number.'室';

        }

        // 计算总和
        foreach ($goodsDetail as $key => $value) {
            $present_price[] = $value['present_price'];
            $original_price[] = $value['original_price'];
        }
//        p($goodsDetail);
        $newGoods = array();
        $newGoods['goodsDetail'] = $goodsDetail;
        $newGoods['order_amount'] = (string)array_sum($present_price);                          // 现价
        $original_prices = (string)array_sum($original_price);                                   // 原价


        // 计算优惠金额
        if($newGoods['order_amount'] == $original_prices) {
            $newGoods['discount_status'] = 1;
        }else {
            $newGoods['discount_status'] = 2;
            $newGoods['discount_price'] = (string)($original_prices - $newGoods['order_amount']);
        }
        $newGoods['orderAddress'] = $order_address;

        //dump($newGoods['discount_price']);die;
        return publicReturn(
            '1',
            '成功',
            $newGoods
        );
    }


    /**
     *  定时任务  一定时间内 未支付订单 改为已取消订单  释放出库存
     * @param Request $request
     */
    public function orderTask( Request $request ,Order $order) {
        $order->checkOrder1();
    }

}
