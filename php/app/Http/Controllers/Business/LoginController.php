<?php

namespace App\Http\Controllers\Business;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
class LoginController extends Controller
{
    public function index() {
    	return view('business.login.index');
    }

    /**
     * 登录验证
     */
    public function doLogin(Request $request)
    {
        $data = $request->except('_token');
        $result['code'] = 2;
        if($data['admin_name'] && $data['admin_password']){
            $where = array(
                    'number'=>trim($data['admin_name'])
                );
            $ret = DB::table('hotel_manager')->where($where)->first();
            if($ret){
                if ($data['admin_password'] === $ret->password) {
                    session(['is_business'=>1]);
                    session(['name'=> $ret->number]);
                    session(['hotel_id'=> $ret->hotel_id]);
                    $result['msg'] = '登录成功';
                    $result['code'] = 1;
                }else{
                    $result['msg'] = '密码错误';
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
        session(['is_business'=>'']);
        return redirect(url('business/login/index', [], false, true));
    }
}
