<?php
/**
 * 基础设置
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Coupon;
use Illuminate\Validation;
class CouponController extends CommonController
{
    private $coupon,$common;
    public function __construct()
    {
        $this->coupon=new Coupon();
    }

    /**
     * @return array
     * 优惠卷添加
     */
    public function add(Request $request){
        if(\Request::isMethod('post')) {
            $input = $request->all();
            if(!empty($input['goods_id'])){
                $goods_id=implode(',',$input['goods_id']);
                $input['goods_id']=$goods_id;
            }
            unset($input['_token']);
            $roles=[
                'coupon_name'=>'required',
                'coupon_name_en'=>'required|alpha',
                'coupon_money'=>'required|numeric',
                'coupon_quota'=>'required|numeric',
                'send_num'=>'required|numeric',
                'coupon_desc'=>'required|between:5,30',
                'coupon_desc_en'=>'required|between:5,30|alpha',
                'coupon_type'=>'required',
                'vaild_start_time'=>'required|date',
                'vaild_end_time'=>'required|date|after:vaild_start_time',
            ];
            $msg = [
                'coupon_name.required'=>'请填写优惠卷名称',
                'coupon_name.alpha'=>'必须是字母',
                'coupon_money.numeric'=>'请填写必须是数字',
                'coupon_quota.numeric'=>'请填写必须是数字',
                'send_num.numeric'=>'请填写必须是数字',
                'coupon_desc.between'=>'请填写字数在5到100之间',
                'coupon_desc_en.between'=>'请填写字数在5到100之间',
                'coupon_desc_en.alpha'=>'必须是字母',
                'coupon_type.required'=>'请选择类型',
                'vaild_start_time.date'=>'请选择活动开始时间',
                'vaild_end_time.after'=>'请选择活动结束时间且活动时间不能小于活动开始时间',
            ];
            $validator = \Validator::make($request->all(), $roles, $msg);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withinput();
            }
            $vaild_start_time = strtotime($input['vaild_start_time']);
            $vaild_end_time = strtotime($input['vaild_end_time']);
            if ( $vaild_end_time > $vaild_start_time) {
                $result= $this->coupon->InsertData($input,$vaild_end_time,$vaild_start_time);
                if($result){
                    return redirect("admin/coupon/list");
                }else{
                    return error('添加失败','admin/coupon/list');
                }

            }

        }else{
            return view('admin.coupon.add');
        }

//
    }


    /**
     * @param Request $request
     * @return array
     * 优惠卷展示
     */
    public function coupon_list(Request $request){
        $coupon_name=$request->get('coupon_name')?$request->get('coupon_name'):'';
        $vaild_start_time=$request->get('vaild_start_time')?$request->get('vaild_start_time'):'';
        $vaild_end_time=$request->get('vaild_end_time')?$request->get('vaild_end_time'):'';
        $type=$request->get('type')?$request->get('type'):'';
        $data=$this->coupon->DataList($type,$coupon_name,$vaild_start_time,$vaild_end_time);
        $data->type=$type;
        $data->coupon_name=$coupon_name;
        $data->vaild_start_time=$vaild_start_time;
        $data->vaild_end_time=$vaild_end_time;
        return view('admin.coupon.list',['data'=>$data]);
//        }
    }


    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 优惠卷详情
     */
    public function details($id){
        $coupon_goods=array();
        $list=$this->coupon->datasFind('coupon',array('coupon_id'=>$id));
        if($list->coupon_type==2){
            $goodsIds=explode(',',$list->goods_id);
            $goods= DB::table('goods_side')->select('goods_name')->whereIn('goods_id',$goodsIds)->where('languages',1)->get();
            return view('admin.coupon.details',['list'=>$list,'goods'=>$goods]);
        }else{
            return view('admin.coupon.details',['list'=>$list]);

        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 编辑
     */
    public function edit(Request $request,$id){
        $list=$this->coupon->datasFind('coupon',array('coupon_id'=>$id));
        if($list->coupon_type==2){
            $data['goods']=DB::table('goods')->select('goods_id','main_img')->where(array('status'=>1,'is_del'=>1))->get()->toArray();
            $data['goods_id']=explode(',',$list->goods_id);
//            var_dump($data['goods_id']);die;
            return view('admin.coupon.edit',['list'=>$list,'data'=>$data]);
        }else{
            return view('admin.coupon.edit',['list'=>$list]);
        }


    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * 优惠卷修改
     */
    public function editAdd(Request $request){
        $input=$request->all();
        if(!empty($input['goods_id'])){
            $goods_id=implode(',',$input['goods_id']);
            $input['goods_id']=$goods_id;
        }
        unset($input['_token']);
        $roles=[
            'coupon_name'=>'required',
            'coupon_money'=>'required|numeric',
            'send_num'=>'required|numeric',
            'coupon_desc'=>'required|between:5,30',
            'coupon_type'=>'required',
            'vaild_start_time'=>'required|date',
            'vaild_end_time'=>'required|date|after:vaild_start_time',
        ];
        $msg = [
            'coupon_name.required'=>'请填写优惠卷名称',
            'coupon_money.numeric'=>'请填写必须是数字',
            'send_num.numeric'=>'请填写必须是数字',
            'coupon_desc.between'=>'请填写字数在5到100之间',
            'coupon_type.required'=>'请选择类型',
            'vaild_start_time.date'=>'请选择活动开始时间',
            'vaild_end_time.after'=>'请选择活动结束时间且活动时间不能小于活动开始时间',
        ];
        $validator = \Validator::make($request->all(), $roles, $msg);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withinput();
        }
        $vaild_start_time = strtotime($input['vaild_start_time']);
        $vaild_end_time = strtotime($input['vaild_end_time']);
        if ( $vaild_end_time > $vaild_start_time) {
            $result = $this->coupon->SaveData($input, $vaild_end_time, $vaild_start_time);
        }
        if($result){
            return redirect("admin/coupon/list");
        }else{
            return error('修改失败','admin/coupon/list');
        }
//        var_dump($data);

    }
    /**
     * @param Request $request
     * @return string
     * 优惠卷删除
     */
    public function del(Request $request){
        $coupon_id=$request->get('id');
        if(!empty($coupon_id)){
            $result=$this->coupon->DataDel(array('coupon_id'=>$coupon_id));
            if($result){
                return json_encode(['code'=>200,'msg'=>'删除成功']);
            }else{
                return json_encode(['code'=>400,'msg'=>'删除失败']);
            }
        }else{
            return json_encode(['code'=>300,'msg'=>'参数为空']);
        }

    }
    public function couponDelAll(Request $request){
        $id=$request->get('id','');
        $id=explode(',',$id);
        $reslut=Coupon::whereIn('coupon_id',$id)->delete();
        if($reslut){
            return ['code'=>200];
        }else{
            return ['code'=>500];
        }
    }

    /**
     * @return array
     * 查询打折相关商品
     */
    public function checkGoods(){
        $data=DB::table('goods')->select('goods_id','main_img')->where(array('status'=>1,'is_del'=>1))->get()->toArray();
        if(!empty($data)){
            return ['code'=>200,'data'=>json_encode($data)];
        }else{
            return ['code'=>404,'msg'=>'无法获取到数据'];
        }
    }
}