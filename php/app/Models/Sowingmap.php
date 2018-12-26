<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;
class Sowingmap extends Common
{
    /**
     * @return mixed  获取轮播图
     */
    public function More(){
        $data= DB::table('sowingmap')->select('id','goods_id','img_url')->where(array('img_status'=>1))->OrderBy('create_time','desc')->limit(4)->get()->toArray();
        return $data;
    }
    //获取轮播图的数据
    public function DataPage($img_name){
            return DB::table('sowingmap')
                ->where('img_name','like',"%$img_name%")
                ->orderBy('create_time','desc')
                ->paginate(10);
    }
//    //添加数据
//    public function InsertData($data,$goods_img){
//        if(!empty($data)){
//            foreach ($data['goods_id'] as $k=>$v){
//                foreach ($goods_img as $key=>$value){
//                    if($key==$k){
//                $newData['goods_id']=$v;
//                $newData['img_status']=$data['img_status'];
//                $newData['img_name']=$data['img_name'];
//                $newData['img_url']=$value->main_img;
//                $newData['create_time']=time();
//                $result= DB::table('sowingmap')->insert($newData);
//                    }
//                }
//            }
//
//           if($result){
//               return true;
//           }else{
//               return false;
//           }
//        }
//    }
    //查找单条数据
    public function getOne($id){
        if(!empty($id)){
            $data= DB::table('sowingmap')->where(['id'=>$id])->first();
            if(!empty($data)){
                return $data;
            }else{
                return array('message'=>'没有数据');
            }
        }else{
            return array('message'=>'没有参数');
        }
    }
    //删除单条数据
    public function delOne($id){
        if(!empty($id)){
            $data= DB::table('sowingmap')->where(['id'=>$id])->delete();
                return $data;
        }else{
            return array('message'=>'没有参数');
        }
    }

    /**
     * @return mixed
     * 查找数据
     */
    public function DataMore(){
        return DB::table('sowingmap')->select("goods_id")->get()->toArray();
    }

    /**
     * @param $where
     * @param $data
     * @return 修改轮播图
     */

    public function DataSave($where,$data){
        if(is_array($data)){
            return DB::table('sowingmap')->where($where)->update($data);
        }else{
            return false;
        }
    }

    /**
     * @param $data
     * @return mixed
     * 天健数据
     */
     public function InsertData($data){
        return DB::table('sowingmap')->insert($data);
     }
}
