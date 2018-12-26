<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Common;
use App\Models\Goods;
use Excel;
class GoodsController extends CommonController
{
    /**
     *
     *  添加商品信息
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add( Request $request,Common $common ) {
        try {
            // 查看出分类列表
            $category = $common->datas(
                'category',
                array(
                    'pid' => 0
                ),
                array(
                    'id',
                    'name'
                )
            );
            return view('admin.goods.add',array('category'=>$category));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function imgUpload( Request $request ) {
        try {
            // 接受图片
            $file = $request->file();
            $filePath =[];  // 定义空数组用来存放图片路径
            foreach ($file as $key => $value) {
                // 判断图片上传中是否出错
                if (!$value->isValid()) {
                    exit("上传图片出错，请重试！");
                }
                if(!empty($value)){//此处防止没有多文件上传的情况
                    $allowed_extensions = ["png", "jpg", "gif"];
                    if ($value->getClientOriginalExtension() && !in_array($value->getClientOriginalExtension(), $allowed_extensions)) {
                        exit('您只能上传PNG、JPG或GIF格式的图片！');
                    }
                    $destinationPath = '/uploads/goods/'.date('Y-m-d');       // public文件夹下面uploads/xxxx-xx-xx 建文件夹
                    $extension = $value->getClientOriginalExtension();   // 上传文件后缀
                    $fileName = date('YmdHis').mt_rand(100,999).'.'.$extension;     // 重命名
                    $value->move(public_path().$destinationPath, $fileName); // 保存图片
                    $filePath[] = $destinationPath.'/'.$fileName;
                }
            }
            // 返回上传图片路径，用于保存到数据库中
            return json_encode($filePath);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function upload(Request $request)
    {
        try {
            // 接受图片
            $file = $request->file();
            $filePath =[];  // 定义空数组用来存放图片路径
            foreach ($file as $key => $value) {
                // 判断图片上传中是否出错
                if (!$value->isValid()) {
                    $data[]=array(
                        'status'=>0,//状态
                        'info'=>'上传图片出错，请重试！',//错误信息
                    );
                }
                if(!empty($value)){//此处防止没有多文件上传的情况
                    $allowed_extensions = ["png", "jpg", "gif"];
                    if ($value->getClientOriginalExtension() && !in_array($value->getClientOriginalExtension(), $allowed_extensions)) {
                        $data[]=array(
                            'info'=>'您只能上传PNG、JPG或GIF格式的图片！',//限制格式
                            'status'=>0,//状态
                        );
                    }
                    $size = $value->getClientSize();
                    if($size>5250000){
                        $data[]=array(
                            'info'=>'图片不能大于5M',//限制大小
                            'status'=>0,//状态
                        );
                    }
                    $destinationPath = '/uploads/goods/'.date('Y-m-d');       // public文件夹下面uploads/xxxx-xx-xx 建文件夹
                    $extension = $value->getClientOriginalExtension();   // 上传文件后缀
                    $fileName = date('YmdHis').mt_rand(100,999).'.'.$extension;     // 重命名
                    $value->move(public_path().$destinationPath, $fileName); // 保存图片
                    $data[]=array(
                        'savename'=>$fileName,//保存名称
                        'savepath'=>$destinationPath,//保存路径
                        'status'=>1,//状态
                    );
                }
            }
            // 返回上传图片路径，用于保存到数据库中

            return json_encode($data[0]);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     *  商品入库
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addGoods( Request $request ,Common $common) {
        try {
            header("Content-type:text/html;charset=utf-8");
            // 接收数据
            $datas = $request->all();
            if(empty($datas['inventory']) || empty($datas['img']) || empty($datas['goods_name_Chinese']) || empty($datas['goods_name_English']) || empty($datas['goods_introduction_Chinese']) || empty($datas['goods_introduction_English']) || empty($datas['goods_description_Chinese']) || empty($datas['goods_description_English']) || empty($datas['original_price']) || empty($datas['present_price']) || empty($datas['barcode']) || empty($datas['order'])) {
                return error('参数不能为空！','admin/goods/add')->withInput();
            }

            // 判断现价是否大于原价
            if($datas['original_price'] < $datas['present_price']) {
                return error('现价不能大于原价','admin/goods/add')->withInput();;
            }

            // 接受产品主图
            $file = $request->file('main_img');
            if($file) {
                $path = '/uploads/goodsMain';   //保存路径
                $url = upload($file, $path);  //调用上传类方法  获取到地址
                $file_name = $file->getClientOriginalName(); // 文件名称
            }else {
                $url = '';
                $file_name = '';
            }

            // 图片传过来是json格式  先处理图片
            $imgUrl = json_decode($datas['img']);
            // 商品的主图
            $goods['main_img'] = $url;
            // 把传来的所有图片作为轮播图 首先转为为字符串入库
            $goods['shuffling_figure'] = implode(',',$imgUrl);
            // 接受入商品数据库的字段
            $goods['barcode'] = $datas['barcode'];
            $goods['inventory'] = $datas['inventory'];
            $goods['original_price'] = $datas['original_price'];
            $goods['present_price'] = $datas['present_price'];
            $goods['order'] = $datas['order'];
            $goods['create_time'] = time();
            $goods['cat_id'] = $datas['id'];

            DB::beginTransaction();  // 开启事物
            try {
                // 入主表数据库
                $goodsAddId = $common->addCommon(
                    'goods',
                    $goods
                );
                // 入附表中文数据
                $goodsChinese = array(
                    'goods_id' => $goodsAddId,
                    'goods_name' => $datas['goods_name_Chinese'],
                    'goods_introduction' => $datas['goods_introduction_Chinese'],
                    'goods_description' => $datas['goods_description_Chinese'],
                    'languages' => 1,
                );
                $goodsChineseId = $common->addCommon(
                    'goods_side',
                    $goodsChinese
                );

                // 入附表英文数据
                $goodsEnglish = array(
                    'goods_id' => $goodsAddId,
                    'goods_name' => $datas['goods_name_English'],
                    'goods_introduction' => $datas['goods_introduction_English'],
                    'goods_description' => $datas['goods_description_English'],
                    'languages' => 2,
                );
                $goodsEnglishId = $common->addCommon(
                    'goods_side',
                    $goodsEnglish
                );

                if($goodsAddId && $goodsChineseId && $goodsEnglish) {
                    DB::commit();
                    return success('添加成功！','admin/goods/goodsList');
                }

            }catch(\Exception $e) {
                DB::rollBack();
                return error($e->getMessage(),'admin/goods/add');
            }

        } catch (\Exception $e) {
            return error($e->getMessage(),'admin/goods/add');
        }
    }

    /**
     *  商品列表
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function goodsList( Request $request,Common $common,Goods $goods ) {
        try {

            $datas = $request->all();
            // 判断状态
            if(isset($datas['status'])) {
                if($datas['status'] ==0) {
                    $datas['status'] = '';
                }
            }
            // 判断分类
            if(isset($datas['cate_id'])) {
                if($datas['cate_id'] ==0) {
                    $datas['cate_id'] = '';
                }
            }
            $res = $goods->datasPage($datas);
//            p($res);die;
            if(isset($datas['goods_name'])){
                $res->goods_name=$datas['goods_name'];
            }else{
                $res->goods_name='';
            }
            if(isset($datas['barcode'])){
                $res->barcode=$datas['barcode'];
            }else{
                $res->barcode='';
            }
            // 查找分类
            $category = $common->datas(
                'category',
                array(
                    'pid' => 0
                ),
                array(
                    'id',
                    'name'
                )
            );

            return view('admin.goods.index',array('res'=>$res,'request'=>'','category'=>$category));
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     *  添加库存
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addInventory( Request $request ,Goods $goods) {
        $token = $request -> except('_token');
        // 接受值
        $data = $request->all();
        // 增加库存
        $res = $goods->addGoodsNum(array('goods_id'=>$data['id']),'inventory',$data['value']);
        if($res) {
            $return['code'] = 1;
            $return['msg'] = '添加库存成功';
        }else {
            $return['code'] = 2;
            $return['msg'] = '添加库存失败';
        }
        return json_encode($return);
    }

    /**
     *  上架下架该商品
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function upGoods( Request $request,Common $common ) {
        $token = $request -> except('_token');
        // 接受值
        $data = $request->all();
        // 查看商品的上架下架值
        $goods = $common->datasFind(
            'goods',
            array(
                'goods_id' => $data['id']
            ),
            'status'
        );
        // 如果为1则是上架  需要该为下架   为2则是下架  需要该成上架
        if($goods->status == 1) {
            $res = $common->UpdateCommon(
                'goods',
                array(
                    'goods_id' => $data['id']
                ),
                array(
                    'status' => 2
                )
            );
        }else {
            $res = $common->UpdateCommon(
                'goods',
                array(
                    'goods_id' => $data['id']
                ),
                array(
                    'status' => 1
                )
            );
        }

        if($res) {
            $return['code'] = 1;
            $return['msg'] = '成功';
        }else {
            $return['code'] = 2;
            $return['msg'] = '失败';
        }
        return json_encode($return);

    }


    /**
     *  查看详情
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function details(Request $request, $id,Goods $goods) {
        try {
            // 查看商品详情
            $goodsList = $goods->showDetails(array('goods_side.goods_id'=>$id));
            $field = array(
                'g.goods_id',
                'g.barcode',
                'g.original_price',
                'g.present_price',
                'g.main_img',
                'g.shuffling_figure',
                'g.order',
                'g.inventory',
                'g.sold',
                'c.name',
            );
            $goods = $goods->goodsDetails(array('goods_id' => $id),$field);
            // 把图片轮播图转为为数组返回视图层
            $imgUrl = array_filter(explode(',',$goods['shuffling_figure'])) ;
            return view('admin.goods.details',array('goods'=>$goods,'China'=>$goodsList['China'],'English'=>$goodsList['English'],'imgUrl'=>$imgUrl));
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     *  编辑查看
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Common $common,Goods $goods) {
        try {
            // 查看商品详情
            $goodsList = $goods->showDetails(array('goods_side.goods_id'=>$id));
            $goods = object_array($common->datasFind(
                'goods',
                array(
                    'goods_id' => $id
                ),
                array(
                    'cat_id',
                    'goods_id',
                    'barcode',
                    'original_price',
                    'present_price',
                    'main_img',
                    'shuffling_figure',
                    'order',
                    'inventory',
                )
            ));
            // 根据分类id查看出分类名称
            $cateName = $common->datasFind(
                'category',
                array(
                    'id' => $goods['cat_id']
                ),
                array(
                    'id',
                    'name'
                )
            );

            // 查看出分类列表
            $category = $common->datas(
                'category',
                array(
                    'pid' => 0
                ),
                array(
                    'id',
                    'name'
                )
            );

            // 把图片轮播图转为为数组返回视图层
            $imgUrl = explode(',',$goods['shuffling_figure']);
            $count = count($imgUrl);
            return view('admin.goods.edit1',array('cate'=>$cateName,'category'=>$category,'goods'=>$goods,'China'=>$goodsList['China'],'English'=>$goodsList['English'],'imgUrl'=>$imgUrl,'count'=>$count));
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     *  编辑修改
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPost( Request $request,Common $common ) {
        try {
            $data = $request->all();
            // 第一步修改商品表主表
            $goodsArray = array(
                'main_img' => $data['main_img'],
                'shuffling_figure' => $data['shuffling_figure'],
                'original_price' => $data['original_price'],
                'present_price' => $data['present_price'],
                'order' => $data['order'],
                'cat_id' => $data['cate_id'],
            );
            DB::beginTransaction();  // 开启事物
            try {
                $goods = $common->UpdateCommon(
                    'goods',
                    array(
                        'goods_id' => $data['goods_id']
                    ),
                    $goodsArray
                );

                // 第二步 修改商品附表  中文
                $ChinaArray = array(
                    'goods_name' => $data['goods_name_Chinese'],
                    'goods_introduction' => $data['goods_introduction_Chinese'],
                    'goods_description' => $data['goods_description_Chinese'],
                );
                $Chinese = $common->UpdateCommon(
                    'goods_side',
                    array(
                        'id' => $data['China_id']
                    ),
                    $ChinaArray
                );

                // 第三步 修改商品附表 英文
                $EnglishArray = array(
                    'goods_name' => $data['goods_name_English'],
                    'goods_introduction' => $data['goods_introduction_English'],
                    'goods_description' => $data['goods_description_English'],
                );
                $English = $common->UpdateCommon(
                    'goods_side',
                    array(
                        'id' => $data['English_id']
                    ),
                    $EnglishArray
                );
//                p($goods);
                // 如果都成功这修改成功
                if($goods !== false && $Chinese !== false && $English !== false) {
                    DB::commit();
                    $return['code'] = 1;
                    $return['msg'] = '成功';
                    return json_encode($return);
                }

            }catch(\Exception $e) {
                DB::rollBack();
                return error($e->getMessage(),'admin/goods/goodsList');
            }

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     *  lj
     *  删除功能
     * @param $id
     */
    public function del( Request $request,Goods $goods) {
        $token = $request -> except('_token');
        // 接受值
        $data = $request->all();
        $where['goods_id'] = $data['id'];
        $res = $goods->deletes($where,array('is_del'=>2));
        if($res) {
            $return['code'] = 1;
            $return['msg'] = '成功';
        }else {
            $return['code'] = 2;
            $return['msg'] = '失败';
        }
        return json_encode($return);
    }
    /**
     *  lj
     *  删除图片
     * @param Request $request
     */
    public function delImg( Request $request,Common $common )
    {
        $data = $request->all();
        $imgUrl = '/' . $data['url'];
        // 获取到图片路径
//        $imgUrl = '/'.strstr($data['url'],'uploads');
//        // 先查询出数据库的路径
        $img = $common->datasFind(
            'goods',
            array(
                'goods_id' => $data['goods_id']
            ),
            'shuffling_figure'
        );
//        // 字符串转化为数组
        $imgArr = explode(',', $img->shuffling_figure);

        foreach ($imgArr as $key => $value) {
            if ($value == $imgUrl) {
                unset($imgArr[$key]);
            }
        }
//
//        // 把新得到的转化数组转为成字符串
        $newUrl = implode(',', $imgArr);
//
//        // 重新修改数据库
        $res = $common->UpdateCommon(
            'goods',
            array(
                'goods_id' => $data['goods_id']
            ),
            array(
                'shuffling_figure' => $newUrl
            )
        );
        @unlink('./' . $data['url']);//刪除圖片
        if(empty($imgArr)){
            $imgStatus=1;
        }else{
            $imgStatus=2;
        }
        if ($res) {
            $return['code'] = 1;
            $return['msg'] = '成功';
            if(empty($imgArr)){
                $return['imgStatus']=1;
            }else{
                $return['imgStatus']=2;
            }
        } else {
            $return['code'] = 2;
            $return['msg'] = '失败';
            $return['imgStatus']=2;
        }
        return json_encode($return);
    }

