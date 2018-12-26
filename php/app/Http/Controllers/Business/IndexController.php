<?php

namespace App\Http\Controllers\Business;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdminMenu;
use App\Models\Common;
use App\Models\Order;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AdminMenu $AdminMenu,Common $common){


        // 根据酒店id来查询出哪个酒店
        $hotelName = $common->datasFind(
            'hotel',
            array(
                'id' => session('hotel_id')
            ),
            'name'
        );
        return view('business.index.index',['hotelName'=>$hotelName->name]);
    }

    //首页信息展示
    public function main(Order $order){
        // 酒店id
        $hotelId = session('hotel_id');

        // where 条件
        $where = array(
            'h_id' => $hotelId,
            'status' => 2,
        );

        // 今日销售金额 和 有效订单
        $todayOrders = $order->hotelTodayOrder($where);

        // 本周销售金额 和 有效订单
        $weekOrders = $order->hotelWeekOrder($where);

        // 本月销售金额 和 有效订单
        $monthOrders = $order->hotelMonthOrder($where);

        return view('business.index.main',array('todayOrder'=>$todayOrders,'weekOrder'=>$weekOrders,'monthOrder'=>$monthOrders));
    }

    /**
     *  lj
     *  订单量   折线图
     * @param Request $request
     * @param Order $order
     */
    public function orderCharts( Request $request,Order $order) {
        $token = $request -> except('_token');
        // 接受值
        $data = $request->all();
        // 酒店id
        $hotelId = session('hotel_id');
        // where 条件
        $where = array(
            array(
                'h_id',$hotelId
            ),
            array(
                'status','!=','0'
            )
        );

        if($data['type'] == 1 || $data['type'] == 3) {
            $orderRank = $order->hotelOrderCharts($data['type'],$where);
//            p($orderRank);die;
            foreach($orderRank as $key => $value) {
                $newOrderRank['name'][] =  $key;
                $newOrderRank['value'][] = $value;
            }
//            p($newOrderRank);
        }elseif($data['type'] == 2) {
            $orderRank = $order->hotelOrderCharts($data['type'],$where);
            foreach($orderRank as $key => $value) {
                $newOrderRank['name'][] =  $key;
                $newOrderRank['value'][] = $value['value'];
//                p($newOrderRank);
//                $newOrderRank['value'][] = $value;
            }
        }
        echo json_encode($newOrderRank);

    }


    /**
     *  lj
     *  销售金额   折线图
     * @param Request $request
     * @param Order $order
     */
    public function priceCharts( Request $request,Order $order) {
        $token = $request -> except('_token');
        // 接受值
        $data = $request->all();
        // 酒店id
        $hotelId = session('hotel_id');
        // where 条件
        $where = array(
            'h_id' => $hotelId,
            'status' => 2,
        );
        if($data['type'] == 1 || $data['type'] == 3) {
            $orderRank = $order->hotelSalesCharts($data['type'],$where);
//        p($orderRank);die;
            foreach($orderRank as $key => $value) {
                $newOrderRank['name'][] =  $key;
                $newOrderRank['value'][] = $value;
            }
        }elseif($data['type'] == 2) {
            $orderRank = $order->hotelSalesCharts($data['type'],$where);
//            p($orderRank);
            foreach($orderRank as $key => $value) {
                $newOrderRank['name'][] =  $key;
//                $newOrderRank['value'][] = $value['value'];
                $newOrderRank['value'][] = $value;
            }
//            p($newOrderRank);die;
        }
        echo json_encode($newOrderRank);
    }

}
