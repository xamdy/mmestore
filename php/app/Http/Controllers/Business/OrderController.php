<?php

namespace App\Http\Controllers\Business;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Room;
use App\Models\Common;
use App\Models\Container;
use Excel;
class OrderController extends Controller
{
    //d订单列表页
	public function index(Request $Request,Order $Order){
        $data = $Request->all();
        $data['h_id'] = session('hotel_id');
        //公共查询内容
        $select=    array('o.order_id', 'o.order_number','o.creat_time', 'o.user_id', 'o.tel', 'o.real_amount', 'o.order_address', 'o.h_id','o.r_id','o.status');
        $res = $Order->datasPage($data,$select,10);

        $room = Room::where('hotel_id',session('hotel_id'))->select('id','room_number')->get();
        $aaa = json_encode($data);
        return view('business.order.index',array('res'=>$res,'room'=>$room,'where'=>$aaa));  
    }
        //订单详情页
    public function orderInfo($id,Order $Order){
        $data['order_id'] = $id;
        $select=    array('o.order_id', 'o.order_number','o.status', 'o.order_address', 'o.tel', 'o.order_amount','o.real_amount', 'o.creat_time','o.user_id','u.name','u.img');
        $common =new Container();
        //查询订单主表
        $res = $common->orderList($data,$select);
        $res[0]->name = json_decode($res[0]->name);
        //查询商品列表
        $filed = array('o.num','o.goods_price','s.goods_name','g.barcode');
        $where['o.order_id'] = $id;
        $where['s.languages'] = 1;
        $list = $Order->infoList($where,$filed);
        return view('business.order.info',array('info'=>$res[0],'list'=>$list));
    }
    /**
     * 导出excel
     * @author j
     * @DateTime 2018-08-29T10:08:22+0800
     * @return   [type]                   [description]
     */
    public function export($data,Order $Order){
        
        $data = object_array(json_decode($data));
        //公共查询内容
        $select=    array( 'o.order_number','o.creat_time',  'o.tel', 'o.real_amount', 'o.order_address','o.status');
        $res = $Order->datasPage($data,$select,10000);
        $arr = object_array($res->items());
        $cellData=array(0=>array('订单编号','订单来源','手机号码','订单金额','状态','下单时间'));
        foreach ($arr as $k =>$v ){
            $cellData[$k+1][0]=$v['order_number'];
            $cellData[$k+1][1]=$v['order_address'];
            $cellData[$k+1][2]=$v['tel'];
            $cellData[$k+1][3]=$v['real_amount'];
            switch ($v['status']){
                case 1:
                  $status = '待支付';
                  break;
                case 2:
                    $status = '已完成';
                    break;
                case 3:
                    $status = '已取消';
                    break;
            }
            $cellData[$k+1][4]=$status;
            $cellData[$k+1][5]=date('Y-m-d H:i:s',$v['creat_time']);
        }

        $name = date('Y-m-d H:i:s');
        Excel::create('订单详情'.$name,function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
}