<?php
//后台订单控制器 lzt
namespace App\Http\Controllers\Admin;

use App\Models\Common;
use App\Models\Order;
use App\Models\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Excel;

class OrderController extends CommonController
{
    //d订单列表页
	public function index(Request $Request,Order $Order){
        $data = $Request->all();

        //公共查询内容
        $select=    array('o.order_id', 'o.order_number','o.creat_time', 'o.user_id', 'o.tel', 'o.real_amount', 'o.order_address', 'o.h_id','o.r_id','o.status');
        $res = $Order->datasPage($data,$select,10);

        $aaa = json_encode($data);
//        p($res);
      $bb =  json_decode($aaa);
      //  dump($bb);
        //获取酒店id和名称
        $common =new Common();
        $H_where['is_del'] = 1;
        $hotel = $common->datas('hotel',array(),array('id','name'));

        $status = array(
            '1' => '待支付',
            '2' => '已完成',
            '3' => '已取消',
        );

        if(isset($data['status'])){
            $res->status= $data['status'];
        }else{
            $res->status='';
        }

        if(isset($data['start_time'])){
            $res->start_time= $data['start_time'];
        }else{
            $res->start_time='';
        }

        if(isset($data['end_time'])){
            $res->end_time= $data['end_time'];
        }else{
            $res->end_time='';
        }

        if(isset($data['h_id'])){
            $res->h_id= $data['h_id'];
        }else{
            $res->h_id='';
        }

        if(isset($data['user_tel'])){
            $res->user_tel=$data['user_tel'];
        }else{
            $res->user_tel='';
        }

        return view('admin.order.index',array('res'=>$res,'hotei'=>$hotel,'select'=>$aaa,'status'=>$status));
    }

    //二级联动根据酒店ID查询房间
    public function selectRoom(Request $Request){
        $data = $Request->all();
        $where['hotel_id']= $data['hotel_id'];
        $where['is_del']= 1;
        $orderBy= 'room_number';
        $type= 'aec';
        $common =new Common();
        //查询房间
        $room = $common->orderByData('room',$where,array('id','room_number'),$orderBy,$type);

        return json_encode($room);
    }

    //订单详情页
    public function orderInfo($id,Order $Order){
        $data['o.order_id'] = $id;
        $select=    array('o.order_id', 'o.order_number','o.status', 'o.order_address', 'o.tel', 'o.order_amount','o.real_amount', 'o.creat_time','o.user_id','u.name','u.img','u.tel');
        $common =new Container();
        //查询订单主表
        $res = $common->orderList($data,$select);
        $res[0]->name = json_decode($res[0]->name);
        //查询商品列表
        $filed = array('o.num','o.goods_price','s.goods_name','o.goods_img','g.barcode');
        $where['o.order_id'] = $id;
        $where['s.languages'] = 1;
        $list = $Order->infoList($where,$filed);
        return view('admin.order.info',array('info'=>$res[0],'list'=>$list));
    }
    
    //Excel文件导出功能 
    public function export($data,Order $Order){

       // $quest = $Request->all();
       // dump($data);  die;
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
