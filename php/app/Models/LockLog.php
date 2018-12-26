<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;
class LockLog extends Model
{
    /**
     * @param $room_id
     * @param $con_id
     * @param $hotel_id
     * @param $start_time
     * @param $end_time
     * @return mixed
     * 开锁数据列表
     */
   public function DataMore($room_id,$con_id,$hotel_id,$start_time,$end_time){
       $where =array();
       if(!empty($room_id)){
           $where['log.r_id']=$room_id;
       }if(!empty($con_id)){
           $where['log.c_id']=$con_id;
       }if(!empty($hotel_id)){
           $where['log.h_id']=$hotel_id;
       }
          $data=DB::table('container_log as log')

           ->join('container as c','log.c_id','=','c.id')
           ->join('hotel as h','log.h_id','=','h.id')
           ->join('room as r','log.r_id','=','r.id')
              ->select('c.container_number','h.name as hotel_name','r.room_name',
                  'r.room_number','log.*')
            ->where($where)
             ->where(function($query) use ($start_time,$end_time){
                  if(!empty($start_time) && !empty($end_time)){
                      $start_time = strtotime($start_time);
                      $end_time = strtotime($end_time);
                      $query->whereBetween('log.create_time', array($start_time,$end_time));
                  }elseif(!empty($start_time)){
                      $start_time = strtotime($start_time);
                      $query->where('log.create_time', '>', $start_time);
                  }elseif(!empty($end_time)){
                      $end_time = strtotime($end_time);
                      $query->where('log.create_time', '<', $end_time);
                  }
              })
           ->orderby('create_time','desc')
           ->paginate(10);
          return $data;

   }
    public function table($table,$select){
        return DB::table($table)->select($select)->get();
    }
    public function findWhere($table,$select,$where){
        return DB::table($table)->select($select)->where($where)->first();
    }

    /**
     * @param $id
     * @return mixed
     * 开锁详情数据
     */
    public function findDetail($id){
        $data=DB::table('container_log as log')
            ->join('container as c','log.c_id','=','c.id')
            ->join('hotel as h','log.h_id','=','h.id')
            ->join('room as r','log.r_id','=','r.id')
            ->select('c.container_number','h.name as hotel_name','r.room_name',
                'r.room_number','log.*')
            ->where('log.id',$id)->first();
        return $data;
    }


    public function LockError(){
        $NewData=array();
       $count= DB::table('container_error as error')
           ->select(DB::raw('count(c_id) as error_num'),'c_id')
            ->groupBy('error.c_id')
            ->get()->toArray();

       $data= DB::table('container_error as error')
           ->select('c.container_number','h.name as hotel_name','r.room_name',
               'r.room_number','error.*')
             ->join('container as c','error.c_id','=','c.id')
             ->join('hotel as h','error.h_id','=','h.id')
              ->join('room as r','error.r_id','=','r.id')
           ->get()->toArray();

            foreach ($data as $key=>$value){
//                foreach ($count as $k=>$v){
                    $NewData['container_number']=$value->container_number;
                    $NewData['hotel_name']=$value->hotel_name;
                    $NewData['room_name']=$value->room_name;
                    $NewData['room_number']=$value->room_number;
                    $NewData['c_id']=$value->c_id;
                    $NewData['h_id']=$value->h_id;
                    $NewData['r_id']=$value->r_id;
                    $NewData['tel']=$value->tel;
                    $NewData['status']=$value->status;
                    $NewData['create_time']=$value->create_time;
//                    $NewData['error_count']=$v->error_num;
                        $unique_arr = array_unique ($NewData);
        // 获取重复数据的数组
        $repeat_arr = array_diff_assoc ( $NewData, $unique_arr );
//                }
//            }
        }
        // 获取去掉重复数据的数组
//        $unique_arr = array_unique ($NewData);
//        // 获取重复数据的数组
//        $repeat_arr = array_diff_assoc ( $NewData, $unique_arr );

       return $unique_arr;
    }
}
