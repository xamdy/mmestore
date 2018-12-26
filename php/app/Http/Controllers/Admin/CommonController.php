<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminMenu;
class CommonController extends Controller
{
	// public function __construct(AdminMenu $AdminMenu){
 //        // $this->request=$request;
 //        $this->AdminMenu = $AdminMenu;

 //        // if( empty(session('ADMIN_ID'))){
 //        //      header('Location: http://laravel.com/admin/logins/index');
 //        // }else{
        
 //         if (!$this->checkAccess(session('ADMIN_ID'))) {

 //                // echo  error("您没有访问权限！",'');die;
 //            }
 //        // }
 //    }
        public function __construct(AdminMenu $AdminMenu){
            $this->AdminMenu = $AdminMenu;
            $this->middleware(function ($request, $next){
                $sessions = $request->session()->all();
                if(isset($sessions['ADMIN_ID'])){

                 if (!$this->checkAccess($sessions['ADMIN_ID'])) {

                    echo  error("您没有访问权限！",'');die;
                 }
                }else{
                    return redirect(url('admin/logins/index', [], false, true));                 
                }
                return $next($request);
            });
        }
	/**
     *  检查后台用户访问权限
     * @param int $userId 后台用户id
     * @return boolean 检查通过返回true
     */
    private  function checkAccess($userId)
    {
        // 如果用户id是1，则无需判断
        if ($userId == 1) {
            return true;
        }
        $apps = request()->route()->getAction();
        // ppp($apps);
        $action = explode('@', $apps['controller']);
        $controller = explode('/', $apps['prefix']);
        $rule = $controller[0].$controller[1].$action[1];

        $notRequire = ["adminindexindex", "adminindexmain"];
        if (!in_array($rule, $notRequire)) {
            $rules = $apps['prefix'].'/'.$action[1];
            return $this->AdminMenu->sky_auth_check($userId,$rules);
        } else {
            return true;
        }
    }
    /**
     * 检查权限
     * @param $userId  int        要检查权限的用户 ID
     * @param $name string|array  需要验证的规则列表,支持逗号分隔的权限规则或索引数组
     * @param $relation string    如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
     * @return boolean            通过验证返回true;失败返回false
     */
    function cmf_auth_check($userId, $name = null, $relation = 'or')
    {
        if (empty($userId)) {
            return false;
        }

        if ($userId == 1) {
            return true;
        }

        $authObj = new \cmf\lib\Auth();
        if (empty($name)) {
            $request    = request();
            $module     = $request->module();
            $controller = $request->controller();
            $action     = $request->action();
            $name       = strtolower($module . "/" . $controller . "/" . $action);
        }
        return $authObj->check($userId, $name, $relation);
    }
    
}
