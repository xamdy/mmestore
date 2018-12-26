<?php

namespace App\Models;
use DB;
use App\Models\Container;
class Room extends Base
{
	protected $table = 'room';
    /**
     * 房间列表
     * @author j
     * @DateTime 2018-07-24T14:19:12+0800
     * @param    [type]                   $where [description]
     * @return   [type]                          [description]
     */
    public function roomlList($where)
    {
      $result = $this
      ->leftJoin('container','room.id','container.room_id')
      ->leftJoin('container_goods as g','container.id','=','g.c_id')
      ->leftJoin('hotel as l','room.hotel_id','=','l.id') 
      ->leftJoin('goods as gs', function($join)
        {
          $join->on('g.goods_id','=','gs.goods_id')
          ->where('gs.status',1);
          
        })
      ->select('l.name','l.name_en','room.id','room.room_number','room.room_name',DB::raw('count(mm_gs.goods_id) as goods_count'))
      ->where(function ($query) use ($where) {
        if (!empty($where['id'])) {
           $query->where('l.id', $where['id']);
         }
         if (!empty($where['room_name'])) {
          $query->where('room.room_name', $where['room_name']);
        }
      })
      ->groupBy('room.id')
      ->paginate(10);
      if(count($result)){
        foreach ($result as $key => $value) {
          $aa[]=$value->id;
        }
        $result1 = $this
        ->leftJoin('container','room.id','container.room_id')
        ->leftJoin('container_goods as g', function($join)
        {
          $join->on('container.id','=','g.c_id')
          ->where('inventory_status',1);
        })
        ->leftJoin('goods as gs', function($join)
        {
          $join->on('g.goods_id','=','gs.goods_id')
          ->where('gs.status',1);
        })
        ->select(DB::raw('count(mm_gs.goods_id) as goods_num'))
        ->whereIn('room.id',$aa) 
        ->groupBy('room.id')
        ->get();
        foreach ($result as $key => $value) {
          $result[$key]->goods_num = $result1[$key]->goods_num;
        }

        

      }
      return $result;

    }
   	/**
   	 * 添加房间绑定货柜
   	 * @author j
   	 * @DateTime 2018-07-19T09:59:01+0800
   	 * @param    [type]                   $data [description]
   	 * @return   [type]                         [description]
   	 */
   	public function addroom($data)
   	{
   		$data1 = array_only($data, ['hotel_id','room_name', 'room_number']);
   		$data1['create_time'] = time();
   		DB::beginTransaction();
   		$id = $this->insertGetId($data1);
   		if($id){
   			$save['hotel_id'] = $data['hotel_id'];
   			$save['room_id'] = $id;
   			$result = Container::where(['id'=>$data['container_id']])->update($save);
   			if($result){
   				DB::commit();
   				return true;
   			}else{
   				DB::rollback();
           return false;
         }
       }else{
         DB::rollback();
         return false;
       }
     }
   	/**
   	 * 房间详情查询
   	 * @author j
   	 * @DateTime 2018-07-19T10:29:38+0800
   	 * @param    [type]                   $id [description]
   	 * @return   [type]                       [description]
   	 */
   	public function findRoom($id)
   	{
   		$result = 
      $this
      ->select('hotel.id as h_id','room.id','room_name','room_number','name','container_number')
      ->leftJoin('hotel','room.hotel_id','=','hotel.id')
      ->leftJoin('container','room.id','=','container.room_id')
      ->where(['room.id'=> $id ])
      ->first();
      return $result;
    }

