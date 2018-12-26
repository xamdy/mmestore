<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
 
    /**
    * @return mixed  查找数据 分页
    */
    public function datasPage($where=array()){
        if($where){
            return DB::table('category')->whereRaw($where)->paginate(20);
        }else{
            return DB::table('category as c')
            ->select('c.*',DB::raw('count(mm_g.goods_id) as count'))
//            ->leftJoin('admin as u', function($join)
//                {
//                    $join->on('c.admin_id', '=', 'u.id');
//                })
//             ->leftJoin('admin as u','c.admin_id','=','u.id')
             ->leftJoin('goods as g','g.cat_id','=','c.id')
             ->groupBy('c.id')
             ->orderBy('c.sorts','asc')
             ->paginate(5);
        }
    }

    /**
     * @return mixed  查找有多少条数据
     */
    public function count($where=array()){
        return DB::table('category')->where($where)->count();
    }


    /**
    * @return mixed  查找数据
    */
    public function whereData($where=array(),$field="*"){
        return DB::table('category')->where($where)->select($field)->get();
    }

    public function datasFind($where,$field='*'){
     // return DB::select('select * from admin where admin_id = ?', [$id]);
        if(is_array($where)){
            return DB::table('category as c')
            ->select('c.*','u.user_login')
            ->leftJoin('admin as u', function($join)
                {
                    $join->on('c.admin_id', '=', 'u.id');
                })->where($where)->first();
        }else{
            return false;
        }
    }
      /**
    * @return mixed  添加数据
    */
    public function add($data){
        if(is_array($data)){
            return DB::table('category')->insert($data);
        }else{
            return false;
        }
    }
        /**
    * @return mixed  修改数据
    */
    public function updatas($where,$data){
        if(is_array($where)&&is_array($data)){
            return DB::table('category')->where($where)->update($data);
        }else{
            return false;
        }
    }
    /**
    * @return mixed  删除数据
    */
    public function deletes($where){
        if(is_array($where)){
            return DB::table('category')->where($where)->delete();
        }else{
            return false;
        }
    }

    /**
     * lj
     *  分类商品列表
     * @param $where
     */
    public function findCateGoods($where) {
        $return = DB::table('category as c')
            ->select('c.name','g.goods_id','g.barcode','g.create_time','g.main_img','s.goods_name')
            ->leftJoin('goods as g','g.cat_id','=','c.id')
            ->leftJoin('goods_side as s','s.goods_id','=','g.goods_id')
            ->where(array('s.languages'=>1,'g.is_del'=>1))
            ->where($where)
            ->orderBy('g.create_time','desc')
            ->paginate(5);
        return $return;
    }


    /**
     * @return mixed  查找数据
     */
    public function datasIn($where){
        $field = array('g.barcode','g.goods_id','g.present_price','s.goods_name');
        $res =  DB::table('goods as g')
            ->leftJoin('goods_side as s', 'g.goods_id', '=', 's.goods_id')
            ->select($field)->wherein('g.barcode',$where)->where('s.languages','=','1')
            ->get()->keyBy('barcode')->toArray();
        return $res;
    }

    /**
     * lj
     * 查找该商品是否是这个分类下
     * @param $where
     * @return mixed
     */
    public function findCate($where,$cate_id){
        $field = array('g.barcode','g.goods_id','g.present_price');
        $res =  DB::table('goods as g')
            ->select($field)->wherein('g.barcode',$where)->where('g.cat_id','=',$cate_id)
            ->get()->keyBy('barcode')->toArray();
        return $res;
    }

}