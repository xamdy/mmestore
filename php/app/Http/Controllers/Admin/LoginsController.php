<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
class LoginsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!empty(session('ADMIN_ID'))){
            return redirect(url('admin/index/index', [], false, true));
        }
        return view('admin.login.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $token = $request->except('_token');
        $arr =$request->all();
        $data=htmlspecialchars($arr);
        $where['UserName'] = $data['username'];
        $where['UserPwd'] = $data['password'];
        $result = DB::table('users')
                  ->where($where)
                  ->first();
//        p($result);die;
        if(!$result){
            $this->show_msg('登录失败');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        //员工试用期转正报告

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
    /**
     * 登录验证
     */
    public function doLogin(Request $request,User $user)
    {
        $token = $request->except('_token');
        $data= $request->all();
//        $data=htmlspecialchars($arr);
        $result['code'] = 2;
        if($data['admin_name'] && $data['admin_password']){
            $where = array(
                    'user_login'=>trim($data['admin_name'])
                );
//            $where = array(
//                    'user_login ?'=>trim($data['admin_name'])
//                );
            $ret = $user->datasFind($where);
//            var_dump($ret);
//            die;
            if($ret){
                if($ret->user_status == 1){
                    if (sky_compare_password($data['admin_password'], $ret->user_pass)) {
                        session(['ADMIN_ID'=>$ret->id]);
                        session(['name'=> $ret->user_login]);
                        $updata['last_login_ip']   = $request->getClientIp();
                        $updata['last_login_time'] = time();
                        $user->updatas($where,$updata);
                        $result['msg'] = '登录成功';
                        $result['code'] = 1;
                    }else{
                        $result['msg'] = '密码错误';
                    }
                }else{
                    $result['msg'] = '您的账号已禁止登录';
                }
                
            }else{
                $result['msg'] = '您还不是管理员';
            }
        }else{
            $result['msg'] = '请输入账号和密码';
        }
        return json_encode($result);
    }

    /**
     * 后台管理员退出
     */
    public function logout()
    {
        session(['ADMIN_ID'=>'']);
        return redirect(url('admin/logins/index', [], false, true));
    }
}
