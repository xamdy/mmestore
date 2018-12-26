<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2018/10/23
 * Time: 17:52
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Sowingmap;
use App\Models\Goods;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Validation;
use App\Http\Controllers\Controller;
class SowingmapController extends CommonController
{
    public $sowingmap,$goods;
    public function __construct()
    {
        $this->sowingmap=new Sowingmap();
        $this->goods=new Goods();
    }

    /**
     * @param Request $request
     * @param Sowingmap $sowingmap
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 轮播图列表
     */
    public function imglist(Request $request){
        $img_name=$request->get('img_name');
        $data=$this->sowingmap->DataPage($img_name);
        if (isset($img_name)) {
            $data->img_name =$img_name;
        } else {
            $data->img_name = '';
        }
        return view('admin.sowingmap.list',array('data'=>$data));
    }
    /**
     * @param Request $request
     * @param Sowingmap $sowingmap
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 轮播图添加
     */
    public function add(Request $request){
        if(\Request::isMethod('post')){
            $roles=[
                'img_name'=>'required',
                'goods_id'=>'required',
                'img_status'=>'required',
                'img_url' => 'dimensions:width=750,height=340',
            ];
            $msg = [
                'img_name.required' => '请填写图片名称',
                'goods_id.required' => '请选择关联商品',
                'img_status.required' => '请选择图片状态',
                'img_url.dimensions' => '图片尺寸为750*340',
            ];
            $validator = \Validator::make($request->all(), $roles, $msg);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withinput();
            }
           $data=$request->all();
            unset($data['_token']);
            $data['create_time']=time();
            $path=$request->img_url->store('sowingmap','public');
            $data['img_url']='/storage/'.$path;
//            $goods_img=$this->goods->whereIds('main_img',$data['goods_id']);
            $reslut=$this->sowingmap->InsertData($data);
            if($reslut){
                return redirect('admin/sowingmap/Imglist');
            }else{
                return error($e->getMessage(),'admin/goods/add');
            }
        }else{
            $goods_id=$this->sowingmap->DataMore();
            $new_goods_id=array();
            foreach ($goods_id as $k=>$v){
                $new_goods_id[]=$v->goods_id;
            }
            $goods=$this->goods->FindField(array('goods_id','main_img'),array('status'=>1,'is_del'=>1),$new_goods_id);
            return view('admin.sowingmap.add',array('goods'=>$goods));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 轮播图详情
     */
    public function detail($id){
        $data=$this->sowingmap->getOne($id);
        return view('admin.sowingmap.detail',array('data'=>$data));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 轮播图编辑页面图渲染
     */
    public function edit($id){
        $data=$this->sowingmap->getOne($id);
        $goods_id= DB::table('sowingmap')->select('goods_id')->where("id",'<>',"$data->id")->get()->toArray();
        $new_goods_id=array();
        foreach ($goods_id as $k=>$v){
            $new_goods_id[]=$v->goods_id;
        }
        $goods=$this->goods->FindField(array('goods_id','main_img'),array('status'=>1,'is_del'=>1),$new_goods_id);
        return view('admin.sowingmap.edit',array('data'=>$data,'goods'=>$goods));
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 轮播图编辑
     */
    public function up(Request $request){
        $roles=[
            'img_name'=>'required',
            'goods_id'=>'required',
            'img_status'=>'required',
            'img_url' => 'dimensions:width=750,height=340',
        ];
        $msg = [
            'img_name.required' => '请填写图片名称',
            'goods_id.required' => '请选择关联商品',
            'img_status.required' => '请选择图片状态',
            'img_url.dimensions' => '图片尺寸为750*340',
        ];
        $validator = \Validator::make($request->all(), $roles, $msg);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withinput();
        }
        $data=$request->all();
        unset($data['_token']);
        if(empty($data['img_url'])){
            $Newdata['img_name']=$data['img_name'];
            $Newdata['goods_id']=$data['goods_id'];
            $Newdata['img_status']=$data['img_status'];
            $Newdata['img_url']=$data['old_img_url'];
            $result=$this->sowingmap->DataSave(array('id'=>$data['id']),$Newdata);
        }else{
            $path=$request->img_url->store('sowingmap','public');
            $Newdata['img_name']=$data['img_name'];
            $Newdata['goods_id']=$data['goods_id'];
            $Newdata['img_status']=$data['img_status'];
            $Newdata['img_url']='/storage/'.$path;
            $result=$this->sowingmap->DataSave(array('id'=>$data['id']),$Newdata);
        }
//        var_dump($data);
//        $goods_img=$this->goods->datasFind(array('goods_id'=>$data['goods_id']),
//            'main_img');
//        $data['img_url']=$goods_img->main_img;
//        $result=$this->sowingmap->DataSave(array('id'=>$data['id']),$data);
        if($request){
            return redirect('admin/sowingmap/Imglist');
        }else{
            return error($e->getMessage(),'admin/goods/add');
        }

    }

 /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 轮播图删除
     */
    public function del(Request $request){
        $data = $request->all();
        $result=$this->sowingmap->delOne($data['id']);
        if($result){
            return json_encode(['code'=>1,'msg'=>'成功']);
        }else{
            return json_encode(['code'=>2,'msg'=>'没有参数']);
        }
    }


}