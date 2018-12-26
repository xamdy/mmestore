<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Models\AdminMenu;
use App\Models\Order;


class IndexController extends CommonController
{
	// public function __construct(AdminMenu $AdminMenu){
	// 	 parent::__construct($AdminMenu);
	// }
	public function index(AdminMenu $AdminMenu) {
		$menus = $AdminMenu->menuTree(0);

		// $menus = $AdminMenu->trees(0);
//		 print('<pre>');
//		 print_r($menus);die;
		// echo session('ADMIN_ID');die;
    	return view('admin.index.index',['menus'=>$menus,'user'=>session('name')]);
	}


	// 首页信息展示
	public function main(Order $order) {
        // 今日销售金额 和 有效订单
        $todayOrders = $order->todayOrder();
        // 本周销售金额 和 有效订单
        $weekOrders = $order->weekOrder();
        // 本月销售金额 和 有效订单
        $monthOrders = $order->monthOrder();
		return view('admin.index.main',array('todayOrder'=>$todayOrders,'weekOrder'=>$weekOrders,'monthOrder'=>$monthOrders));
	}


    /**
     * lj
     *  本月酒店订单量排行  圆柱图
     * @param Request $request
     * @param Order $order
     */
    public function hotelOrderRank( Request $request,Order $order ) {
        $token = $request -> except('_token');
        // 接受值
        $data = $request->all();
        if($data['hotelOrderRank'] == 1) {
            // 本月酒店订单量排行
            $monthHotelRank = $order->hotelOrderRank();
            foreach($monthHotelRank as $key => $value) {
                $newHotelRank['name'][] =  $value['name'];
                $newHotelRank['count'][] = $value['count'];
            }
            echo json_encode($newHotelRank);
        }
    }

    /**
     * lj
     *  本月酒店销售金额排行  圆柱图
     * @param Request $request
     * @param Order $order
     */
    public function hotelPriceRank( Request $request,Order $order ) {
        $token = $request -> except('_token');
        // 接受值
        $data = $request->all();
        if($data['hotelPriceRank'] == 1) {
            // 本月酒店订单量排行
            $monthHotelRank = $order->hotelPriceRank();
            foreach($monthHotelRank as $key => $value) {
                $newHotelRank['name'][] =  $value['name'];
                $newHotelRank['price'][] = $value['price'];
            }
            echo json_encode($newHotelRank);
        }
    }

    /**
     *  lj
     *  订单量   折线图
     * @param Request $request
     * @param Order $order
     */
    public function orderRank( Request $request,Order $order) {
        $token = $request -> except('_token');
        // 接受值
        $data = $request->all();
        if($data['type'] == 1 || $data['type'] == 3) {
            $orderRank = $order->orderRank($data['type']);
//        p($orderRank);die;
            foreach($orderRank as $key => $value) {
                $newOrderRank['name'][] =  $key;
                $newOrderRank['value'][] = $value;
            }
        }elseif($data['type'] == 2) {
            $orderRank = $order->orderRank($data['type']);
//            p($orderRank);die;
            foreach($orderRank as $key => $value) {
                $newOrderRank['name'][] =  $key;
                $newOrderRank['value'][] = $value['value'];
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
    public function priceRank( Request $request,Order $order) {
        $token = $request -> except('_token');
        // 接受值
        $data = $request->all();
        if($data['type'] == 1 || $data['type'] == 3) {
            $orderRank = $order->priceRank($data['type']);
//        p($orderRank);die;
            foreach($orderRank as $key => $value) {
                $newOrderRank['name'][] =  $key;
                $newOrderRank['value'][] = $value;
            }
        }elseif($data['type'] == 2) {
            $orderRank = $order->priceRank($data['type']);
//            p($orderRank);
            foreach($orderRank as $key => $value) {
                $newOrderRank['name'][] =  $key;
                $newOrderRank['value'][] = $value['value'];
//                $newOrderRank['value'][] = $value;
            }
//            p($newOrderRank);die;
        }
        echo json_encode($newOrderRank);
    }


	// 权限管理提示
	public function auth() {
		return'您的权限不足！请联系超级管理员给你相应的权限分配';
	}
}
