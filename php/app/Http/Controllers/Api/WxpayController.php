<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class WxpayController extends Controller
{
    /**
     *  lj
     *  微信支付
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orderPay( Request $request ) {
        // 用户id  用户订单号  金钱 openid
        header('content-type:text/html;charset=utf-8');
        $config = config('alisms');
        //微信下单
        $body = '梦马mmstore';
//        $notify_url = $config['url'].'/api/Wxpay/notify';
        $notify_url = 'https://www.mmestore.com/api/Wxpay/notify';
        $data = $request->all();
        if($data['order_number'] && $data['price']){

		    //优惠卷状态为1的情况下 满足优惠的条件
//            if($data['use_status']==1){
//                $discount=array(
//                    'discount_amount'=>$data['discount_price']+$data['discount_money'],
//                    'real_amount'=>$data['price'],
//                );
//                DB::table('order')->where(array('order_number'=>$data['order_number']))->update($discount);
//
//            }
            // 根据订单id来查询该订单下的货柜id和商品id来  从货柜商品表里查询该商品是否已售罄
            //先根据订单是否有该条订单   查出订单id和货柜id
            $orderInfo = $this->order->findOrder(
                array(
                    'order_number' => $data['order_number']
                ),
                array(
                    'order_number',
                    'real_amount',
                    'order_id',
                    'c_id',
                    'user_id'
                )
            );
            // 根据订单id来查询订单对应的商品
            $goodsId = $this->common->datas(
                'order_side',
                array(
                    'order_id' => $orderInfo->order_id
                ),
                array(
                    'goods_id'
                )
            );
            $goodsIds = array_column($goodsId,'goods_id');

            // 判断货柜商品里 该商品是否已经售罄
            $isContainer = object_array($this->order->isContainer(
                $goodsIds,
                array(
                    'inventory_status' => 1,
                    'c_id' => $orderInfo->c_id
                ),
                'goods_id'
            ));
            $isContainerIds = array_column($isContainer,'goods_id');
            $isSuccess = array_diff($goodsIds,$isContainerIds);
            // 如果数组为空则是通过 有数据则是有 售罄商品
            if($isSuccess) {
                // 先清空一下购物车
                $where['user_id'] = $orderInfo->user_id;
                $where['c_id'] = $orderInfo->c_id;
                $res =  DB::table('shop_car')->where($where)->delete();
                return json_encode(['code'=>5,'data'=>null,'msg'=>'有已售罄商品']);
            }else {
                if($orderInfo) {
                    if($orderInfo->real_amount == $data['price']){
                        //更改订单状态为已取消 --开始
                        $updata['status'] =  3;
                        $this->common->UpdateCommon('order',array( 'order_number' => $data['order_number']),$updata);
                        //更改订单状态为已取消 --结束
// 					$data['price'] = 0.01*100;
                        $data['price'] = $orderInfo->real_amount * 100;
                        $input = new \WxPayUnifiedOrder();
                        $tools = new \JsApiPay();
                        $input->SetBody($body);  //设置商品或支付单简要描述
//                    $input->SetAttach('');    //设置附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据
                        $input->SetOut_trade_no($data['order_number']);  //设置商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号
                        $input->SetTotal_fee($data['price']);  //设置订单总金额，只能为整数，详见支付金额
                        $input->SetTime_start(date("YmdHis"));  //设置订单生成时间，格式为yyyyMMddHHmmss
                        $input->SetTime_expire(date("YmdHis", time() + 60*10));    //设置订单失效时间，格式为yyyyMMddHHmmss
//                    $input->SetGoods_tag("tag");  //设置商品标记，代金券或立减优惠功能的参数，说明详见代金券或立减优惠
                        $input->SetNotify_url($notify_url);  //设置接收微信支付异步通知回调地址
                        $input->SetTrade_type("JSAPI");  //设置类型如下：JSAPI，NATIVE，APP
                        $input->SetOpenid($data['open_id']);
                        $inputs = new \WxPayApi();
                        $order_data = $inputs::unifiedOrder($input);  //统一下单
//                    p($order_data);die;
                        if ($order_data['return_code'] == 'FAIL') {
                            return json_encode(['code'=>5,'data'=>null,'msg'=>$order_data['return_msg']]);
                        } else {
                            $jsApiParameters = $tools->GetJsApiParameters($order_data);
                            $order_result = json_decode($jsApiParameters);
//                        p($order_result);die;
                            return json_encode(['code'=>1,'data'=>$order_result,'msg'=>'成功']);
                        }
                    }else{
                        return json_encode(['code'=>3,'data'=>null,'msg'=>'支付金额有误']);
                    }
                }else{
                    return json_encode(['code'=>2,'data'=>null,'msg'=>'该订单号不存在']);
                }
            }
        }else{
            return json_encode(['code'=>4,'data'=>null,'msg'=>'参数错误']);
        }
    }

    /**
     *
     * lj
     *  支付回调地址
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function notify( Request $request ) {


        $xml = file_get_contents('php://input');
        $array = $this->xmlToArray($xml);

//        $array['out_trade_no'] = 'MM2018082350989997';
//        $array['appid'] = 'wxa032c3a1803ad256';

        // 根据订单号查看订单数据
        $orderFind = $this->common->datasFind(
            'order',
            array(
                'order_number' => $array['out_trade_no'],
                'status' => 3
            )
        );


        $wxpayconfig = new \WxPayConfig();
        $app_id = $wxpayconfig::APPID;

        // 判断订单的支付状态及订单号是否对应、oppen_id是否一样
        if(!empty($orderFind->order_number) && $orderFind->status == 3 && $array['appid'] == $app_id) {
            // 根据订单主表id和订单附表id关联查询有几个商品
            // 查询购买的商品列表
            $filed = array('o.num','o.goods_price','o.goods_id','r.c_id','r.h_id','r.r_id');
            $where['o.order_id'] = $orderFind->order_id;
            $list = $this->order->orderSide($where,$filed);
//dump($list);
            DB::beginTransaction();  // 开启事物
            // 修改货柜商品表的商品状态
            $goods_ids = array_column($list, 'goods_id');
        //    dump($goods_ids);
            $updateGoods = $this->container->goodsContainerUpdata(
                array('c_id' => $list[0]->c_id),
                $goods_ids,
                array('inventory_status' => 2)
            );
          //  dump($updateGoods);
            // 清空购物车
           // $where2['user_id'] = $orderFind->user_id;
           // $where2['c_id'] = $orderFind->c_id;
           // $res =  DB::table('shop_car')->where($where2)->delete();
            //dump($res);
            // 修改订单表状态
            $orderStatus = $this->common->UpdateCommon(
                'order',
                array(
                    'order_number' =>$orderFind->order_number
                ),
                array(
                    'status' => 2,
                    'pay_time' => time()
                )
            );

	    $where2['user_id'] = $orderFind->user_id;
            $where2['c_id'] = $orderFind->c_id;
            $goodsInfo=object_array(DB::table("shop_car")->whereIn('goods_id',$goods_ids)->where($where2)->get()->toArray());
            if(empty($goodsInfo)){
                $result=1;
            }else{
                $res =  DB::table('shop_car')->where($where2)->delete();
                $result=1;
            }
          //  dump($orderStatus);
            if($updateGoods && $orderStatus && $result==1) {
                DB::commit();
                echo "<xml>
	                     <return_code><![CDATA[SUCCESS]]></return_code>
	                     <return_msg><![CDATA[OK]]></return_msg>
	                 </xml>";
                die;
            }else {
                DB::rollback();
                echo "<xml>
	                    <return_code><![CDATA[FAIL]]></return_code>
	                      <return_msg><![CDATA[ERROR]]></return_msg>
	                  </xml>";
                die;
            }

        }else {
            echo "<xml>
                <return_code><![CDATA[FAIL]]></return_code>
                <return_msg><![CDATA[ERROR]]></return_msg>
            </xml>";
            die;
//            return publicReturn(
//                '2',
//                '订单已失效'
//            );
        }
    }

    //xml转数组.
    private function xmlToArray($xml) {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }


    /**
     * lj
     *  判断是否支付成功
     * @param Request $request
     */
    public function isPaySuccess( Request $request ) {
        $data = $request->all();
        // 根据订单编号查询改订单是否支付成功
        $order = $this->common->datasFind(
            'order',
            array(
                'order_number' => $data['order_number'],
                'status' => 2,
            ),
            'order_id'
        );
        // 如果存在则说明支付成功
        if($order) {
            return publicReturn(
                '1',
                '支付成功'
            );
        }else {
            return publicReturn(
                '2',
                '支付失败'
            );
        }

    }

}
