<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiUser extends Model
{
	use SoftDeletes;
	    /**
     * 允许赋值的字段 后台管理员
     *
     * @var array
     */
    protected $fillable = ['name','phone','code','password'];
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
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
        return DB::table('user')->get();
    }
    /**
    * @return mixed  查找数据
    */
    public function whereData($where=array(),$field="*"){
        return DB::table('user')->where($where)->select($field)->get();
    }
    /**
    * @return mixed  查找数据
    */
    public function datasFind($where,$field='*'){
     // return DB::select('select * from admin where admin_id = ?', [$id]);
        if(is_array($where)){
            return DB::table('user')->select($field)->where($where)->first();
        }else{
            return false;
        }
    }
       /**
    * @return mixed  查找数据 分页
    */
    public function datasPage($where=''){
        if($where){
            return DB::table('user')->whereRaw($where)->paginate(20);
        }else{
            return DB::table('user')->paginate(20);
        }
      
    }
    /**
    * @return mixed  添加数据
    */
    public function add($data){
        if(is_array($data)){
            return DB::table('user')->insertGetId($data);
        }else{
            return false;
        }
    }
    /**
    * @return mixed  修改数据
    */
    public function updatas($where,$data){
        if(is_array($where)&&is_array($data)){
            return DB::table('user')->where($where)->update($data);
        }else{
            return false;
        }
    }
    /**
    * @return mixed  删除数据
    */
    public function deletes($where){
        if(is_array($where)){
            return DB::table('user')->where($where)->delete();
        }else{
            return false;
        }
    }
}
