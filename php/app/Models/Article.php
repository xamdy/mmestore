<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
	use SoftDeletes;
	    /**
     * 允许赋值的字段
     *
     * @var array
     */
    protected $fillable = ['name'];
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
    public function select(){

         return $result = $this->get();
//        p($result);die;
    }

    /**
     * @param $id
     * @return bool|null
     * @throws \Exception  删除数据
     */
    public function del($id){
        return $result = $this->where('id',$id)->delete();
    }

    /**
     * @param $id
     * @param $data
     * @return bool  修改数据
     */
	public function edit($id,$data){
		return $result = $this->where('id',$id)->update(['name'=>$data]);
	}

}
