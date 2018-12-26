<?php
//货柜（体验店）模型 lzt
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
class Container extends Base
{
    protected $table = 'container';

    
    /**
     * 后台货柜列表
     * @author lzt
     * @DateTime 2018-07-19
     * @param    [type]                   $where [description]
     * @param    [type]                   $data  [description]
     * @return   [type]                          [description]
     */
    public function datasPage($data,$select){

            $where =array();
            //体验店编号
            if(!empty($data['container_number'])){
                $where['container_number'] = $data['container_number'];
            }
            //酒店Id
            if(!empty($data['h_id'])){
                $where['hotel_id'] = $data['h_id'];
            }
            //体验店状态
            if(!empty($data['status'])){
                $where['status'] = $data['status'];
            }
            $where['is_del'] = 1;
            //查询数据
            $list =  DB::table('container')->where($where)->select($select)->orderBy('status','asc')->orderBy('id','desc')->paginate(10);
            //对象转换成数据方法
            $cont =  json_decode(json_encode($list,true),true);
            $contList =  $cont['data'];
            if(!empty($contList)){
                $h_ids = $eWhere = array();
                //查询房间号并组装酒店和报修查询条件
                //"select `id`, `hotel_id`, `room_number` from `mm_room` where ((`id` = ? and `hotel_id` = ?) or (`id` = ? and `hotel_id` = ?) or (`id` = ? and `hotel_id` = ?))"
                $room =  DB::table('room')
                    ->where(function($query) use($contList,&$h_ids,&$eWhere){
                        foreach ($contList as $k=>$v){
                            //拼接查询条件
                            $query->orwhere(function ($query) use($v) {
                                $query->where('id', '=', $v['room_id'])->where('hotel_id', '=', $v['hotel_id']);
                            });
                            //组装酒店和报修查询条件
                            if(!empty($v['hotel_id'])){
                                $h_ids[] = $v['hotel_id'];//酒店
                            }
                            $eWhere[] = $v['id'];//报修
                        }
                    })
                    ->select(['id','hotel_id','room_number'])
                    ->get()->keyBy('id')
                    ->toArray();

                //查询酒店名称
                if(!empty($h_ids)){
                    $filed =array('id','name');
                    $hotels =  DB::table('hotel')->wherein('id',$h_ids)->select($filed)->get()->keyBy('id')->toArray();
                }else{
                    $hotels = '';
                }
                //获取报修次数
                $error =  DB::table('container_error')->wherein('c_id',$eWhere)
                    ->select('c_id',DB::raw('count(id) as error_count'))
                    ->orderBy('error_count')
                    ->groupBy('c_id')
                    ->get()->keyBy('c_id')
                    ->toArray();
                //获取商品种类
                $zNum =  DB::table('container_goods')->wherein('c_id',$eWhere)
                    ->select('c_id',DB::raw('count(id) as goods_count'))
                    ->groupBy('c_id')
                    ->get()->keyBy('c_id')
                    ->toArray();

                //获取剩余库存
                $Num =  DB::table('container_goods')->wherein('c_id',$eWhere)
                    ->select('c_id',DB::raw('count(id) as goods_count'))
                    ->where('inventory_status','=','1')
                    ->groupBy('c_id')
                    ->get()->keyBy('c_id')
                    ->toArray();

                foreach ($contList as $k=>$v){
                    //商品种类
                    if(!empty($zNum[$v['id']])){
                        $contList[$k]['z_num'] = $zNum[$v['id']]->goods_count;
                    }else{
                        $contList[$k]['z_num'] = 0;
                    }

                    //获取剩余库存
                    if(!empty($Num[$v['id']])){
                        $contList[$k]['num'] = $Num[$v['id']]->goods_count;
                    }else{
                        $contList[$k]['num'] = 0;
                    }

                    //酒店
                    if(!empty($v['hotel_id'])&&!empty($hotels[$v['hotel_id']])){
                        $contList[$k]['hotel_name'] = $hotels[$v['hotel_id']]->name;
                    }else{
                        $contList[$k]['hotel_name'] = '-';
                    }
                    //报修
                    if(!empty($error[$v['id']])){
                        $contList[$k]['error_num'] = $error[$v['id']]->error_count;
                    }else{
                        $contList[$k]['error_num'] = 0;
                    }
                    //房间
                    if(!empty($v['room_id'])&&!empty($room[$v['room_id']])){
                        $contList[$k]['room_name'] = $room[$v['room_id']]->room_number;
                    }else{
                        $contList[$k]['room_name'] = '-';
                    }
                }
                //$cont['data'] = $contList;
            }
            $info['list'] =$list;
            $info['contList'] =$contList;

            return $info;
    }

