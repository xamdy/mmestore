<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\AdminMenu;
use App\Models\AuthRule;
use App\Models\AuthAccess;
use wc;
class RbacController extends CommonController
{

    // public function __construct(){
    //     $this->request=$request;
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Role $role)
    {
        try {
            $res = $role->whereData();
            return view('admin.rbac.index',array('res'=>$res));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function roleadd(Request $request)
    {
         return view('admin.rbac.roleadd');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addpost(Request $request,Role $role)
    {
        try {
            $datas = $request->all();
            $add = array(
                    'name'=>trim($datas['name']),
                    'remark'=>trim($datas['remark']),
                    'status'=>$datas['status']
                );
            $ret = $role->add($add);
            if($ret){
                return success("添加角色成功", 'admin/rbac/index');
            }else{
                return error('添加失败','admin/rbac/roleadd');
            }
        } catch (\Exception $e) {
            return error($e->getMessage(),'admin/rbac/roleadd');
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
    public function edit($id,Role $role)
    {
        try {
            $id = intval($id);
            if($id == 1){
                return error("超级管理员角色不能被修改！",'admin/rbac/index');
            }
            $res = $role->datasFind(['id'=>$id]);
            if($res){
                return view('admin.rbac.edit',array('res'=>$res));
            }else{
                return error('该角色不存在！','admin/rbac/index');
            }
        } catch (\Exception $e) {
            return error($e->getMessage(),'admin/rbac/index');
        }
    }
      /**
     * updata the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editpost(Request $request,Role $role)
    {
        try {
            $datas = $request->all();
            $update = array(
                    'name'=>trim($datas['name']),
                    'remark'=>trim($datas['remark']),
                    'status'=>$datas['status']
                );
            $ret = $role->updatas(['id'=>$datas['id']],$update);
            if($ret){
                return success("保存角色成功", 'admin/rbac/index');
            }else{
                return error('保存失败',"admin/rbac/edit/{$datas['id']}");
            }
        } catch (\Exception $e) {
            return error($e->getMessage(),'admin/rbac/index');
        }
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
    public function delete($id,RoleUser $roleuser,Role $role)
    {
        try {
            $id = intval($id);
            if ($id == 1) {
               return error("超级管理员角色不能被删除！",'admin/rbac/index');
            }
            $count = $roleuser->counts(['role_id' => $id]);
            if($count>0){
                return error("该角色已经有用户！",'admin/rbac/index');
            }else{
                $ret = $role->deletes(['id'=>$id]);
                if($ret){
                    return success("删除成功！", 'admin/rbac/index');
                }else{
                    return error("删除失败！", 'admin/rbac/index');
                }
            }
        } catch (\Exception $e) {
            return error($e->getMessage(),'admin/rbac/index');
        }
    }
   public function auths($id,AdminMenu $AdminMenu,AuthAccess $AuthAccess){
        // $AuthAccess     = Db::name("AuthAccess");
        $tree       = new \Tree();
        $tree->icon = ['│ ', '├─ ', '└─ '];
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        // $result = $adminMenuModel->menuCache();
        $result = $AdminMenu->whereData();
        $result = json_decode(json_encode($result), true);
//              var_dump($result);die;
       $newMenus      = [];
        $privilegeData = $AuthAccess->columns(["role_id" => $id],"rule_name");//获取权限表数据
        // ppp($privilegeData);
        // $privilegeData =  '';
        foreach ($result as $m) {
            $newMenus[$m['id']] = $m;
        }
        foreach ($result as $n => $t) {
            $result[$n]['checked']      = ($this->_isChecked($t, $privilegeData)) ? ' checked' : '';
            $result[$n]['level']        = $this->_getLevel($t['id'], $newMenus);
            $result[$n]['style']        = empty($t['parent_id']) ? '' : 'display:none;';
            $result[$n]['parentIdNode'] = ($t['parent_id']) ? ' class="child-of-node-' . $t['parent_id'] . '"' : '';
        }

        $str = "<tr id='node-\$id'\$parentIdNode  style='\$style'>
                   <td style='padding-left:30px;'>\$spacer<input type='checkbox' name='menuId[]' value='\$id' level='\$level' \$checked onclick='javascript:checknode(this);'> \$name</td>
                </tr>";
        $tree->init($result);
        $category = $tree->getTree(0, $str);
        // ppp($category);
        return view('admin.rbac.auths',array('category'=>$category,'roleId'=>$id));
   }

   public function authspost(Request $request,AdminMenu $AdminMenu,AuthAccess $AuthAccess){
    try {
        $datas = $request->all();
//        var_dump($datas['menuId']);die;
        $roleId =intval($datas['roleId']);
        if (!$roleId) {
                return error("需要授权的角色不存在！",'admin/rbac/index');
            }
        if(is_array($datas['menuId']) && count($datas['menuId'])>0){
            $AuthAccess->deletes(["role_id" => $roleId, 'type' => 'admin_url']);
                foreach ($datas['menuId'] as $menuId) {
                    $menu = $AdminMenu->datasFind(["id" => $menuId]);
                    if ($menu) {
                        $app    = $menu->app;
                        $model  = $menu->controller;
                        $action = $menu->action;
                        $name   = strtolower("$app/$model/$action");
                        $AuthAccess->add(["role_id" => $roleId, "rule_name" => $name, 'type' => 'admin_url']);
                    }
                }
                return success("授权成功！","admin/rbac/auths/{$roleId}");
        }else{
            //当没有数据时，清除当前角色授权
            $ret = $AuthAccess->deletes(["role_id" => $roleId]);
            if($ret){
                return error("没有接收到数据，执行清除授权成功！","admin/rbac/auths/{$roleId}");
            }else{
                return error("没有接收到数据，执行清除授权失败！","admin/rbac/auths/{$roleId}");
            }
        }
   
    } catch (\Exception $e) {
        return error($e->getMessage(),'admin/rbac/index');
    }
   }
    /**
     * 获取菜单深度
     * @param $id
     * @param array $array
     * @param int $i
     * @return int
     */
    protected function _getLevel($id, $array = [], $i = 0)
    {
        if ($array[$id]['parent_id'] == 0 || empty($array[$array[$id]['parent_id']]) || $array[$id]['parent_id'] == $id) {
            return $i;
        } else {
            $i++;
            return $this->_getLevel($array[$id]['parent_id'], $array, $i);
        }
    }
        /**
     * 检查指定菜单是否有权限
     * @param array $menu menu表中数组
     * @param $privData
     * @return bool
     */
    private function _isChecked($menu, $privData)
    {
        $app    = $menu['app'];
        $model  = $menu['controller'];
        $action = $menu['action'];
        $name   = strtolower("$app/$model/$action");
        if ($privData) {
            if (in_array($name, $privData)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }
}
