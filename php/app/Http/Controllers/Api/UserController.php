<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * // 用户个人中心
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request) {
        // 判断是否传用户id
        $uid = $request->user_id;
        if(empty($uid)) {
            return publicReturn(
                '2',
                '参数不正确'
            );
        }
        // 根据用户id来查询出用户信息
        $user = object_array($this->common->datasFind(
                'users',
                array(
                    'user_id' => $uid
                ),
                array(
                    'user_id',
                    'name',
                    'img',
                    'tel',
                )
            ));
        if(empty($user)) {
            return publicReturn(
                '2',
                '没有查询出该条用户的信息'
            );
        }
        // 用户手机号中间4位变*
        $user['tel'] = substr($user['tel'],0,3).'****'.substr($user['tel'],7);
        return publicReturn(
            '1',
            '成功',
            $user
        );
    }

    /**
     * // 联系客服  关于梦马
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function telAbout( Request $request) {
        $status = $request->status;
        // 判断status状态是否为空   1是联系客服  2关于梦马
        if(empty($status)) {
            return publicReturn(
                '2',
                '参数不能为空'
            );
        }
        // 查找配置表
        $list = $this->common->mysqlList('web_config');
        // 取出配置表的第一条数据
        $result = $list[0];
        // 判断是否为空
        if(empty($result)) {
            return publicReturn(
                '2',
                '数据为空'
            );
        }
        // 如果为1是联系客服  2是关于梦马  3是传参错误
        if($status == 1) {
            return publicReturn(
                '1',
                '成功',
                $result['phone']
            );
        }elseif($status ==2 ) {
            return publicReturn(
                '1',
                '成功',
                $result['about']
            );
        }else {
            return publicReturn(
                '2',
                '参数只能传1和2'
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
