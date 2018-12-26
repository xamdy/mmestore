<?php

namespace App\Models;

// 公共的
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Common extends Model
{
    use SoftDeletes;
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


    /**
     * @return mixed  查找数据
     */
        public function datas($mysql_name,$where,$field='*'){
        if(is_array($where)){
            return object_array(DB::table($mysql_name)->select($field)->where($where)->get()->toArray());
        }else{
            return false;
        }
    }



    /**
     * 排序查询表数据
     * @param $mysqlName
     * @return array|bool
     */
    public function orderByData($mysql_name,$where,$field,$orderBy,$type){
        if(is_array($where)){
            return object_array(DB::table($mysql_name)->select($field)->where($where)->orderBy($orderBy,$type)->get()->toArray());
        }else{
            return false;
        }
    }


    /**
     * 查询表数据
     * @param $mysqlName
     * @return array|bool
     */
    public function mysqlList($mysqlName){
        if( !empty($mysqlName) ) {
            return object_array(DB::table($mysqlName)->get()->toArray());
        }else{
            return false;
        }
    }

    /**
     * @return mixed  查找一条数据
     */
    public function datasFind($mysql_name,$where,$field='*') {
        if(is_array($where)){
            return DB::table($mysql_name)->select($field)->where($where)->first();
        }else{
            return false;
        }
    }
    /**
     * 查询单个字段
     * @author j
     * @DateTime 2018-08-13T14:37:38+0800
     * @param    [type]                   $mysql_name [description]
     * @param    [type]                   $where      [description]
     * @param    string                   $field      [description]
     * @return   [type]                               [description]
     */
    public function findval($mysql_name,$where,$field='*')
    {
        if(is_array($where)){
            return DB::table($mysql_name)->where($where)->value($field);
        }else{
            return false;
        }
    }
    /**
     * @return mixed  修改数据
     */
    public function UpdateCommon($mysql_name,$where,$data) {
        if(is_array($where)){
            return DB::table($mysql_name)->where($where)->update($data);
        }else{
            return false;
        }
    }

    /**
     * @return mixed  删除
     */
    public function delCommon($mysql_name,$id) {
        if(!empty($id)){
            return DB::table($mysql_name)->delete($id);
        }else{
            return false;
        }
    }

    /**
     * @return mixed  添加数据
     */
    public function addCommon($mysql_name,$data) {
        if(is_array($data)){
            return DB::table($mysql_name)->insertGetId($data);
        }else{
            return false;
        }
    }

    /**
     * @param $field
     * @param $where
     * @param $ids
     * @return bool
     * 查找除了id列的值
     */
    public function FindField($table,$field,$where,$id,$ids){
        if(is_array($where)){
            return DB::table($table)->select($field)->where($where)->whereNotIn($id,$ids)->get();
        }else{
            return false;
        }
    }

    public function whereIds($table,$field,$id,$where)
    {
        if(is_array($where)){
            return DB::table($table)->select($field)->whereIn($id,$where)->get()->toArray();
        }else{
            return false;
        }
    }

    public function DataNot($table,$field,$id){
        if(!empty($where)){
            return DB::table($table)->select($field)->where("id",'<>',"$id")->get()->toArray();
        }else{
            return false;
        }

    }

}
