<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Member;


class MemberController extends CommonController
{
	 /**
     * 用戶管理
     *
     * @return 
     */
	public function index( Request $request,Member $member){
		try {
            // 判断是get 还是 post
            $method = $request->method();
            // 接收传过来的值
            $data = $request->all();
            // 要查询的字段
            $field = array(
                'user_id',
                'name',
                'sex',
                'languages',
                'tel',
                'register_time',
            );
            if($method == 'GET') {
                // 直接查询用户数据库
                $list = $member->datasPage($data,$field);
                foreach($list->items() as $key => $value) {
                    $list[$key]->name = json_decode($value->name);
                }
            }elseif($method == 'POST') {
                isset($data['name']) ? $data['name']=json_encode($data['name']) : '';
                $list = $member->datasPage($data,$field);
                foreach($list->items() as $key => $value) {
                    $list[$key]->name = json_decode($value->name);
                }
            }
            return view('admin.member.index',array('res'=>$list));

		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	   
    }
}
