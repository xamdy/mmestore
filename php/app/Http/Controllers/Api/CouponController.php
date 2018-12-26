<?php

namespace App\Http\Controllers\Api;

use App\Models\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CouponController extends Controller
{

    public function index(Request $request){
        $data=$request->all();
        if(!empty($data['user_id'])) {
            if($data['language']==1){
                $list=object_array(DB::table('coupon_users')
                    ->select('coupon.coupon_name','coupon.coupon_type','coupon.coupon_desc','coupon.coupon_money','coupon_users.*')
                    ->join('coupon','coupon_users.coupon_id','=','coupon.coupon_id')
                    ->where(array('user_id'=>$data['user_id'],'coupon.dataflag'=>1))->get()->toArray());
            }else{
                $list=object_array(DB::table('coupon_users')
                    ->select('coupon_side.coupon_name','coupon_side.coupon_desc','coupon.coupon_type','coupon.coupon_money','coupon_users.*')
                    ->join('coupon','coupon_users.coupon_id','=','coupon.coupon_id')
                    ->join('coupon_side','coupon_side.coupon_id','=','coupon.coupon_id')
                    ->where(array('user_id'=>$data['user_id'],'coupon.dataflag'=>1))->get()->toArray());
            }

            if(empty($list)){
                return publicReturn(
                    0,
                    '该用户没有优惠卷'
                );
            }else{
                $time=time();
                foreach ($list as $k=>$v){
                    $list[$k]['coupon_money']=substr($v['coupon_money'],0,-3);
                    $list[$k]['start_time']=date('Y-m-d H:i:s',$v['receive_time']);
                    $end_time= $v['receive_time']+2*24*60*60;
                    if($end_time>=$time){
                        $list[$k]['use_status']=0;
                    }else{
                        $list[$k]['use_status']=1;
                    }
                    $list[$k]['end_time']=date('Y-m-d H:i:s',$end_time);
                }
                return publicReturn(
                    1,
                    '查询成功',
                    $list
                );
            }
        }else{
            return publicReturn(
                3,
                '参数为空'
            );
        }
    }
    /**
     * @param Request $request
     * @return string
     * 查询优惠卷
     */
    public function show(Request $request)
    {
        $data = $request->all();
        if (!empty($data['user_id']) && $data['userStatus'] == 2) {
            $list = $this->common->datasFind('coupon_users', array("coupon_status" => 1, 'user_id' => $data['user_id'], 'coupon_id' => 3));
            if (!empty($list) && !empty($list->receive_time)) {
                $time = time();
                $end_time = $list->receive_time + 2 * 24 * 60 * 60;
                if ($end_time >= $time) {
                    $coupon=$this->common->datasFind('coupon',array('coupon_id'=>$list->coupon_id,'dataflag'=>1,'coupon_type'=>1),array('coupon_quota','coupon_money'));
                    $list->coupon_quota=(string)$coupon->coupon_quota;
                    $list->coupon_money=(string)$coupon->coupon_money;
                    return publicReturn(
                        1,
                        '优惠卷获取成功',
                        $list
                    );
                } else {
                    return publicReturn(
                        2,
                        '优惠卷已失效'
                    );
                }

            } else {
                return publicReturn(
                    3,
                    '无优惠卷可使用'
                );
            }

        }
    }

        /**
         * @param Request $request
         * 用户领取优惠卷
         */
        public function addCoupon(Request $request){
        $data=$request->all();
        if(!empty($data['user_id'])  && $data['status']==1){
            $user_id=$this->common->datasFind('coupon_users',array('user_id'=>$data['user_id']),'user_id');
            if(empty($user_id->user_id)){
                $result= $this->common->addCommon(
                    'coupon_users',
                    array(
                        'coupon_id'=>3,
                        'user_id'=>$data['user_id'],
                        'receive_time'=>time(),
                        'coupon_status'=>1
                    )
                );

                if($result){
                    $status=1;
                    return publicReturn(
                        '1',
                        '领取成功',
                        $status
                    );
                }else{
                    $status=0;
                    return publicReturn(
                        '2',
                        '领取失败',
                        $status
                    );
                }
            }else{
                $status=3;
                return publicReturn(
                    '3',
                    '您已经领取过了哦!',
                    $status
                );
            }
        }

    }

        /**
         * @param Request $request
         * 修改优惠卷状态
         */
    public function updateCoupon(Request $request){
        $data=$request->all();
        if(!empty($data['id']) ){
            $result=$this->common->UpdateCommon(
                'coupon_users',
                array('id'=>$data['id']),
                array(
                    'coupon_status'=>2
                )
            );
            if($result){
                return publicReturn(
                    1,
                    '优惠卷已使用'
                );
            }else{
                return publicReturn(
                    2,
                    '优惠卷修改失败'
                );
            }
        }else{
            return publicReturn(
                2,
                '参数错误'
            );
        }

    }


    /**
     * 定时任务 修改优惠卷结束时间的数据
     */
        public function UpCoupon(){
        $time=time();
        $Ids=object_array(DB::table('coupon')->where("vaild_end_time", "<","$time")->where(array('dataflag'=>1))->pluck('coupon_id')->toArray());
        if(!empty($Ids)){
            foreach ($Ids as $k=>$v){
                DB::table('coupon')->where(array('coupon_id'=>$v))->update(array('dataflag'=>0));
            }
        }

    }

    }
