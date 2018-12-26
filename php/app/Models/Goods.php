<?php

namespace App\Models;

use DB;
class Goods extends Base
{
    /**
     * 允许赋值的字段
     *
     * @var array
     */
    protected $fillable = [''];
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = '';

    /**
     * 指定是否模型应该被戳记时间。
     *
     * @var bool
     */
    public $timestamps = false;


    public function goodsList($where=array(),$user_id,$c_id,$field)
    {
        $result =  object_array(DB::table('container_goods')
            ->leftJoin('goods', 'container_goods.goods_id', '=', 'goods.goods_id')
            ->leftJoin('goods_side', 'goods.goods_id', '=', 'goods_side.goods_id')
            ->leftJoin('category', 'goods.cat_id', '=', 'category.id')
            ->select('goods.goods_id','original_price','present_price','main_img','goods_name',$field,'category.img')
            ->where($where)
            ->where(['inventory_status'=>1,'status'=>1])//上架且未售罄
            ->orderBy('sorts', 'asc')
            ->get()
            ->toArray());
        $goods_id = DB::table('shop_car')->where(['user_id'=>$user_id,'c_id'=>$c_id])->pluck('goods_id')->toArray();
        foreach ($result as $key => $value) {
            if(in_array($value['goods_id'],$goods_id)){
                $result[$key]['iscar'] = true;
            }else{
                $result[$key]['iscar'] = false;
            }
        }
        if($result){
            foreach ($result as $k => $v) {
                $arr[$v[$field]]['name'] = $v[$field];
                $arr[$v[$field]]['sonData'][] = $v;
            }
            $i = 0;
            foreach ($arr as $key => $value) {
                $arrt[$i] = $value;
                $i++;
            }
            return $arrt;

        }else{
            return false;
        }

    }

    /**
     * @return mixed  查找商品信息
     */
    public function findGoods( $where,$field = '*' ){
        $return = object_array(DB::table('goods as g')
            ->where($where)
            ->select($field)
            ->leftJoin('goods_side as s','g.goods_id','=','s.goods_id')
            ->first());
        return $return;
    }

    /**
     * @return mixed  查找数据 分页
     */
    public function datasPage($data) {
        $result = DB::table('goods')->where(function ($query) use ($data) {
            if (!empty($data['status'])) {
                $query->where('goods.status', $data['status']);
            }
            if (!empty($data['cate_id'])) {
                $query->where('category.id', $data['cate_id']);
            }
            if (!empty($data['goods_name'])) {
                $query->where('goods_side.goods_name','like', '%'.$data['goods_name'].'%');
            }
            if (!empty($data['barcode'])) {
                $query->where('goods.barcode',$data['barcode']);
            }
        })
            ->leftJoin('goods_side', 'goods.goods_id', '=', 'goods_side.goods_id')
            ->leftJoin('category', 'category.id', '=', 'goods.cat_id')
            ->pluck('goods.goods_id')
            ->toArray();
        $list = DB::table('goods')
            ->select('category.name','goods.cat_id','goods.sold','goods.inventory','goods.goods_id','original_price','present_price','barcode','main_img','order','status','create_time','inventory','goods_name','goods_introduction','goods_description')
            ->leftJoin('goods_side', 'goods.goods_id', '=', 'goods_side.goods_id')
            ->leftJoin('category', 'category.id', '=', 'goods.cat_id')
            ->where(['goods.is_del'=>1,'goods_side.languages'=>1])
            ->whereIn('goods_side.goods_id',$result)
            ->orderBy('goods_id','desc')
            ->paginate(8);
        return $list;
    }


    /**
     *  增加库存
     * @param $where
     * @param $num
     * @return mixed
     */
    public function addGoodsNum($where,$field,$num) {
        if($where) {
            return DB::table('goods')->where($where)->increment($field,$num);
        }else {
            return false;
        }
    }

    /**
     *  减少库存
     * @param $where
     * @param $num
     * @return mixed
     */
    public function reduceGoodsNum($where,$field,$num) {
        if($where) {
            return DB::table('goods')->where($where)->increment($field,$num);
        }else {
            return false;
        }
    }

    /**
     * lj
     *  查看详情
     * @param $where
     */
    public function showDetails($where) {
        if($where) {
            $list = object_array(DB::table('goods')
                ->select('goods_side.id','goods.goods_id','goods_name','goods_introduction','goods_description')
                ->leftJoin('goods_side', 'goods.goods_id', '=', 'goods_side.goods_id')
                ->where($where)
                ->get()
                ->toArray());

            // 重新定义键名
            $keyArr = ['China', 'English'];
            $newList = array();
            foreach ($list as $key => $value) {
                $newList[$keyArr[$key]] = $value;
            }
            return $newList;
        }else {
            return false;
        }
    }


