<?php

namespace App\Models;

//use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
class Order extends Base
{
    protected $table = 'order';
    /**
     * 订单列表
     * @author j
     * @DateTime 2018-07-05T14:51:15+0800
     * @param    array                    $where [description]
     * @return   [type]                          [description]
     */
    public function orderList($where,$language)
    {
    	$orderArr = $this->where($where)
                    ->orderByRaw("case when status = 2 then 1 else 0 end desc")
                    ->orderBy('order_id','desc')
                    ->paginate(8)
                    ->pluck('order_id')
                    ->toArray();
    	if($orderArr){
                $quxiao = $language == 1 ? '已取消' :'cancelled';
                $wancheng = $language == 1 ? '已完成' :'completed';
    		foreach ($orderArr as $k=>$v) {
	    		$result[$k]['orderId'] = $v;
	    		$sonData = DB::table('order')
	    			->select('order.order_id',DB::raw("case when status=1 then '".$quxiao."' when status=2 then '".$wancheng."' when status=3 then '".$quxiao."'  end as status"),'order_number','user_id','order_amount','real_amount','creat_time','goods_side.goods_id','goods_side.goods_name','goods_price','goods_img','order_address')
	    			->leftJoin('order_side', 'order.order_id', '=', 'order_side.order_id')
	    			->leftJoin('goods_side', 'order_side.goods_id', '=', 'goods_side.goods_id')
	    			->where(['order_side.order_id'=>$v,'languages'=>$language])
	    			->get()
	    			->toArray();
	    			$result[$k]['sonData'] = $sonData;
	    			$result[$k]['count'] = count($sonData);
	    			$result[$k]['order_address'] = $sonData[0]->order_address;
	    			$result[$k]['creat_time'] = date('m-d H:i',$sonData[0]->creat_time);
	    			$result[$k]['status'] = $sonData[0]->status;
	    			$result[$k]['real_amount'] = $sonData[0]->real_amount;
    		}
    		return $result;
    	}else{
    		return 3;
    	}
    }
    /**
     * 订单详情
     * @author j
     * @DateTime 2018-07-05T17:45:20+0800
     * @param    [type]                   $where [description]
     * @return   [type]                          [description]
     */
    public function orderDetails($where,$languages) {
        $quxiao = $languages == 1 ? '已取消' :'cancelled';
        $wancheng = $languages == 1 ? '已完成' :'completed';
    	$result = object_array(DB::table('order')->select('status',DB::raw("case when status=1 then '".$quxiao."' when status=2 then '".$wancheng."' when status=3 then '".$quxiao."'  end as status"),'order_number','order_amount','real_amount','creat_time','order_side.goods_id','goods_price','goods_side.goods_name','goods_img')
    		->leftJoin('order_side', 'order.order_id', '=', 'order_side.order_id')
    		->leftJoin('goods_side', 'order_side.goods_id', '=', 'goods_side.goods_id')
    		->where($where)
    		->get()
    		->toArray());

        foreach ($result as $key => &$value) {
            $value['creat_time'] = date('Y-m-d H:i:s',$value['creat_time']);
        }
        foreach ($result as $key => $v) {
            $arr['status'] = $result[0]['status'];
            $arr['order_number'] = $result[0]['order_number'];
            $arr['real_amount'] = $result[0]['real_amount'];
            $arr['creat_time'] = $result[0]['creat_time'];
            $arr['sonData'][] = $v;
        }
    	return $arr;
    }
    /**
     * 订单软删除
     * @author j
     * @DateTime 2018-07-06T14:17:41+0800
     * @param    [type]                   $where [description]
     * @param    [type]                   $data  [description]
     * @return   [type]                          [description]
     */
    public function delOrders($where)
    {
        return $this->where($where)->delete();
    }
    /**
     * 恢复软删除的订单
     * @author j
     * @DateTime 2018-08-14T09:59:05+0800
     * @param    [type]                   $where [description]
     * @return   [type]                          [description]
     */
    public function recoveryOrder($where)
    {
        return $this->where($where)->restore();
    }
    /**
     * 后台订单列表
     * @author lzt
     * @DateTime 2018-07-11T14:17:41+0800
     * @param    [type]                   $where [description]
     * @param    [type]                   $data  [description]
     * @return   [type]                          [description]
     */
    public function datasPage($data,$select,$page){


            $where =array();
        //订单号
        if(!empty($data['order_number'])){
            $where['o.order_number'] = $data['order_number'];
        }
        //酒店Id
        if(!empty($data['h_id'])){
            $where['o.h_id'] = $data['h_id'];
        }
        //房间ID
        if(!empty($data['r_id'])){
            $where['o.r_id'] = $data['r_id'];
        }
        //手机号
        if(!empty($data['user_tel'])){
            $where['o.tel'] = $data['user_tel'];
        }
        //订单状态
        if(!empty($data['status'])){
            $where['o.status'] = $data['status'];
        }
        //会员ID
        if(!empty($data['user_id'])){
            $where['o.user_id'] = $data['user_id'];
        }

        //查询并返回数据
        return DB::table('order as o')
            ->where($where)
            ->where(function($query) use($data) {
                //根据起止时间条件判断查询查询范围
                if(!empty($data['start_time']) && !empty($data['end_time'])){
                    $start_time = strtotime($data['start_time']);
                    $end_time = strtotime($data['end_time']);
                    $query->whereBetween('o.creat_time', array($start_time,$end_time));
                }elseif(!empty($data['start_time'])){
                    $start_time = strtotime($data['start_time']);
                    $query->where('o.creat_time', '>', $start_time);
                }elseif(!empty($data['end_time'])){
                    $end_time = strtotime($data['end_time']);
                    $query->where('o.creat_time', '<', $end_time);
                }
            })
            ->orderBy('order_id','desc')
            ->select($select)->paginate($page);

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
     *  查看是否有该订单
     * @param $where
     * @param string $field
     */
    public function findOrder( $where,$field = '*' ) {
        return DB::table('order')->where($where)->select($field)->first();
    }


    /**
     *  lj
     * @param $whereIn  对应的whereIn字段
     * @param $where    where 条件
     * @param string $field   查询的字段
     * @return mixed
     */
    public function isContainer($whereIn,$where,$field = '*') {
        return DB::table('container_goods')->wherein('goods_id',$whereIn)->where($where)->select($field)->get()->toArray();
    }

    /**
     *  lzt
     *  查看是否有该订单
     * @param $where
     * @param string $field
     */
    public function OrderUpdata( $where,$field = '*' ) {
        return DB::table('order')->where($where)->select($field)->first();
    }

    /**
     *  lj
     *  查找商品信息
     * @param $where
     * @return mixed
     */
    public function findGoods($where,$languagess) {
        $result =object_array(DB::table('goods as g')
            ->leftJoin('goods_side as s','s.goods_id','=','g.goods_id')
            ->whereIn('g.goods_id',$where)
            ->select('g.goods_id','g.present_price','g.original_price','g.main_img','s.goods_name')
            ->where('s.languages','=',$languagess)
            ->get()
            ->toArray());
        return $result;
    }


    /**
     *  lj
     *  订单主表 附表 商品表关联
     * @param $where
     * @param $field
     * @return mixed
     */
    public function orderSide($where,$field)
    {
        $resutl =DB::table('order_side as o')
            ->leftJoin('order as r', 'r.order_id', '=', 'o.order_id')
            ->leftJoin('goods as g', 'o.goods_id', '=', 'g.goods_id')
            ->where($where)
            ->select($field)
            ->get()
            ->toArray();
        return $resutl;
    }


    /**
     * lj
     * 今日有效订单 和 销售金额
     * @return array
     */
    public function todayOrder() {
        // 获取到今日开始时间 和 截止时间
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $todayOrder = object_array(DB::table('order')
            ->select(DB::raw('count(order_id) as count'),DB::raw('sum(real_amount) as price'))
            ->where('status','=',2) // where条件
            ->where('pay_time', '>=', $beginToday)//当前月的开始时间
            ->where('pay_time', '<=', $endToday)//当前月的最后时间
            ->get()
            ->toArray()
        );
        return $todayOrder;
    }

    /**
     * lj
     * 本周有效订单 和 销售金额
     */
    public function weekOrder() {
        $beginLastweek = mktime(0, 0, 0, date('m'), date('d')-date('w')+1, date('Y'));
        $endLastweek = mktime(23, 59, 59, date('m'), date('d')-date('w')+7, date('Y'));
        $weekOrder = object_array(DB::table('order')
            ->select(DB::raw('count(order_id) as count'),DB::raw('sum(real_amount) as price'))
            ->where('status','=',2) // where条件
            ->where('pay_time', '>=', $beginLastweek)//当前月的开始时间
            ->where('pay_time', '<=', $endLastweek)//当前月的最后时间
            ->get()
            ->toArray()
        );
        return $weekOrder;
    }

    /**
     * lj
     * 本月有效订单 和 销售金额
     * @return array
     */
    public function monthOrder() {
        $beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
        $endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));

