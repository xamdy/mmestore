<?php
//后台货柜（体验店）控制器 lzt
namespace App\Http\Controllers\Admin;
use App\Models\Common;
use App\Models\Container;
use Illuminate\Http\Request;
use QRcode;
class ContainerController extends CommonController
{
    //货柜表页
    public function index(Request $Request,Container $container){
        $data = $Request->all();
        //公共查询内容
        $select=    array('id','hotel_id', 'room_id','status', 'container_number','img');
        $res = $container->datasPage($data,$select);

        //获取酒店id和名称
        $common =new Common();
        $H_where['is_del'] = 1;
        $hotel = $common->datas('hotel',array(),array('id','name'));
        return view('admin.container.index',array('res'=>$res['list'],'hotel'=>$hotel,'contList'=>$res['contList']));

    }

    //查询体验店报修详情
    public function selectErr(Request $Request){
        $data = $Request->all();
        $where['c_id']= $data['id'];
        //$where['is_del']= 1;
        $orderBy= 'create_time';
        $type= 'aec';
        $common =new Common();
        //查询房间
        $list = $common->orderByData('container_error',$where,array('tel','create_time'),$orderBy,$type);
        foreach ($list as $k=>$v){
            $list[$k]['create_time'] = date("Y-m-d h:i:s", $v['create_time']);
        }
        return json_encode($list);
    }

    //查询体验店编号是否存在
    public function checkNumber(Request $Request){
        $data = $Request->all();
        $common =new Common();
        //查询房间
        if($data['status']==1){
            $where['container_number']= $data['container_number'];
            $list = $common->datasFind('container',$where,array('id'));
            if(empty($list)){
                $res = 1; //未找到数据
            }else{
                $res = 2;  //数据已存在
            }
            return json_encode($res);
        }
        if($data['status']==2){
            $where['lock_code']= $data['lock_code'];
            $list = $common->datasFind('container',$where,array('id'));
            if(empty($list)){
                $res = 1; //未找到数据
            }else{
                $res = 2;  //数据已存在
            }
            return json_encode($res);
        }

    }

    //查询体验店商品编号是否存在
    public function checkGoods(Request $Request,Container $container){
        $data = $Request->all();
        $where= explode('/',$data['num']);
        //查询商品编号是否存在
        $list = $container->datasIn($where);
        //组长数据
        if(empty($list)){
            $res['code'] = 1; //未找到数据
        }else{
            $test = 0;
            $msg ='';
            foreach ($where as $k =>$v ){
                if(empty($list[$v])){
                    $test = 1;
                    $msg .=$v.',';
                }
            }
            if($test == 1){
                $res['code'] = 2;  //部分数据不存在或下架
                $res['msg'] = $msg;
            }else{
                $res['code'] = 3;  //数据存在
                $res['msg'] = $list;
            }
        }
        //根据c_id判断是编辑还是添加货柜
        if(!empty($data['c_id'])){
            //查询添加的商品，货柜中是否已存在
            $res2 = $container->editContainer($data['c_id']);
            $num = 1;
            // dump($res);
            foreach ($where as $k=>$v){
                //   dump($res['goods'][$v]);
                if(!empty($res2['goods'][$v])){
                    $num = 2;
                    break ;
                }
            }
            if($num == 2){
                $res['code'] = 4;  //数据存在
                $res['msg'] = '';
            }
        }
        return json_encode($res);
    }

    //体验店添加
    public function add(Request $Request,Container $container){
        $data = $Request->all();
        if (empty($data)) {
            return view('admin.container.add');
        } else {
            //根据信息生成二维码
            $url= 'https://www.mmestore.com/'.$data['container_number'];
            $img =$this->scerweima($url);
            //插入数据库
            $res = $container->addContainer($data,$img);
            if($res == 1){
                return success("体验店添加成功", 'admin/container/index');
            }else{
                return error('体验店添加失败','admin/container/add');
            }
        }
    }

    //货柜编辑
    public function edit(Request $Request,Container $container){

        $method = $Request->method();
        if($method == 'GET'){
            $id = $Request['id'];
            //查询内容
            $info = $container->editContainer($id);
            return view('admin.container.edit',array('res'=>$info));
        }else{
            $data = $Request->all();
            $url= 'https://www.mmestore.com/'. $data['container_number'];
            $img = $this->scerweima($url, $data['container_number']);
            $res = $container->editContainerGoods($data['id'],$data['goods'],$img);
            if($res == true){
                return success("体验店编辑成功", 'admin/container/index');
            }else{
                return error('体验店编辑失败','admin/container/edit');
            }
        }
    }


    /**
     * 货柜详情
     * @param $id  货柜id
     * @param $error_num   保修次数
     * @param $hotel    酒店名称
     * @param $room     房间名称
     * @param Container $container
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function info($id,$error_num,$hotel,$room,Container $container) {
        //查询内容
        $info = $container->editContainer($id);
        $info['info']->error_num = $error_num;
        $info['info']->hotel = $hotel;
        $info['info']->room = $room;
        return view('admin.container.info',array('res'=>$info));
    }

    //体验店状态更改
    public function updateStatus(Request $Request,Container $container){
        $data = $Request->all();
        $where['id'] = $data['id'];

        $status[$data['column']] = $data['value'];
        if(($data['column'] == 'status')&&($data['value'] == 3) ){
            $status['hotel_id'] =0;
            $status['room_id'] =0;
        }
        $DB = new Common();
        $res = $DB->UpdateCommon('container',$where,$status);
        if($res){
            return 1;
        }else{
            return 0;
        }
    }



    // 1. 生成原始的二维码(生成图片文件)
    public  function scerweima($url) {
        $value = $url;					//二维码内容
        $errorCorrectionLevel = 'L';	//容错级别
        $matrixPointSize = 16;			//生成图片大小
        //生成二维码图片
        $filename = '../public/qrcode/'.time().'.png';
        QRcode::png($value,$filename , $errorCorrectionLevel, $matrixPointSize, 2);
        $res = substr($filename,10);
        return $res;
    }


    public function excel()
    {
//        $common = new Common();
//        $arr = $common->datas('container', array('is_del' => 1, 'status' => 1), array('room_id','lock_code'));
//        $rAr=array();
//        $rArs=array();
//        for($i=0;$i<count($arr);$i++)
//        {
//            if(!isset($rAr[$arr[$i]['room_id']]))
//            {
//                $rAr[$arr[$i]['room_id']]=$arr[$i];
//            }
//        }
//        $arrs=array_values($rAr);
//
//        for($i=0;$i<count($arrs);$i++)
//        {
//            if(!isset($rArs[$arrs[$i]['lock_code']]))
//            {
//                $rArs[$arrs[$i]['lock_code']]=$arrs[$i];
//            }
//        }
//        $arr1=array_values($rArs);
//        var_dump($arr1);

            return view('admin.container.excel');
    }


}