    /**
     * lj
     * 查看详情 关联分类表
     * @param $where
     * @param $file
     * @return array
     */
    public function goodsDetails($where,$file) {
        if($where) {
            $list = object_array(DB::table('goods as g')
                ->select($file)
                ->leftJoin('category as c', 'c.id', '=', 'g.cat_id')
                ->where($where)
                ->first());
            return $list;
        }
    }


    /**
     *  lj
     *  软删除
     * @param $where
     * @return mixed
     */
    public function deletes($where,$data) {
        return DB::table('goods')->where($where)->update($data);
    }


    /**
     *  lj
     *  查找信息
     * @param $where
     * @param string $field
     * @return bool
     */
    public function datasFind($where,$field='*'){
        // return DB::select('select * from admin where admin_id = ?', [$id]);
        if(is_array($where)){
            return DB::table('goods')->select($field)->where($where)->first();
        }else{
            return false;
        }
    }

    public function FindField($field,$where,$ids){
        if(is_array($where)){
            return DB::table('goods')->select($field)->where($where)->whereNotIn('goods_id',$ids)->get();
        }else{
            return false;
        }
    }

    public function whereIds($field,$where)
    {
        if(is_array($where)){
            return DB::table('goods')->select($field)->whereIn('goods_id',$where)->get()->toArray();
        }else{
            return false;
        }
    }

    public function GoodsDamage($where,$page){
        $NewWhere=array();
        $goods_name=$where['goods_name'];
        if(!empty($where['damage_type']))$NewWhere['damage_type']=$where['damage_type'];
        if(!empty($where['c_id']))$NewWhere['c_id']=$where['c_id'];
        if(!empty($where['h_id']))$NewWhere['container_damage.hotel_id']=$where['h_id'];
        $start_time=$where['start_time'];
        $end_time=$where['end_time'];
        $data=DB::table('container_damage')
            ->select('container_damage.damage_type','container_damage.c_id','container_damage.goods_name','container_damage.damage_time','hotel.name_en','hotel.name','room.room_number','container.container_number')
            ->leftJoin('container','container_damage.c_id','=','container.id')
            ->leftJoin('hotel','container_damage.hotel_id','=','hotel.id')
            ->leftJoin('room','container_damage.room_id','=','room.id')
            ->where('goods_name','like',"".$where['goods_name']."%")
            ->where($NewWhere)
            ->where(function($query) use ($start_time,$end_time){
                if(!empty($start_time) && !empty($end_time)){
                    $start_time = strtotime($start_time);
                    $end_time = strtotime($end_time);
                    $query->whereBetween('damage_time', array($start_time,$end_time));
                }elseif(!empty($start_time)){
                    $start_time = strtotime($start_time);
                    $query->where('damage_time', '>', $start_time);
                }elseif(!empty($end_time)){
                    $end_time = strtotime($end_time);
                    $query->where('damage_time', '<', $end_time);
                }
            })
            ->orderBy('damage_time','desc')
            ->paginate($page);
        return $data;
    }


    public function GoodsExport($where){
        if(!empty($where['h_id'])|| !empty('start_time') || !empty('end_time')){
            $NewWhere=array();
            if(!empty($where['h_id']))$NewWhere['container_damage.hotel_id']=$where['h_id'];
            $start_time=$where['start_time'];
            $end_time=$where['end_time'];
        $data=DB::table('container_damage')
            ->select('container_damage.damage_type','container_damage.c_id','container_damage.goods_name','container_damage.damage_time','hotel.name_en','hotel.name','room.room_number','container.container_number')
            ->leftJoin('container','container_damage.c_id','=','container.id')
            ->leftJoin('hotel','container_damage.hotel_id','=','hotel.id')
            ->leftJoin('room','container_damage.room_id','=','room.id')
            ->where($NewWhere)
            ->where(function($query) use ($start_time,$end_time){
                if(!empty($start_time) && !empty($end_time)){
                    $start_time = strtotime($start_time);
                    $end_time = strtotime($end_time);
                    $query->whereBetween('damage_time', array($start_time,$end_time));
                }elseif(!empty($start_time)){
                    $start_time = strtotime($start_time);
                    $query->where('damage_time', '>', $start_time);
                }elseif(!empty($end_time)){
                    $end_time = strtotime($end_time);
                    $query->where('damage_time', '<', $end_time);
                }
            })
            ->orderBy('damage_time','desc')
            ->get();
        return $data;
        }
    }
}
