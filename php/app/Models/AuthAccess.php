<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuthAccess extends Model
{
	use SoftDeletes;
	/**
     * 允许赋值的字段
     *
     * @var array
     */
    protected $fillable = ['name','phone','code','password'];
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'vehicle';
    /**
     * 指定是否模型应该被戳记时间。
     *
     * @var bool
     */
    public $timestamps = false;
    /**
    * @return mixed  查找数据
    */
    public function datas(){
        return DB::table('auth_access')->get();
    }
    /**
    * @return mixed  查找数据
    */
    public function whereData($where=array(),$field="*",$orderby=''){
        return DB::table('auth_access')->where($where)->select($field)->orderBy("list_order", "ASC")->get()->toarray();
    }
      /**
    * @return mixed  查找数据
    */
    public function arrData($where=array(),$field="*",$orderby=''){
        return  DB::table('auth_access')->where($where)->select($field)->orderBy("list_order", "ASC")->get()->toArray();
    }
          /**
    * @return mixed  查找数据
    */
    public function columns($where=array(),$column="*"){
        return  DB::table('auth_access')->where($where)->pluck($column)->toarray();
    }
    /**
    * @return mixed  查找数据
    */
    public function datasFind($where='',$field='*'){
     // return DB::select('select * from admin where admin_id = ?', [$id]);
        if(is_array($where)){
            return DB::table('auth_access')->select($field)->where($where)->first();
        }else{
            return false;
        }
    }
    /**
    * @return mixed  添加数据
    */
    public function add($data){
        if(is_array($data)){
            return DB::table('auth_access')->insert($data);
        }else{
            return false;
        }
    }
    /**
    * @return mixed  修改数据
    */
    public function updatas($where,$data){
        if(is_array($where)&&is_array($data)){
            return DB::table('auth_access')->where($where)->update($data);
        }else{
            return false;
        }
    }
       /**
    * @return mixed  删除数据
    */
    public function deletes($where){
        if(is_array($where)){
            return DB::table('auth_access')->where($where)->delete();
        }else{
            return false;
        }
    }
}
