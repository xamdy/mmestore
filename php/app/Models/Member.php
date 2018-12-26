<?php
/**
 * Created by PhpStorm.
 * User: 李靖
 * Date: 2018/7/16
 * Time: 13:57
 */
namespace App\Models;
use DB;

class Member extends Base
{
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
     *  lj
     *  查询用户列表
     * @param $data
     * @param $field
     */
    public function datasPage( $data,$field ='*' ) {
        $result = DB::table('users')->where(function ($query) use ($data) {
            if (!empty($data['cate_id'])) {
                $query->where('languages', $data['cate_id']);
            }
            if (!empty($data['tel'])) {
                $query->where('tel',$data['tel']);
            }
//            if (!empty($data['name'])) {
//                $query->where('name',$data['name']);
//            }
            if (!empty($data['name'])) {
                $query->where('name','like', "'%'.{$data['name']}.'%'");
            }
        })
            ->select($field)
            ->orderBy('user_id','desc')
            ->paginate(8);
        return $result;
    }

}