    /**
     * lj
     *  查看条形码是否存在
     * @param Request $request
     * @return string
     */
    public function isBarcode(Request $request,Common $common) {
        $data = $request->all();
        $where['barcode']= $data['barcode'];
        $where['is_del']= 1;

        //查询商品
        $list = $common->datasFind('goods',$where,array('goods_id'));
        if(empty($list)){
            $res = 1; //未找到数据
        }else{
            $res = 2;  //数据已存在
        }
        return json_encode($res);
    }

    /**
     * @param Request $request
     * @param Goods $goods
     * @param Common $common
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 货损列表
     */

    public function damagelist(Request $request,Goods $goods,Common$common){
        $where=array();
        $where['goods_name']=$request->get('goods_name')?$request->get('goods_name'):'';
        $where['damage_type']=$request->get('damage_type')?$request->get('damage_type'):'';
        $where['c_id']=$request->get('c_id')?$request->get('c_id'):'';
        $where['h_id']=$request->get('h_id')?$request->get('h_id'):'';
        $where['start_time']=$request->get('start_time')?$request->get('start_time'):'';
        $where['end_time']=$request->get('end_time')?$request->get('end_time'):'';
        $data=$goods->GoodsDamage($where,10);
        $data->goods_name=$where['goods_name'];
        $data->damage_type=$where['damage_type'];
        $data->c_id=$where['c_id'];
        $data->h_id=$where['h_id'];
        $data->start_time=$where['start_time'];
        $data->end_time=$where['end_time'];
        if(!empty($where['h_id'])|| !empty('start_time') || !empty('end_time'))
        {
            $data->keyword=$where;
        }
        $container=$common->datas('container',array('is_del'=>1),array('id','container_number'));
        $hotel=$common->datas('hotel',array('is_del'=>1),array('id','name'));
        return view('admin.goods.damage',['data'=>$data,'container'=>$container,'hotel'=>$hotel]);
    }

    public function Goodsxsls($keyword,Goods $goods){
        $where=object_array(json_decode($keyword));
        $data=$goods->GoodsExport($where);
        $cellData=array(0=>array('商品名称','所属酒店','所属房间','所属体验店','货损状态','货损时间'));
        foreach ($data as $k =>$v ){

            $cellData[$k+1][0]=$v->goods_name;
            $cellData[$k+1][1]=$v->name;
            $cellData[$k+1][2]=$v->room_number;
            $cellData[$k+1][3]=$v->container_number;
            if($v->damage_type == 1){
                $cellData[$k+1][4]='损坏';
            }else if($v->damage_type == 2){
                $cellData[$k+1][4]='过期';
            }else if($v->damage_type == 3){
                $cellData[$k+1][4]='丢失';
            }else{
                $cellData[$k+1][4]='其他';
            }
            $cellData[$k+1][5]=date('Y-m-d H:i:s',$v->damage_time);

        }

        $name = date('Y-m-d');
        Excel::create('货损详情'.$name,function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');

    }
}
