<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Support\Facades\DB;
class UserController extends CommonController
{

    // public function __construct(){
    //     $this->request=$request;
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user,Request $Request)
    {
          try {
                $datas = $Request->all();
                $where = '';
                $and = '';
                if(isset($datas['user_login'])){
                    $where .= " user_login like '%{$datas['user_login']}%'";
                    $and = 'and';
                }
                if(isset($datas['user_email'])){
 
                    $where .= "$and  user_email like '%{$datas['user_email']}%'";
                }
                $res = $user->datasPage($where);

                if(isset($datas['user_login'])){
                    $res->user_login=$datas['user_login'];
                }else{
                    $res->user_login='';
                }
                if(isset($datas['user_email'])){
                    $res->user_email=$datas['user_email'];
                }else{
                    $res->user_email='';
                }
                return view('admin.user.index',array('res'=>$res,'search'=>$datas));
        } catch (\Exception $e) {
             echo $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request,Role $role)
    {
        try {
            $role = $role->datas();
            return view('admin.user.add',array('role'=>$role));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addpost(Request $request,User $user,RoleUser $roleuser)
    {
        try {
            $datas = $request->all();
            if(!empty($datas['role_id']) && is_array($datas['role_id'])){
                //检测用户是否存在
                $ret = $user->datasFind(['user_login'=>$datas['user_login']]);
                if(!$ret){
                    $add = array(
                        'user_login'=>trim($datas['user_login']),
                        'user_pass'=>set_password($datas['user_pass']),
                        'user_email'=>trim($datas['user_email'])
                        );
                    DB::beginTransaction();
                    $useRet = $user->add($add);
                    if($useRet){
                        foreach ($datas['role_id'] as $role_id) {
                            if (session('ADMIN_ID') != 1 && $role_id == 1) {
                               return error('添加失败,超级管理员只能有网站admin才能添加！','admin/user/add');
                            }

                            $roleuser->add(["role_id" => $role_id, "user_id" => $useRet]);
                        }
                        DB::commit();
                        return success('添加成功！','admin/user/index');
                    }else{
                         DB::rollBack();
                         return error('添加失败！','admin/user/add');
                    }
                }else{
                    return error('该管理员已经存在','admin/user/add');
                }
            }else{
                return error('请选择角色','admin/user/add');
            }
         
        } catch (\Exception $e) {
           return error($e->getMessage(),'admin/user/add');
        }
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
    public function edit($id,User $user,RoleUser $roleuser,Role $role)
    {
        try {
            $role = $role->datas();
            $roleUseRet = $roleuser->whereData(["user_id" => $id],'role_id');
            // $roleUseRet = $roleuser->rolesId(["user_id" => $id]);
            
            $useRet = $user->datasFind(["id" => $id]);
            $rolesId = array();
            foreach($roleUseRet as $roleid){
                $rolesId[] = $roleid->role_id;
            }
            // print('<pre>');
            // print_r($rolesId);die;
            return view('admin.user.edit',array('role'=>$role,'ruRet'=>$rolesId,'useRet'=>$useRet));
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editpost(Request $request,User $user,RoleUser $roleuser)
    {
        try {
            $datas = $request->all();
            $uid = intval($datas['id']);
            if (!empty($datas['role_id']) && is_array($datas['role_id'])) {
                $updatas = array();
                if (!empty($datas['user_pass'])) {
                    $updatas['user_pass'] = set_password($datas['user_pass']);
                }
                $updatas['user_login'] = trim($datas['user_login']);
                $updatas['user_email'] = trim($datas['user_email']);
                $updatas['uptime'] = time();
                //更新
                $useRet = $user->updatas(['id'=>$uid],$updatas);

                if($useRet){
                     $roleuser->deletes(['user_id'=>$uid]);
                        foreach ($datas['role_id'] as $role_id) {
                            if (session('ADMIN_ID') != 1 && $role_id == 1) {
                                error("为了网站的安全，非网站创建者不可创建超级管理员！",'admin/user/edit/{$uid}');
                            }
                            $roleuser->add(["role_id" => $role_id, "user_id" => $uid]);
                        }
                        return success("保存成功！",'admin/user/index');
                }else{
                   return error('保存失败！',"admin/user/edit/{$uid}"); 
                }
            }else{
                return error('请选择角色！',"admin/user/edit/{$uid}");
            }
        } catch (\Exception $e) {
            return error($e->getMessage(),'admin/user/index');
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id,User $user,RoleUser $roleuser)
    {
        if ($id == 1) {
            error("最高管理员不能删除！",'admin/user/index');
        }

        if ($user->deletes(['id'=>$id])) {
            $roleuser->deletes(['user_id'=>$id]);
            return success("删除成功！",'admin/user/index');
        } else {
            return error("删除失败！",'admin/user/index');
        }
    }
    /**
     * 管理员拉黑操作
     * @author j
     * @DateTime 2018-08-27T16:55:00+0800
     * @param    string                   $value [description]
     * @return   [type]                          [description]
     */
    public function black($id)
    {
        $result = DB::table('admin')->where('id',$id)->Update(['user_status'=>0]);
        if($result){
            return success("拉黑成功！",'admin/user/index');
        }else {
            return error("拉黑失败！",'admin/user/index');
        }
    }

}