    /**查询商品 lzt
     * @return mixed  查找数据
     */
    public function datasIn($where){

        $field = array('g.barcode','g.goods_id','g.present_price','s.goods_name');
        $res =  DB::table('goods as g')
                ->leftJoin('goods_side as s', 'g.goods_id', '=', 's.goods_id')
                ->select($field)->wherein('g.barcode',$where)->where('s.languages','=','1')
            ->where('g.status','=','1')
            ->get()->keyBy('barcode')->toArray();
        return $res;
    }


    /**
     * 后台订单详情
     * @author lj
     * @DateTime 2018-07-05T17:45:20+0800
     * @param    [type]                   $where [description]
     * @return   [type]                          [description]
     */
    public function orderList($where,$field) {
        $resutl =DB::table('order as o')
            ->leftJoin('users as u', 'u.user_id', '=', 'o.user_id')
            ->where($where)
            ->select($field)
            ->get()
            ->toArray();
        return $resutl;
    }
    
    /**
     * 后台订单详情
     * @author lzt
     * @DateTime 2018-07-05T17:45:20+0800
     * @param    [type]                   $where [description]
     * @return   [type]                          [description]
     */
    public function infoList($where,$field)
    {
        $resutl =DB::table('order_side as o')
            ->leftJoin('goods_side as s', 'o.goods_id', '=', 's.goods_id')
            ->leftJoin('goods as g', 'o.goods_id', '=', 'g.goods_id')
            ->where($where)
            ->select($field)
            ->get()
            ->toArray();
        return $resutl;
    }


    /**
     *  lj
     *  修改货柜商品状态
     * @param $where
     * @param $num
     * @return mixed
     */
    public function goodsContainer($where,$data) {
        if($where) {
            return DB::table('container_goods')->where($where)->update($data);
        }else {
            return false;
        }
    }

    /**
     *  lj
     *  修改货柜商品状态
     * @param $where
     * @param $num
     * @return mixed
     */
    public function goodsContainerUpdata($where,$wherein,$data) {
        if($where) {
            return DB::table('container_goods')->where($where)->whereIn('goods_id',$wherein)->update($data);
        }else {
            return false;
        }
    }


    /**
     *  lzt
     *  添加货柜及货柜商品表信息
     * @param $where
     * @param $num
     * @return mixed
     */
    public function addContainer($data,$img) {
       // dump($data);
        $time= time();
//dump($data);
        DB::beginTransaction();//开启事务
        //组装体验店表数据
        $conInsert['status'] = $data['status'];
        $conInsert['container_number'] = $data['container_number'];
        $conInsert['lock_code'] = $data['code'] == Null ? Null :$data['code'];
        $conInsert['img'] = $img;
        $conInsert['create_time'] = $time;
    //    dump($conInsert);
        $id =  DB::table('container')->insertGetId($conInsert);
       // dump($id);
        if($id){
            $ids = $this->addContainerGoods($id,$data['goods']);
           // dump($ids);
            if($ids){
                DB::commit();//成功，提交事务
                return  true;
            }else{
                DB::rollBack();//失败，回滚事务
                return  false;
            }
        }else{
            DB::rollBack();//失败，回滚事务
            return  false;
        }
    }

    /**
     *  lzt
     *  添加货柜及货柜商品表信息
     * @param $where
     * @param $num
     * @return mixed
     */
    public function addContainerGoods($id,$data) {
       // dump($data); die;
        $time= time();
        if($id){
           // dump($aa);
            //组装体验店商品表数据
            $goodInsert = array();
            foreach($data as $k=>$v){
                $goodInsert[$k]['c_id'] = $id;
                $goodInsert[$k]['goods_id'] = $v['goods_id'];
                $goodInsert[$k]['barcode'] = $v['barcode'];
                $goodInsert[$k]['level'] = isset($v['level']) ? $v['level'] : 1;
                $goodInsert[$k]['create_time'] = $time;
            }
//            dump($goodInsert); die;
            $ids =  DB::table('container_goods')->insert($goodInsert);
           // dump($ids);
            if($ids){
                return  true;
            }else{
                return  false;
            }
        }else{
            return  false;
        }
    }