        $monthOrder = object_array(DB::table('order')
            ->select(DB::raw('count(order_id) as count'),DB::raw('sum(real_amount) as price'))
            ->where('status','=',2) // where条件
            ->where('pay_time', '>=', $beginThismonth)//当前月的开始时间
            ->where('pay_time', '<=', $endThismonth)//当前月的最后时间
            ->get()
            ->toArray()
        );
        return $monthOrder;
    }

    /**
     *  lj
     * 本月酒店订单排行
     */
    public function hotelOrderRank() {
        $beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
        $endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));
        // select `h_id`, count(h_id) as count, `mm_h`.`name` from `mm_order` as `mm_o` left join `mm_hotel` as `mm_h` on `mm_h`.`id` = `mm_o`.`h_id` where `status` = ? and `pay_time` >= ? and `pay_time` <= ? group by `h_id`
        $hotelRank = object_array(DB::table('order as o')
            ->leftJoin('hotel as h','h.id','=','o.h_id')
            ->select('h_id','h.create_time',DB::raw('count(h_id) as count'),'h.name')
//            ->select(DB::raw('count(h_id) as count'),'h_id','h.name')
            ->where('status','=',2) // where条件
            ->where('pay_time', '>=', $beginThismonth)//当前月的开始时间
            ->where('pay_time', '<=', $endThismonth)//当前月的最后时间
            ->orderBy('count','desc','h.create_time','desc')
            ->groupBy('h_id')
            ->limit(10)
            ->get()
            ->toArray()
        );
        return $hotelRank;
    }

    /**
     * lj
     * 本月酒店销售金额排行
     */
    public function hotelPriceRank() {
        $beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
        $endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));
        $hotelRank = object_array(DB::table('order as o')
            ->leftJoin('hotel as h','h.id','=','o.h_id')
            ->select('h_id','h.create_time',DB::raw('count(order_id) as count'),DB::raw('sum(real_amount) as price'),'h.name')
            ->where('status','=',2) // where条件
            ->where('pay_time', '>=', $beginThismonth)//当前月的开始时间
            ->where('pay_time', '<=', $endThismonth)//当前月的最后时间
            ->orderBy('price','desc','h.create_time','desc')
            ->groupBy('h_id')
            ->limit(10)
            ->get()
            ->toArray()
        );
        return $hotelRank;
    }


    /**
     * lj
     * 订单量排行  日 周 月
     */
    public function orderRank($type) {
        // 1 为按天算出前30天   2 为按周算出一季度的   3 为按月算出一年的
        if($type == 1) {
            $start = date("Y-m-d", time());
            $endtime= date("Y-m-d", strtotime("-1 month"));
            $days = date("t");
            $orderRank = object_array(DB::table('order')
                ->where('status','!=',0)                  // where条件
                ->where('created_at', '>=', $endtime)    // 大于当前时间往前推1一个时间
                ->where('created_at', '<=', $start)      // 小于当前时间
                ->groupBy('date')
                ->limit($days)
                ->get([DB::raw('DATE(created_at) as date'),DB::raw('COUNT(*) as value')])
                ->keyBy('date')
                ->toArray()
            );
            $newArray = array();
            for($i=0;$i<15;$i++) {
                $time= date("Y-m-d", strtotime("-$i day"));
                $newArray[$time] ='0';
                // 先测试数据
            }


            foreach($newArray as $k =>$v){
                if(!empty($orderRank[$k])){
                    $newArray[$k] = $orderRank[$k]['value'];
                }
            }
            ksort($newArray);

            return $newArray;

        }elseif($type == 2) {
            // 计算出本周
            $monday = strtotime(date('Y-m-d', (time() + (7 - (date('w') == 0 ? 7 : date('w'))) * 24 * 3600)));
            //由于每个月只有四周 让 $i 从 1 到 5 增加即可
            $newTime = array();
            for ($i=1; $i <= 12; $i++) {
                $start=date("Y-m-d",$monday-($i-1)*86400*7);   // 起始周一
                $end=date("Y-m-d",$monday-$i*86399*7);        // 结束周日
                $newTime[] = array(
                    'start' => $end,
                    'end' => $start,
                );
            }

            $endtime = date("Y-m-d", time());
            $starttime= strtotime(date("Y-m-d", strtotime("-3 month")));

            $orderRank = object_array(DB::table('order')
                ->where('status','!=',0) // where条件
                ->where('created_at', '>=', $starttime) // 大于当前时间往前推1一个时间
                ->where('created_at', '<=', $endtime)   // 小于当前时间
                ->groupBy('date')
                ->limit(12)
                ->get([DB::raw('DATE(created_at) as date'),DB::raw('COUNT(*) as value')])
                ->toArray()
            );
            $rankArray= [];
            foreach($newTime as $key => $value) {
                $keys =$value['start'].'.'.$value['end'];
                $rankArray[$keys] =$value;
                $rankArray[$keys]['value'] =0;
                foreach($orderRank as $k =>$v){
                    if(($v['date'] < $value['end']) && ($v['date'] >= $value['start'])) {
                        $rankArray[$keys]['value'] += $v['value'];
                        unset($orderRank[$k]);
                    }
                }
            }
            ksort($rankArray);
            return $rankArray;

        }elseif($type == 3) {
            $start = date("Y-m-d", time());
            $endtime= date("Y-m-d", strtotime("-1 year"));
            $orderRank = object_array(DB::table('order')
                ->where('status','!=',0) // where条件
                ->where('created_at', '>=', $endtime) // 大于当前时间往前推1一个时间
                ->where('created_at', '<=', $start)   // 小于当前时间
                ->groupBy('date')
                ->limit(12)
                ->get([DB::raw('DATE_FORMAT(created_at,\'%Y-%m\') as date'),DB::raw('COUNT(*) as value')])
                ->keyBy('date')
                ->toArray()
            );
            $newArray = array();
            for($n=0;$n<12;$n++) {
                $time= date("Y-m", strtotime("-$n month"));
                $newArray[$time] ='0';
            }

            foreach($newArray as $k =>$v){
                if(!empty($orderRank[$k])){
                    $newArray[$k] = $orderRank[$k]['value'];
                }
            }
            ksort($newArray);
            return $newArray;
        }
    }


    /**
     * lj
     * 销售金额排行  日 周 月
     */
    public function priceRank($type) {
        // 1 为按天算出前30天   2 为按周算出一季度的   3 为按月算出一年的
        if($type == 1) {
            $start = date("Y-m-d", time());
            $endtime= date("Y-m-d", strtotime("-1 month"));
            $days = date("t");
            $orderRank = object_array(DB::table('order')
                ->where('status','=',2)                  // where条件
                ->where('created_at', '>=', $endtime)    // 大于当前时间往前推1一个时间
                ->where('created_at', '<=', $start)      // 小于当前时间
                ->groupBy('date')
                ->limit($days)
                ->get([DB::raw('DATE(created_at) as date'),DB::raw('COUNT(*) as value'),DB::raw('sum(real_amount) as price')])
                ->keyBy('date')
                ->toArray()
            );
            $newArray = array();
            for($i=0;$i<15;$i++) {
                $time= date("Y-m-d", strtotime("-$i day"));
                $newArray[$time] ='0';
            }


            foreach($newArray as $k =>$v){
                if(!empty($orderRank[$k])){
                    $newArray[$k] = $orderRank[$k]['price'];
                }
            }
            ksort($newArray);
            return $newArray;

        }elseif($type == 2) {
            // 计算出本周
            $monday = strtotime(date('Y-m-d', (time() + (7 - (date('w') == 0 ? 7 : date('w'))) * 24 * 3600)));
            //由于每个月只有四周 让 $i 从 1 到 5 增加即可
            $newTime = array();
            for ($i=1; $i <= 12; $i++) {
                $start=date("Y-m-d",$monday-($i-1)*86400*7);   // 起始周一
                $end=date("Y-m-d",$monday-$i*86399*7);        // 结束周日
                $newTime[] = array(
                    'start' => $end,
                    'end' => $start,
                );
            }

            $endtime = date("Y-m-d", time());
            $starttime= strtotime(date("Y-m-d", strtotime("-3 month")));

            $orderRank = object_array(DB::table('order')
                ->where('status','=',2) // where条件
                ->where('created_at', '>=', $starttime) // 大于当前时间往前推1一个时间
                ->where('created_at', '<=', $endtime)   // 小于当前时间
                ->groupBy('date')
                ->limit(12)
                ->get([DB::raw('DATE(created_at) as date'),DB::raw('COUNT(*) as value'),DB::raw('sum(real_amount) as price')])
                ->toArray()
            );
            $rankArray= [];
            foreach($newTime as $key => $value) {
                $keys =$value['start'].'.'.$value['end'];
                $rankArray[$keys] = $value;
                $rankArray[$keys]['value'] =0;
                foreach($orderRank as $k =>$v){
                    if(($v['date'] < $value['end']) && ($v['date'] >= $value['start'])) {

                        $rankArray[$keys]['value'] += $v['price'];
                        unset($orderRank[$k]);
                    }
                }
            }
            ksort($rankArray);
            return $rankArray;

        }elseif($type == 3) {
            $start = date("Y-m-d", time());
            $endtime= date("Y-m-d", strtotime("-1 year"));
            $orderRank = object_array(DB::table('order')
                ->where('status','=',2) // where条件
                ->where('created_at', '>=', $endtime) // 大于当前时间往前推1一个时间
                ->where('created_at', '<=', $start)   // 小于当前时间
                ->groupBy('date')
                ->limit(12)
                ->get([DB::raw('DATE_FORMAT(created_at,\'%Y-%m\') as date'),DB::raw('COUNT(*) as value'),DB::raw('sum(real_amount) as price')])
                ->keyBy('date')
                ->toArray()
            );
            $newArray = array();
            for($n=0;$n<12;$n++) {
                $time= date("Y-m", strtotime("-$n month"));
                $newArray[$time] ='0';
            }

            foreach($newArray as $k =>$v){
                if(!empty($orderRank[$k])){
                    $newArray[$k] = $orderRank[$k]['price'];
                }
            }
            ksort($newArray);
            return $newArray;
        }
    }


    /**
     * 往前推7天
     * @param $start
     * @param $end
     * @return array
     */
    private function getweek($start, $end) {
        $ret = array();
        $i = 0;
        while($start <= $end){
            $ret[$i]['start'] = date('Y-m-d',$start);
            $tmp = strtotime("+6 days",$start);
            if($end <= $tmp)
                $ret[$i]['end'] = date('Y-m-d',$end);
            else
                $ret[$i]['end'] = date('Y-m-d',$tmp);
            $i++;
            $start = strtotime("+1 day",$tmp);
        }
        return $ret;
    }


    /****************   以下为商家端的方法   ***********************/
    /**
     * lj
     * 今日有效订单 和 销售金额 (当前酒店)
     * @param $where where 条件
     * @return array
     */
    public function hotelTodayOrder($where) {
        // 获取到今日开始时间 和 截止时间
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $todayOrder = object_array(DB::table('order')
            ->select(DB::raw('count(order_id) as count'),DB::raw('sum(real_amount) as price'))
            ->where($where) // where条件
            ->where('pay_time', '>=', $beginToday)//当天的开始时间
            ->where('pay_time', '<=', $endToday)//当天的最后时间
            ->get()
            ->toArray()
        );
        return $todayOrder;
    }


    /**
     * lj
     *  本周有效订单 和 销售金额 (当前酒店)
     * @param $where  条件
     * @return array
     */
    public function hotelWeekOrder($where) {
        $beginLastweek = mktime(0, 0, 0, date('m'), date('d')-date('w')+1, date('Y'));
        $endLastweek = mktime(23, 59, 59, date('m'), date('d')-date('w')+7, date('Y'));
        $weekOrder = object_array(DB::table('order')
            ->select(DB::raw('count(order_id) as count'),DB::raw('sum(real_amount) as price'))
            ->where($where) // where条件
            ->where('pay_time', '>=', $beginLastweek)  //当周的开始时间
            ->where('pay_time', '<=', $endLastweek)   //当周的最后时间
            ->get()
            ->toArray()
        );
        return $weekOrder;
    }


    /**
     * lj
     * 本月有效订单 和 销售金额 (当前酒店)
     * @param $where
     * @return array
     */
    public function hotelMonthOrder($where) {
        $beginThismonth=mktime(0,0,0,date('m'),1,date('Y'));
        $endThismonth=mktime(23,59,59,date('m'),date('t'),date('Y'));
        $monthOrder = object_array(DB::table('order')
            ->select(DB::raw('count(order_id) as count'),DB::raw('sum(real_amount) as price'))
            ->where($where) // where条件
            ->where('pay_time', '>=', $beginThismonth)//当前月的开始时间
            ->where('pay_time', '<=', $endThismonth)//当前月的最后时间
            ->get()
            ->toArray()
        );
        return $monthOrder;
    }


    /**
     * lj
     * 订单量排行  日 周 月 （当前酒店）
     * @param $type   日月周 状态
     * @return array
     */
    public function hotelOrderCharts($type,$where) {
        // 1 为按天算出前30天   2 为按周算出一季度的   3 为按月算出一年的
        if($type == 1) {
            $start =  date("Y-m-d",strtotime("+1 day"));
            $endtime= date("Y-m-d", strtotime("-1 month"));
            $days = date("t");
            $orderRank = object_array(DB::table('order')
                ->where($where)                  // where条件
                ->where('created_at', '>=', $endtime)    // 大于当前时间往前推1一个时间
                ->where('created_at', '<=', $start)      // 小于当前时间
                ->groupBy('date')
                ->limit($days)
                ->get([DB::raw('DATE(created_at) as date'),DB::raw('COUNT(*) as value')])
                ->keyBy('date')
                ->toArray()
            );
            $newArray = array();
            for($i=0;$i<15;$i++) {
                $time= date("Y-m-d", strtotime("-$i day"));
                $newArray[$time] ='0';
            }


            foreach($newArray as $k =>$v){
                if(!empty($orderRank[$k])){
                    $newArray[$k] = $orderRank[$k]['value'];
                }
            }
            ksort($newArray);

            return $newArray;

        }elseif($type == 2) {
            // 计算出本周
            $monday = strtotime(date('Y-m-d', (time() + (7 - (date('w') == 0 ? 7 : date('w'))) * 24 * 3600)));
            //由于每个月只有四周 让 $i 从 1 到 5 增加即可
            $newTime = array();
            for ($i=1; $i <= 12; $i++) {
                $start=date("Y-m-d",$monday-($i-1)*86400*7);   // 起始周一
                $end=date("Y-m-d",$monday-$i*86399*7);        // 结束周日
                $newTime[] = array(
                    'start' => $end,
                    'end' => $start,
                );
            }

            $endtime = date("Y-m-d", time());
            $starttime= strtotime(date("Y-m-d", strtotime("-3 month")));

            $orderRank = object_array(DB::table('order')
                ->where($where) // where条件
                ->where('created_at', '>=', $starttime) // 大于当前时间往前推1一个时间
                ->where('created_at', '<=', $endtime)   // 小于当前时间
                ->groupBy('date')
                ->limit(12)
                ->get([DB::raw('DATE(created_at) as date'),DB::raw('COUNT(*) as value')])
                ->toArray()
            );
            $rankArray= [];
            foreach($newTime as $key => $value) {
                $keys =$value['start'].'.'.$value['end'];
                $rankArray[$keys] =$value;
                $rankArray[$keys]['value'] =0;
                foreach($orderRank as $k =>$v){
                    if(($v['date'] < $value['end']) && ($v['date'] >= $value['start'])) {
                        $rankArray[$keys]['value'] += $v['value'];
                        unset($orderRank[$k]);
                    }
                }
            }
            ksort($rankArray);
            return $rankArray;

        }elseif($type == 3) {
            $start = date("Y-m-d", time());
            $endtime= date("Y-m-d", strtotime("-1 year"));
            $orderRank = object_array(DB::table('order')
                ->where($where) // where条件
                ->where('created_at', '>=', $endtime) // 大于当前时间往前推1一个时间
                ->where('created_at', '<=', $start)   // 小于当前时间
                ->groupBy('date')
                ->limit(12)
                ->get([DB::raw('DATE_FORMAT(created_at,\'%Y-%m\') as date'),DB::raw('COUNT(*) as value')])
                ->keyBy('date')
                ->toArray()
            );
            $newArray = array();
            for($n=0;$n<12;$n++) {
                $time= date("Y-m", strtotime("-$n month"));
                $newArray[$time] ='0';
            }

            foreach($newArray as $k =>$v){
                if(!empty($orderRank[$k])){
                    $newArray[$k] = $orderRank[$k]['value'];
                }
            }
            ksort($newArray);
            return $newArray;
        }
    }


    /**
     * lj
     * 销售金额排行  日 周 月 （当前酒店）
     * @param $type   日月周 状态
     * @return array
     */
    public function hotelSalesCharts($type,$where) {
        // 1 为按天算出前30天   2 为按周算出一季度的   3 为按月算出一年的
        if($type == 1) {
            $start =  date("Y-m-d",strtotime("+1 day"));
            $endtime= date("Y-m-d", strtotime("-1 month"));
            $days = date("t");
            $orderRank = object_array(DB::table('order')
                ->where($where)                  // where条件
                ->where('created_at', '>=', $endtime)    // 大于当前时间往前推1一个时间
                ->where('created_at', '<=', $start)      // 小于当前时间
                ->groupBy('date')
                ->limit($days)
                ->get([DB::raw('DATE(created_at) as date'),DB::raw('COUNT(*) as value'),DB::raw('sum(real_amount) as price')])
                ->keyBy('date')
                ->toArray()
            );
            $newArray = array();
            for($i=0;$i<15;$i++) {
                $time= date("Y-m-d", strtotime("-$i day"));
                $newArray[$time] ='0';
            }


            foreach($newArray as $k =>$v){
                if(!empty($orderRank[$k])){
                    $newArray[$k] = $orderRank[$k]['price'];
                }
            }
            ksort($newArray);
            return $newArray;

        }elseif($type == 2) {
            // 计算出本周
            $monday = strtotime(date('Y-m-d', (time() + (7 - (date('w') == 0 ? 7 : date('w'))) * 24 * 3600)));
            //由于每个月只有四周 让 $i 从 1 到 5 增加即可
            $newTime = array();
            for ($i=1; $i <= 12; $i++) {
                $start=date("Y-m-d",$monday-($i-1)*86400*7);   // 起始周一
                $end=date("Y-m-d",$monday-$i*86399*7);        // 结束周日
                $newTime[] = array(
                    'start' => $end,
                    'end' => $start,
                );
            }

            $endtime = date("Y-m-d", time());
            $starttime= strtotime(date("Y-m-d", strtotime("-3 month")));

            $orderRank = object_array(DB::table('order')
                ->where($where) // where条件
                ->where('created_at', '>=', $starttime) // 大于当前时间往前推1一个时间
                ->where('created_at', '<=', $endtime)   // 小于当前时间
                ->groupBy('date')
                ->limit(12)
                ->get([DB::raw('DATE(created_at) as date'),DB::raw('COUNT(*) as value'),DB::raw('sum(real_amount) as price')])
                ->toArray()
            );
            $rankArray= [];
            foreach($newTime as $key => $value) {
                $keys =$value['start'].'.'.$value['end'];
                $rankArray[$keys] = $value;
                $rankArray[$keys]['value'] =0;
                foreach($orderRank as $k =>$v){
                    if(($v['date'] < $value['end']) && ($v['date'] >= $value['start'])) {
                        $rankArray[$keys]['value'] += $v['price'];
                        unset($orderRank[$k]);
                    }
                }
            }
            ksort($rankArray);
            return $rankArray;

        }elseif($type == 3) {
            $start = date("Y-m-d", time());
            $endtime= date("Y-m-d", strtotime("-1 year"));
            $orderRank = object_array(DB::table('order')
                ->where($where) // where条件
                ->where('created_at', '>=', $endtime) // 大于当前时间往前推1一个时间
                ->where('created_at', '<=', $start)   // 小于当前时间
                ->groupBy('date')
                ->limit(12)
                ->get([DB::raw('DATE_FORMAT(created_at,\'%Y-%m\') as date'),DB::raw('COUNT(*) as value'),DB::raw('sum(real_amount) as price')])
                ->keyBy('date')
                ->toArray()
            );
            $newArray = array();
            for($n=0;$n<12;$n++) {
                $time= date("Y-m", strtotime("-$n month"));
                $newArray[$time] ='0';
            }

            foreach($newArray as $k =>$v){
                if(!empty($orderRank[$k])){
                    $newArray[$k] = $orderRank[$k]['price'];
                }
            }
            ksort($newArray);
            return $newArray;
        }
    }

    /**
     * lj
     * 本月有效订单 和 销售金额 (当前酒店)
     * @param $where
     * @return array
     */
    public function checkOrder1() {
        $time  = time()- 172800;
        $select =array('o.c_id','s.goods_id');
        $data = object_array(DB::table('order as o')
            ->select($select)
            ->join('order_side as s','o.order_id','=','s.order_id')
            ->where('o.status','=','1') // where条件
            ->where('o.creat_time', '<=', $time)
            ->get()->toArray()
        );
        dump($data);  die;


    }


}
