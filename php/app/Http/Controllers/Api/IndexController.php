<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class IndexController extends Controller  
{


    /**
     *  lj
     *  开锁接口
     * @param Request $request
     * @return string
     */
    public function lock( Request $request ) {
        // 货柜编号查询货柜id
        if(empty($request->id) || empty($request->user_id) ) {
            return publicReturn(
                '2',
                '参数错误'
            );
        }
        $c_id = $this->common->datasFind('container',['container_number'=>$request->id],array('lock_code','id','hotel_id','room_id'));

        if($c_id->lock_code){
            // 开锁
            $client = new \swoole_client(SWOOLE_SOCK_TCP|SWOOLE_KEEP);
            if($client->connect('0.0.0.0',9789)){
                $message = json_encode(['deviceid'=>$c_id->lock_code,'action'=>'action']);
                $client->send($message);
                sleep(3);
                @$datas = $client->recv();
                $res = json_decode($datas,true);

                $userInfo = $this->common->datasFind('users',['user_id'=>$request->user_id],array('tel','name'));
                $log=array(
                    'c_id' => $c_id->id,
                    'h_id' => $c_id->hotel_id,
                    'r_id' => $c_id->room_id,
                    'tel' => $userInfo->tel,
                    'name' =>$userInfo->name,
                    'status' => 0,
                    'notes' => '开锁成功',
                    'create_time' => time(),
                );
                $container_id=array('c_id'=>$c_id->id,'lock_time'=>time());
                if($res['status']==1){

                    $this->common->addCommon('container_log',$log);	
		            $this->common->UpdateCommon('users',array('user_id'=>$request->user_id),$container_id);
                    return publicReturn(
                        '1',
                        '开锁成功',
			$res
                    );
                }else {
//                    $tel = $this->common->datasFind('users',['user_id'=>$request->user_id],'tel');
//
//                        // 如果开锁失败则入报修表
//                        $addArray = array(
//                            'c_id' => $c_id->id,
//                            'h_id' => $c_id->hotel_id,
//                            'r_id' => $c_id->room_id,
//                            'tel' => $tel->tel,
//                            'status' => 1,
//                            'notes' => '开锁失败报修',
//                            'create_time' => time(),
//                        );
//                        $addContainer = $this->common->addCommon('container_error',$addArray);
//			if($addContainer) {
//                            return publicReturn(
//                                '2',
//                                '开锁失败',
//				$res
//                            );
//                        }
                    $swoole = new \swoole_client(SWOOLE_SOCK_TCP | SWOOLE_KEEP);
                    if ($swoole->connect('0.0.0.0', 9789)) {
                        $me = json_encode(['deviceid' => $c_id->lock_code, 'action' => 'action']);
                        $swoole->send($me);
                        sleep(3);
                        @$datas = $swoole->recv();
                        $res = json_decode($datas, true);
                        if ($res['status'] == 1) {
                            $this->common->addCommon('container_log', $log);
                            $this->common->UpdateCommon('users', array('user_id' => $request->user_id), $container_id);
                            return publicReturn(
                                '1',
                                '开锁成功',
                                $res
                            );
                        } else {

                            // 根据用户id来查询出手机号
                            $tel = $this->common->datasFind('users', ['user_id' => $request->user_id], 'tel');

                            // 如果开锁失败则入报修表
                            $addArray = array(
                                'c_id' => $c_id->id,
                                'h_id' => $c_id->hotel_id,
                                'r_id' => $c_id->room_id,
                                'tel' => $tel->tel,
                                'status' => 1,
                                'notes' => '开锁失败报修',
                                'create_time' => time(),
                            );
                            $addContainer = $this->common->addCommon('container_error', $addArray);
                            if ($addContainer) {
                                return publicReturn(
                                    '2',
                                    '开锁失败',
                                    $res
                                );
                            } else {
                                return publicReturn(
                                    '2',
                                    '服务器异常'
                                );
                            }

                        }
                    }else{
                        return publicReturn(
                            '2',
                            '服务器异常'
                        );
                    }
                }
            }else{
                return publicReturn(
                    '3',
                    '锁未连接服务器'
                );
            }
            //开锁结束
        }else {
            return publicReturn(
                '3',
                '开锁结束'
            );
//            return publicReturn(
//                '2',
//                '锁编号错误或者未绑定货柜'
//            );
        }
    }

    /**
     * 首页商品列表
     * @author j
     * @DateTime 2018-07-06T15:19:41+0800
     * @param    Request                  $request [description]
     * @return   [type]                            [description]
     */
    public function index( Request $request )
    {
    	if(empty($request->id) || empty($request->user_id)){
    		return publicReturn(
    			'2',
    			'参数错误'
    		);
    	}

        // 货柜编号查询货柜id
        $c_id = $this->common->datasFind('container',['container_number'=>$request->id],array('id','hotel_id','room_id'));

        $data = [
            'container_goods.c_id' => $c_id->id,
            'goods_side.languages' => $request->languagess ? $request->languagess : 1
        ];
        $field = $request->languagess == 2 ? 'English_name' : 'name';
        $hname = $request->languagess == 2 ? 'name_en' : 'name';
        $user_id = $request->user_id;
        $results = $this->goods->goodsList($data,$user_id,$c_id->id,$field);
        // 获取到订单地址 根据酒店id 房间id 拼接起来
        $hotelName = $this->common->datasFind(
            'hotel',
            array(
                'id' => $c_id->hotel_id
            ),
            $hname.' as name'
        );
        $roomName = $this->common->datasFind(
            'room',
            array(
                'id' => $c_id->room_id
            ),
            'room_number'
        );
        // 查询酒店地址
        $order_address = $hotelName->name.$roomName->room_number;
        $result['address'] = $order_address;
        $result['hotel_id'] = $c_id->hotel_id;
        $result['room_id'] = $c_id->room_id;
        $result['container_id'] = $c_id->id;
        $result['result'] = $results ? $results : [];
        if($result){
            return publicReturn(
                '1',
                '成功',
                $result
            );
        }else{
            return publicReturn(
                '3',
                '无数据'
            );
        }
        
    }
    /**
     * 首页分类接口（分类下没有商品的不展示分类名称）
     * @author j
     * @DateTime 2018-08-24T10:56:33+0800
     * @param    Request                  $request [description]
     * @return   [type]                            [description]
     */
    public function category(Request $request)
    {
        if(empty($request->id)){
            return publicReturn(
                '2',
                '参数错误'
            );
        }
        // 货柜编号查询货柜id
        $c_id = $this->common->findval('container',['container_number'=>$request->id],'id');
        $result = DB::table('category')->select('category.id','name','English_name','img')
                    ->leftJoin('goods','category.id','cat_id')
                    ->leftJoin('container_goods','goods.goods_id','container_goods.goods_id')
                    ->where(['is_del'=>1,'status'=>1,'inventory_status'=>1,'c_id'=>$c_id])
                    ->orderBy('sorts','asc')
                    ->groupBy('category.id')
                    ->get();
        if($result){ 
            return publicReturn(
                '1',
                '成功',
                $result
            );
        }else{
            return publicReturn(
                '3',
                '无数据'
            );
        }
    }
}