    /**查询货柜商品 lzt
     * @return mixed  查找数据
     */
    public function editContainer($id){
     
        //查询主表数据
        $info['info'] =  DB::table('container')->select(array('id','status','container_number','img','lock_code'))->where('id','=',$id )->first();
     
        //查询商品货柜信息
        $field = array('g.barcode','g.goods_id','g.present_price','s.goods_name','c.level','c.id','c.inventory_status');
        $info['goods'] =  DB::table('container_goods as c')
            ->leftJoin('goods as g', 'g.goods_id', '=', 'c.goods_id')
            ->leftJoin('goods_side as s', 's.goods_id', '=', 'c.goods_id')
            ->select($field)->where('c.c_id','=',$id)->where('s.languages','=','1')
            ->orderBy('c.level','asc')
            ->get()->keyBy('barcode')->toArray();
        return $info;
    }

    /**查询货柜商品是否存在 lzt
     * @return mixed  查找数据
     */
    public function CheckContainerGoods($id){
        
        //查询商品货柜信息
        $field = array('barcode');
        $info =  DB::table('container_goods')
            ->select($field)->where('c_id','=',$id)
            ->get()->toArray();
        return $info;
    }
    /**
     *  lzt
     *  编辑货柜商品表信息
     * @param $where
     * @param $num
     * @return mixed
     */
    public function editContainerGoods($id,$data,$img) {
        $info=DB::table('container')->select('img')->where('id', '=', $id)->first();
        if(empty($info->img)){
          DB::table('container')->where('id', '=', $id)->update(array('img'=>$img));
        }
        // dump($data); die;
        $time= time();
        //查询数据库中已有的商品；
        $field = array('goods_id','barcode','inventory_status','id');
        $acontainer_goods =  DB::table('container_goods')->select($field)->where('c_id','=',$id)->get()->keyBy('goods_id')->toArray();
        //比较提交数据与数据库的差异得出删除与追加的数据；

            //取两者并集$w
        $w = $delete= $goods_ids = $goodInsert= array();
 
            foreach($acontainer_goods as $k =>$v){
                if(!empty($data[$k])){
                    $w[$k] = $v;
                }
            }
            //取$w与数据库的差集（删除的数据）
            $a = $b = $c =2; //设置标记判断后续操作；
            foreach($acontainer_goods as $k =>$v){
                if(empty($w[$k])){
                    $delete[] = $v->id;  //需要删除的数据
                    if($v->inventory_status ==1){  //判断状态增加需要还原商品表库存的数据
                        $goods_ids[] = $v->goods_id;
                        $b = 1;
                    }
                    $a  = 1;
                }
            }
            //取$w与提交数据的差集（追加的数据）
            foreach($data as $k =>$v){
                if(empty($w[$k])){
                    $goodInsert[$k]['c_id'] = $id;
                    $goodInsert[$k]['goods_id'] = $v['goods_id'];
                    $goodInsert[$k]['barcode'] = $v['barcode'];
                    $goodInsert[$k]['level'] = isset($v['level']) ? $v['level'] : 1;
                    $goodInsert[$k]['create_time'] = $time;
                    $c = 1;
                }
            }
        //开启事务
        DB::beginTransaction();
        
        //删除数据库中存在；提交数据中不存在的数据库商品。
        if($a == 1 ) {
            $res1 = DB::table('container_goods')->where('c_id', '=', $id)->whereIn('id', $delete)->delete();
//            $res1 = DB::table('container')->where('id', '=', $id)->whereIn('id', $delete)->delete();
        }else{
            $res1= 1;
        }
        //增加商品表库存。
        if($b == 1 ){
            $res2=    DB::table('goods')->whereIn('goods_id',$goods_ids)->increment('inventory',1);
            //DB::table(‘test‘)->where([‘id‘=> 1])->increment(‘num‘, 5);  //自增    //tp中 setInc()
            //DB::table(‘test‘)->where([‘id‘=> 2])->decrement(‘num‘, 5);            //tp中 setDec()
        }else{
            $res2 = 1;
        }

        //追加数据库中不存在；提交数据中存在的商品；
        if($c == 1 ){
            $res3 =  DB::table('container_goods')->insert($goodInsert);
        }else{
            $res3 = 1;
        }
        if($res1&&$res2&&$res3){
            DB::commit();  //成功提交
           return  true;
        }else{
            DB::rollBack();  //失败返回
            return false;
        }


    }
    //查询未绑定酒店房间号的体验间编号
    public function Unbound(){
        $result=$this->select('id','container_number')->where(['hotel_id'=>0,
            'room_id'=>0,'is_del'=>1,'status'=>1])->get();
        return $result;
    }
}
