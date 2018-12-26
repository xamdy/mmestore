<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdminMenu;
use App\Models\AuthRule;
use wc;
class MenuController extends CommonController
{
	public function index(AdminMenu $AdminMenu){
		$tree = new \Tree();
		// $menus = $AdminMenu->menuTree(0);
		$result = $AdminMenu->whereData();

		$tree->icon = ['&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ '];
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        $newMenus = [];
        foreach ($result as $m) {
            $newMenus[$m->id] = $m;
        }
		$result = json_decode(json_encode($result), true);

		foreach ($result as $key => $value) {
            $result[$key]['parent_id_node'] = ($value['parent_id']) ? ' class="child-of-node-' . $value['parent_id'] . '"' : '';
            $result[$key]['style']          = empty($value['parent_id']) ? '' : 'display:none;';
            $result[$key]['str_manage']     = '<a href="' . url("admin/menu/add", ["parent_id" => $value['id'], "menu_id" => 1])
                . '">' . '添加子菜单'. '</a>  <a href="' . url("admin/menu/edit", ["id" => $value['id'], "menu_id" => 1])
                . '">' . '编辑' . '</a>  <a class="js-ajax-delete" href="' . url("admin/menu/delete", ["id" => $value['id'], "menu_id" => 1]) . '">' . '删除' . '</a> ';
            $result[$key]['status']         = $value['status'] ? '显示' : '隐藏';
            if (1) {
                $result[$key]['app'] = $value['app'] . "/" . $value['controller'] . "/" . $value['action'];
            }
        }
        
        $tree->init($result);
        $str      = "<tr id='node-\$id' \$parent_id_node style='\$style'>
                        <td style='padding-left:20px;'><input name='list_orders[\$id]' type='text' size='3' value='\$list_order' class='input input-order'></td>
                        <td>\$id</td>
                        <td>\$spacer\$name</td>
                        <td>\$app</td>
                        <td>\$status</td>
                        <td>\$str_manage</td>
                    </tr>";
        $category = $tree->getTree(0, $str);
        //   print("<pre>");
        // print_r($category);die;
    	return view('admin.menu.index',['category'=>$category]);
	}
	//首页信息展示
	public function main(){
		return view('admin.index.main');
	}
    /**
     * 添加菜单.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add($mid,$type,AdminMenu $AdminMenu){
     
        try {
            $parentId = $mid;

            $tree     = new \Tree();
            $result = $AdminMenu->whereData();
            $result = json_decode(json_encode($result), true);
            $array    = [];
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $parentId ? 'selected' : '';
                $array[]       = $r;
            }
            $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
            $tree->init($array);
            $selectCategory = $tree->getTree(0, $str);
            return view('admin.menu.add',['selectCategory'=>$selectCategory]);
        } catch (\Exception $e) {
            return $e->getMessage();
           
        }
    }
    /**
     * 编辑菜单.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit($mid,$type,AdminMenu $AdminMenu){
        try {
            

            $res = $AdminMenu->datasFind(['id'=>$mid]);
            $parentId = $res->parent_id;
            $tree     = new \Tree();
            $result = $AdminMenu->whereData();
            $result = json_decode(json_encode($result), true);
            $array    = [];
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $parentId ? 'selected' : '';
                $array[]       = $r;
            }
            $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
            $tree->init($array);
            $selectCategory = $tree->getTree(0, $str);
            return view('admin.menu.edit',['selectCategory'=>$selectCategory,'res'=>$res]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
       /**
     * 编辑菜单.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function doedit(Request $request,AdminMenu $AdminMenu,AuthRule $AuthRule){
        try {
            $datas = $request->all();
           
            $where = ['id'=>$datas['id']];
            $ret = $AdminMenu->datasFind($where);  //查找原始数据  查找authrule  表的id进行更改
            if($ret){
                 $updata = array(
                        'parent_id'=>$request->input("parent_id"),
                        'name'=>$request->input("name"),
                        'app'=>$request->input("app"),
                        'controller'=>$request->input("controller"),
                        'action'=>$request->input("action"),
                        'param'=>$request->input("param")?$request->input("param"):'',
                        'icon'=>$request->input("icon")?$request->input("icon"):'',
                        'remark'=>$request->input("remark")?$request->input("remark"):'',
                        'status'=>$request->input("status"),
                        'type'=>$request->input("type")
                    );
               
                    $menuRet = $AdminMenu->updatas($where,$updata);
                    if($menuRet){
                        $whereAuth = array(
                            'app'  => $ret->app,
                            'name' => "$ret->app/$ret->controller/$ret->action",
                            'type' => 'admin_url'
                            );
                        $authRet = $AuthRule->datasFind($whereAuth);
                        $authAdd = array(
                            "name"  => "{$datas['app']}/{$datas['controller']}/{$datas['action']}",
                            "app"   => $datas['app'],
                            "type"  => "admin_url", //type 1-admin rule;2-user rule
                            "title" => $datas['name'],
                            'param' => $datas['param']?$datas['param']:'',
                        );

                        $AuthRule->updatas(['id'=>$authRet->id],$authAdd);
                        return view('admin.public.jump')->with([
                            'message'=>'修改成功',
                            'url' =>"admin/menu/index",//控制器路径
                            'jumpTime'=>3,//执行时间
                            ]);
                    }else{
                        return view('admin.public.jump')->with([
                            'message'=>'修改失败',
                            'url' =>"admin/menu/edit/{$datas['id']}/1",//控制器路径
                            'jumpTime'=>3,//执行时间
                            ]);
                    }
              
            }else{
                return view('admin.public.jump')->with([
                    'message'=>'参数错误',
                    'url' =>"admin/menu/index",//控制器路径
                    'jumpTime'=>3,//执行时间
                    ]);
            }
        } catch (Exception $e) {
            return $e->getMMessage();
        }

    }
    public function addpost(Request $request,AdminMenu $AdminMenu,AuthRule $AuthRule){
        try {
            $datas = $request->all();
            
            $app          = $request->input("app");
            $controller   = $request->input("controller");
            $action       = $request->input("action");
            $param        = $request->input("param")?$request->input("param"):'';
            $authRuleName = "$app/$controller/$action";
            $menuName     = $request->input("name");

            $where = ['parent_id'=>$datas['parent_id'],'name'=>$menuName];
            $ret = $AdminMenu->datasFind($where);
           
            if(!$ret){
                $add = array(
                        'parent_id'=>$request->input("parent_id"),
                        'name'=>$request->input("name"),
                        'app'=>$request->input("app"),
                        'controller'=>$request->input("controller"),
                        'action'=>$request->input("action"),
                        'param'=>$request->input("param")?$request->input("param"):'',
                        'icon'=>$request->input("icon")?$request->input("icon"):'',
                        'remark'=>$request->input("remark")?$request->input("remark"):'',
                        'status'=>$request->input("status"),
                        'type'=>$request->input("type")
                    );
                $menuRet = $AdminMenu->add($add);
               
                $whereAuth = array(
                    'app'  => $app,
                    'name' => $authRuleName,
                    'type' => 'admin_url'
                    );
                $authRet = $AuthRule->datasFind($whereAuth);
                if(!$authRet){
                    $authAdd = array(
                            "name"  => $authRuleName,
                            "app"   => $app,
                            "type"  => "admin_url", //type 1-admin rule;2-user rule
                            "title" => $menuName,
                            'param' => $param,
                        );
                    $AuthRule->add($whereAuth);
                }
                 return view('admin.public.jump')->with([
                    'message'=>'添加成功',
                    'url' =>"admin/menu/index",//控制器路径
                    'jumpTime'=>3,//执行时间
                    ]);
            }else{
                return view('admin.public.jump')->with([
                    'message'=>'该菜单的名称已经存在请更换',
                    'url' =>"admin/menu/add",//控制器路径
                    'jumpTime'=>3,//执行时间
                    ]);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
   /**
     * 编辑菜单.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete($mid,$type,AdminMenu $AdminMenu){
        //查找是否存在
        $where = ['id'=>$mid];
        $ret = $AdminMenu->datasFind($where);  //查找原始数据  查找authrule  表的id进行更改
        if($ret){   //如果存在  查看是否有子目录 如果有不允许删除
            $sunRet = $AdminMenu->datasFind(['parent_id'=>$mid]);
            if(!$sunRet){
                $sunRet = $AdminMenu->deletes($where);
                if($sunRet){
                      return view('admin.public.jump')->with([
                        'message'=>'删除成功',
                        'url' =>"admin/menu/index",//控制器路径
                        'jumpTime'=>3,//执行时间
                    ]);
                }else{
                    return view('admin.public.jump')->with([
                        'message'=>'删除失败',
                        'url' =>"admin/menu/index",//控制器路径
                        'jumpTime'=>3,//执行时间
                    ]);
                }
            }else{
                 return view('admin.public.jump')->with([
                        'message'=>'请先删除子菜单',
                        'url' =>"admin/menu/index",//控制器路径
                        'jumpTime'=>3,//执行时间
                    ]);
            }
        }else{
             return view('admin.public.jump')->with([
                        'message'=>'找不到该条记录',
                        'url' =>"admin/menu/index",//控制器路径
                        'jumpTime'=>3,//执行时间
                    ]);
        }

    }
    public function lists(AdminMenu $AdminMenu){
        // $result = Db::name('AdminMenu')->order(["app" => "ASC", "controller" => "ASC", "action" => "ASC"])->select();
        try {
            // $res = $AdminMenu->arrData();
            $res = $AdminMenu->datasPage();
            // print('<pre>');
            // print_r($res);die;
            return view('admin/menu/lists',['res'=>$res]);
        } catch (\Exception $e) {
            
        }
        
    }
    
}
