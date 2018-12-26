<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
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
        return DB::table('admin')->get();
    }
    /**
    * @return mixed  查找数据
    */
    public function whereData($where=array(),$field="*"){
        return DB::table('admin')->where($where)->select($field)->get();
    }
    /**
    * @return mixed  查找数据
    */
    public function datasFind($where,$field='*'){
     // return DB::select('select * from admin where admin_id = ?', [$id]);
        if(is_array($where)){
          $data=DB::table('admin')->select($field)->where($where)->first();
//          var_dump($data);
          return $data;
        }else{
            return false;
        }
    }
       /**
    * @return mixed  查找数据 分页
    */
    public function datasPage($where){
        if($where){
            return DB::table('admin')->whereRaw($where)->paginate(10);
        }else{
            return DB::table('admin')->paginate(10);
        }
      
    }
    /**
    * @return mixed  添加数据
    */
    public function add($data){
        if(is_array($data)){
            return DB::table('admin')->insertGetId($data);
        }else{
            return false;
        }
    }
    /**
    * @return mixed  修改数据
    */
    public function updatas($where,$data){
        if(is_array($where)&&is_array($data)){
            return DB::table('admin')->where($where)->update($data);
        }else{
            return false;
        }
    }
    /**
    * @return mixed  删除数据
    */
    public function deletes($where){
        if(is_array($where)){
            return DB::table('admin')->where($where)->delete();
        }else{
            return false;
        }
    }
}