    /**
     * 查询房间商品列表
     * @author j
     * @DateTime 2018-07-24T11:14:06+0800
     * @param    [type]                   $id [description]
     * @return   [type]                       [description]
     */
    public function selectGlist($id)
    {
      $result = DB::table('container_goods')
      ->select('container_goods.id','room_id','container_goods.goods_id','inventory_status','goods_name')
      ->leftJoin('container','container_goods.c_id','container.id')
      ->leftJoin('goods_side','container_goods.goods_id','=','goods_side.goods_id')
      ->leftJoin('goods','container_goods.goods_id','=','goods.goods_id')
      ->where(['room_id'=>$id,'languages'=>1,'goods.status'=>1])
      ->orderBy('inventory_status','desc')
      ->paginate(10);
      return $result;
    }
    /**
     * 单品补货功能
     * @author j
     * @DateTime 2018-07-24T11:15:02+0800
     * @param    [type]                   $id [description]
     * @return   [type]                       [description]
     */
    public function addgood($id,$goods_id)
    {
      $data['inventory_status'] = 1;
      DB::beginTransaction();
      $result = DB::table('container_goods')->where('id',$id)->update($data);
      $num = DB::table('goods')->where('goods_id',$goods_id)->value('inventory');//查询库存剩余
      if($num>0){
          $result1 = DB::table('goods')->where('goods_id',$goods_id)->decrement('inventory');//库存减一
          if($result && $result1){
            DB::commit();
            return ['code'=>1,'msg'=>'添加成功'];
          }else{
            return ['code'=>2,'msg'=>'添加失败'];
            DB::rollback();
          }
        }else{
          return ['code'=>2,'msg'=>'该商品库存不足'];
          DB::rollback();
        }
      }
    /**
     * 一键补货
     * @author j
     * @DateTime 2018-07-24T11:38:32+0800
     * @param    [type]                   $id [description]
     * @return   [type]                       [description]
     */
    public function addallgood($id)
    {
      $resulta = DB::table('container_goods')
      ->leftJoin('container','container_goods.c_id','container.id')
      ->where(['room_id'=>$id])
      ->pluck('goods_id');
      if(count($resulta) == 0){
        return  ['code'=>2,'msg'=>'该房间货柜没有商品，请添加商品'];
      }
      //查询需要补货的商品id集合
      $result = DB::table('container_goods')
      ->leftJoin('container','container_goods.c_id','container.id')
      ->where(['inventory_status'=>2,'room_id'=>$id])
      ->pluck('goods_id');
      if(count($result) == 0){
        return  ['code'=>2,'msg'=>'无需补货'];
      }
      $data['inventory_status'] = 1;
      // 修改货柜商品库存状态
      $result3 = DB::table('container_goods')
      ->leftJoin('container','container_goods.c_id','container.id')
      ->leftJoin('goods','container_goods.goods_id','goods.goods_id')
      ->where('inventory','>',0)
      ->where('goods.status','=',1)
      ->where('room_id',$id)
      ->update($data);
      
      // 查询需要补货的商品，库存大于0的库存有多少
      $result1 = DB::table('goods')->where('inventory','>',0)->whereIn('goods_id',$result)->count();
      // 有库存的减库存
      $result2 = DB::table('goods')->where('inventory','>',0)->whereIn('goods_id',$result)->decrement('inventory');
      // 比较是否相等如果不等于说明有商品没有库存
      if($result1<count($result)){
        return  ['code'=>2,'msg'=>'部分商品已售罄，无法补货'];
      }else{
        return ['code'=>1,'msg'=>'一键补货成功'];
      }
    }

    /**
     * @param $data
     * @return array
     * 货损
     */
    public function CargoDamage($data)
    {
        if (!empty($data)) {
            $c_id = DB::table('container_goods')->where(array('id' => $data['id']))->value('c_id');
            $ids = DB::table('container')->select('hotel_id','room_id')->where(array('id' => $c_id))->first();
            if ($data['inventory_status'] == 1) {
                DB::beginTransaction();
                $roomNum = DB::table('container_goods')->where(array('id' => $data['id']))->update(array(
                    'inventory_status' => 2
                ));
                $goodsSum = DB::table('goods')->where('goods_id', $data['goods_id'])->decrement('inventory');
                $damageData = DB::table('container_damage')->insert(
                    array(
                        'c_id' => $c_id,
                        'hotel_id' => $ids->hotel_id,
                        'room_id' => $ids->room_id,
                        'goods_id' => $data['goods_id'],
                        'goods_name' => $data['goods_name'],
                        'damage_type' => $data['damage_type'],
                        'damage_time' => time(),
                    )
                );
                if ($roomNum && $goodsSum && $damageData) {
                    DB::commit();
                    return ['code' => 1, 'msg' => '添加成功'];
                } else {
                    return ['code' => 2, 'msg' => '添加失败'];
                    DB::rollback();
                }
            }
        }else{
            return ['code' => 3, 'msg' => '参数不能为空'];
        }
    }


  }
