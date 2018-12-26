<?php
namespace App\Models;;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Coupon extends Common
{
    public function CouponMoeny($shop_id)
    {
        return $this->select('coupon_id', 'coupon_money', 'coupon_name')->where('goods_id', $shop_id)->first()->toArray();
    }

    public function InsertData($input, $vaild_end_time, $vaild_start_time)
    {
        $data = array();
        if (!empty($input)) {
            if(!empty($input['goods_id'])){
                $data['goods_id'] = $input['goods_id'];
            }
            if(!empty($input['coupon_quota'])){
                $data['coupon_quota'] = $input['coupon_quota'];
            }
            DB::beginTransaction();
            try {
                $data['create_time'] = time();
                $data['vaild_end_time'] = $vaild_end_time;
                $data['vaild_start_time'] = $vaild_start_time;
                $data['dataflag'] = 1;
                $data['coupon_name'] = $input['coupon_name'];
                $data['coupon_desc'] = $input['coupon_desc'];
                $data['coupon_type'] = $input['coupon_type'];
                $data['coupon_money'] = $input['coupon_money'];
                $data['coupon_quota'] = $input['coupon_quota'];
                $data['send_num'] = $input['send_num'];
                $couponId = DB::table('coupon')->insertGetId($data);
                $result=DB::table('coupon_side')->insert(
                    array(
                        'coupon_id'=>$couponId,
                        'coupon_name'=>$input['coupon_name_en'],
                        'coupon_desc'=>$input['coupon_desc_en'],
                        'create_time'=>time(),
                    )
                );
                if($couponId && $result){
                    DB::commit();
                    return true;
                }
            }catch(\Exception $e) {
                DB::rollBack();
                return false;
            }
        } else {
            return false;
        }
    }

    public function SaveData($input, $vaild_end_time, $vaild_start_time)
    {
        $data = array();
        if (!empty($input)) {
            if(!empty($input['goods_id'])){
                $data['goods_id'] = $input['goods_id'];
            }
            if(!empty($input['coupon_quota'])){
                $data['coupon_quota'] = $input['coupon_quota'];
            }
            $data['vaild_end_time'] = $vaild_end_time;
            $data['vaild_start_time'] = $vaild_start_time;
            $data['dataflag'] = 1;
            $data['coupon_name'] = $input['coupon_name'];
            $data['coupon_desc'] = $input['coupon_desc'];
            $data['coupon_type'] = $input['coupon_type'];
            $data['coupon_money'] = $input['coupon_money'];
            $data['send_num'] = $input['send_num'];
            return DB::table('coupon')->where(array('coupon_id'=>$input['coupon_id']))->update($data);
        } else {
            return false;
        }
    }

    public function DataList($type ,$coupon_name,$vaild_start_time,$vaild_end_time)
    {
        $where = array();
        $where['dataflag'] = 1;
        if ($type!=0) {
            $where['coupon_type'] = $type;
        }
        $data = DB::table('coupon')->where($where)
            ->where(function($query) use ($vaild_start_time,$vaild_end_time){
                if(!empty($vaild_start_time) && !empty($vaild_end_time)){
                    $vaild_start_time = strtotime($vaild_start_time);
                    $vaild_end_time = strtotime($vaild_end_time);
                    $query->where('vaild_start_time', '>', $vaild_start_time)
                    ->where('vaild_end_time', '<', $vaild_end_time);
                }elseif(!empty($vaild_start_time)){
                    $vaild_start_time = strtotime($vaild_start_time);
                    $query->where('vaild_start_time', '>', $vaild_start_time);
                }elseif(!empty($vaild_end_time)){
                    $vaild_end_time = strtotime($vaild_end_time);
                    $query->where('vaild_end_time', '<', $vaild_end_time);
                }
            })
            ->orderby('create_time','desc')
            ->paginate(5);
        return $data;
    }

    public function DataDel($where){
        if(!empty($where)){
            return DB::table('coupon')->where($where)->delete();
        }else{
            return false;
        }

    }
}
