<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class Hotel extends Base
{	

	protected $table = 'hotel';
	/**
	 * 添加酒店
	 * @author j
	 * @DateTime 2018-07-12T14:15:23+0800
	 * @param    [type]                   $data [description]
	 */
	public function add($data)
	{
		$add['name'] = $data['name'];
		$add['name_en'] = $data['name_en'];
		$add['address'] = $data['provinceName'].$data['cityName'].$data['districtName'].$data['address'];
		$add['address_en'] = $data['address_en'];
		$add['front_phone'] = $data['front_phone'];
		$add['create_time'] = time();
		DB::beginTransaction(); 
		$result = DB::table('hotel')->insertGetId($add);   
		if($result){
			foreach ($data['jlname'] as $k => $v) {
				$add1[]=[
					'hotel_id'=>$result,
					'name'=>$data['jlname'][$k],
					'tel'=>$data['tel'][$k],
					'number'=>$data['number'][$k],
					'password'=>Crypt::encrypt($data['password'][$k]),
					'create_time' => time()
				];
			}
			$result1 = DB::table('hotel_manager')->insert($add1);
		}
		if($result1){
			DB::commit();
			return true;
		}else{
			DB::rollback();
			return false;
		}
	}
    /**
     * 酒店列表
     * @author j
     * @DateTime 2018-07-12T14:15:38+0800
     * @return   [type]                   [description]
     */
    public function hotelList($where)
    {	
    	$order = !empty($where['orderby']) ? $where['orderby'] :'asc';
	           //查询商品余量
    	$result = 
    	$this     
    	->select('hotel.id',DB::raw('count(goods_id) as good_ov'),'hotel.name','name_en','address','front_phone')
    	->leftJoin('container','hotel.id','container.hotel_id')
        ->leftJoin('hotel_manager','hotel.id','hotel_manager.hotel_id')
    	->leftJoin('container_goods', function($join)
    	{
    		$join->on('container.id', '=', 'container_goods.c_id')
    		->where('inventory_status',1);

    	})
    	->where(function ($query) use ($where) {
    		if (!empty($where['id'])) {
    			$query->where('hotel.id', $where['id']);
    		}
    		if (!empty($where['tel'])) {
    			$query->where('hotel.front_phone',$where['tel']);
    		}
            if (!empty($where['jltel'])) {
                $query->where('hotel_manager.tel',$where['jltel']);
            }
    	})
    	->where(['hotel.is_del'=>1])
    	->orderBy('good_ov',$order) //先根据余量排序
    	->orderBy('hotel.id','asc')  //再根据id排序    
    	->groupBy('hotel.id')
    	->paginate(10);
    	foreach ($result as $key => $value) {
    		$aa[]=$value->id;
    	}
    		//房间数量统计
    	$result1 = 
    	$this
    	->select('hotel.id',DB::raw('count(room_number) as count'))
    	->leftJoin('room', function($join)
    	{
    		$join->on('hotel.id', '=', 'room.hotel_id')
    		->where(['room.is_del'=>1]);
    	})
    	->whereIn('hotel.id',$aa)
    	->orderByRaw("FIELD(mm_hotel.id, " . implode(", ", $aa) . ")") //根据wherein中的顺序排序     
    	->groupBy('hotel.id')
    	->get();
    	foreach ($result as $key => $value) {
    		$result[$key]->count = $result1[$key]['count'];
    	}
    		//商品总量统计
    	$result2 = 
    	$this
    	->select('hotel.id',DB::raw('count(goods_id) as good_count'))
    	->leftJoin('container','hotel.id','container.hotel_id')
    	->leftJoin('container_goods', 'container.id', '=', 'container_goods.c_id')
    	->whereIn('hotel.id',$aa)
    	->orderByRaw("FIELD(mm_hotel.id, " . implode(", ", $aa) . ")")    //根据wherein中的顺序排序     
    	->groupBy('hotel.id')
    	->get();
    	foreach ($result2 as $key => $value) {
    		$result[$key]->good_count = $result2[$key]['good_count'];
    	}
    		//商品销量统计
    	$result3 = 
    	$this
    	->select('hotel.id',DB::raw('count(order_id) as order_count'))
    	->leftJoin('order', function($join)
    	{
    		$join->on('hotel.id', '=', 'order.h_id')
    		->where('status',2); 	
    	}) 
    	->whereIn('hotel.id',$aa)
    	->orderByRaw("FIELD(mm_hotel.id, " . implode(", ", $aa) . ")")   //根据wherein中的顺序排序   
    	->groupBy('hotel.id')
    	->get();
    	foreach ($result3 as $key => $value) {
    		$result[$key]->order_count = $result3[$key]['order_count'];
    	}
    	return $result;
    }
    /**
     * 查询酒店详情
     * @author j
     * @DateTime 2018-07-13T16:15:17+0800
     * @param    [type]                   $id [description]
     * @return   [type]                       [description]
     */
    public function findHotel($id)
    {
    	$result = 
    	$this
    	->select('id','name','name_en','address','address_en','front_phone')
    	->where(['id'=> $id ])
    	->first();
    	$result->tags = DB::table('hotel_manager')->where(['hotel_id'=>$id,'is_disable'=>1])->select('name','tel','id as hid','number','password')->get();
    	return $result;
    }
    /**
     * 酒店编辑
     * @author j
     * @DateTime 2018-08-03T16:39:07+0800
     * @param    [type]                   $request [description]
     * @return   [type]                            [description]
     */
    public function updates($request)
    {
        // $arr = $request->all();
        // p($arr);die;
         
    	$map=[
    		'id'=>$request->id
    	];
    	$data = array_only($request->all(), ['name', 'name_en','address','address_en','front_phone']);
    	DB::beginTransaction();
    	$result = $this->where($map)->update($data);//修改酒店表
    	$mo = 1;

    	if($request->hid){
            $hid = array_unique($request->hid);
            $result1  = DB::table('hotel_manager')->whereIn('id',$hid)->delete();//删除原有的重新添加
    		$data1 = array_only($request->all(), ['tel','number','password']);
    		foreach ($data1['tel'] as $k => $value) {
                $arr[$k]['hotel_id']=$request->id;
    			$arr[$k]['tel']=$data1['tel'][$k];
    			$arr[$k]['number']=$data1['number'][$k];
    			$arr[$k]['password']=$data1['password'][$k];
                $arr[$k]['create_time']=time();
    		}
    		$result2 = DB::table('hotel_manager')->insert($arr);
	    	if($result && $result1 && $result2){
	    		$mo = 1;
	    	}else{
	    		$mo = 0;
	    	}
	    	
	    }else{
	    	DB::rollback();
	    	return false;
	    }
	    if($mo){
	    	DB::commit();
	    	return true;
	    }else{
	    	DB::rollback();
	    	return false;
	    }
	}
    /**
     * 删除酒店经理方法
     * @author j
     * @DateTime 2018-08-03T16:24:59+0800
     * @param    [type]                   $id [description]
     * @return   [type]                       [description]
     */
    public function delmag($id)
    {
        $result = DB::table('hotel_manager')->where('id',$id)->delete();
        return $result;
    }


}
