<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminMenu extends Model
{
	use SoftDeletes;
	    /**
     * 允许赋值的字段
     *
     * @var array
     */
    protected $fillable = ['name','phone','code','password'];
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
     * 按父ID查找菜单子项
     * @param int $parentId 父菜单ID
     * @param boolean $withSelf 是否包括他自己
     * @return mixed
     */
    public function adminMenu($parentId, $withSelf = false)
    {
        //父节点ID
        $parentId = intval($parentId);
        $result   = DB::table('admin_menu')->where(['parent_id' => $parentId, 'status' => 1])->orderBy("list_order", "ASC")->get();
      
        if ($withSelf) {
            $result2[] = $this->where(['id' => $parentId])->find();
            $result    = array_merge($result2, $result);
        }
         //权限检查
        if (session('ADMIN_ID') == 1) {
            //如果是超级管理员 直接通过
            return $result;
        }

        $array = [];

        foreach ($result as $v) {

//             //方法
            $action = $v->action;
            //public开头的通过
            if (preg_match('/^public_/', $action)) {
                $array[] = $v;
            } else {

                if (preg_match('/^ajax_([a-z]+)_/', $action, $_match)) {

                    $action = $_match[1];
                }

                $ruleName = strtolower($v->app . "/" . $v->controller . "/" . $action);
                if ($this->sky_auth_check(session('ADMIN_ID'), $ruleName)) {
                // if (1) {
                    $array[] = $v;
                }
            }
        }

        return $array;
    }
    public function menuTree()
    {
        $data = $this->getTree(0);
        return $data;
    }

    /**
     * 取得树形结构的菜单
     * @param $myId
     * @param string $parent
     * @param int $Level
     * @return bool|null
     */
    public function getTree($myId, $parent = "", $Level = 1)
    {
        $data = $this->adminMenu($myId);
        $Level++;
        if (count($data) > 0) {
            $ret = NULL;
            foreach ($data as $a) {
                $id         = $a->id;
                $app        = $a->app;
                // $controller = ucwords($a->controller);
                $controller = lcfirst($a->controller);//strtolower 小写  strtoupper 大写
                $action     = $a->action;
                //附带参数
                $params = "";
                if ($a->param) {
                    $params = "?" . htmlspecialchars_decode($a->param);
                }

                if (strpos($app, 'plugin/') === 0) {
                    $pluginName = str_replace('plugin/', '', $app);
                    $url        = cmf_plugin_url($pluginName . "://{$controller}/{$action}{$params}");
                } else {
                    $url = url("{$app}/{$controller}/{$action}{$params}");
                }

                $app = str_replace('/', '_', $app);

                $array = [
                    "icon"   => $a->icon,
                    "id"     => $id . $app,
                    "name"   => $a->name,
                    "parent" => $parent,
                    "url"    => $url,
                    'lang'   => strtoupper($app . '_' . $controller . '_' . $action)
                ];


                $ret[$id . $app] = $array;
                $child           = $this->getTree($a->id, $id, $Level);
                //由于后台管理界面只支持三层，超出的不层级的不显示
                if ($child && $Level <= 3) {
                    $ret[$id . $app]['items'] = $child;
                }

            }
            return $ret;
        }

        return false;
    }
/**
     * 检查权限
     * @param $userId  int        要检查权限的用户 ID
     * @param $name string|array  需要验证的规则列表,支持逗号分隔的权限规则或索引数组
     * @param $relation string    如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
     * @return boolean            通过验证返回true;失败返回false
     */
    public function sky_auth_check($userId, $name = null, $relation = 'or')
    {
        if (empty($userId)) {
            return false;
        }

        if ($userId == 1) {
            return true;
        }
        // if (empty($name)) {
        //     $request    = request();
        //     $module     = $request->module();
        //     $controller = $request->controller();
        //     $action     = $request->action();
        //     $name       = strtolower($module . "/" . $controller . "/" . $action);
        // }

        return $this->check($userId, $name, $relation);
    }
        /**
     * 检查权限
     * @param $name string|array  需要验证的规则列表,支持逗号分隔的权限规则或索引数组
     * @param $uid  int           认证用户的id
     * @param $relation string    如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
     * @return boolean           通过验证返回true;失败返回false
     */
    public function check($uid, $name, $relation = 'or')
    {

        if (empty($uid)) {
            return false;
        }
        if ($uid == 1) {
            return true;
        }
        if (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {

                $findAuthRuleCount = DB::table('auth_rule')->where(['name'=>$name])->count();

                if ($findAuthRuleCount == 0) {//没有规则时,不验证!
                    return true;
                }
                $name = [$name];
            }
        }

        $list   = []; //保存验证通过的规则名

        // $groups = DB::table('RoleUser')->alias("a")->join('__ROLE__ r', 'a.role_id = r.id')
        //     ->where(["a.user_id" => $uid, "r.status" => 1])
        //     ->column("role_id");
        //有问题需解决
        $groups = DB::table('role_user')->where(["user_id" => $uid])->pluck('role_id')->toarray();
        if (in_array(1, $groups)) {
            return true;
        }

        if (empty($groups)) {
            return false;
        }

        // $rules = Db::table('auth_access as a')
            // ->alias("a")
            // ->leftjoin('auth_rule as b ', ' a.rule_name = b.name')
            // ->where(["a.role_id" => ["in", $groups], "b.name" => ["in", $name]])
            // ->get();
            // ppp($rules)
        $rules = Db::table('auth_access as a')
             ->leftJoin('auth_rule as b', function($join)
                {
                    $join->on('a.rule_name', '=', 'b.name');
                })
             ->whereIn('b.name',$name)
             ->whereIn('a.role_id',$groups)
             // ->where($wheres)
            ->get();
            // ppp($name);
           

            if(!$rules){
                return false;
            }

        foreach ($rules as $rule) {
            if (!empty($rule->condition)) { //根据condition进行验证
                // $user = $this->getUserInfo($uid);//获取用户信息,一维数组

                // $command = preg_replace('/\{(\w*?)\}/', '$user[\'\\1\']', $rule->condition);
                // //dump($command);//debug
                // @(eval('$condition=(' . $command . ');'));
                // if ($condition) {
                //     $list[] = strtolower($rule->name);
                // }
                $list[] = strtolower($rule->name);
            } else {
                $list[] = strtolower($rule->name);
            }
        }

        if ($relation == 'or' and !empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ($relation == 'and' and empty($diff)) {
            return true;
        }
        return false;
    }
    /**
    * @return mixed  查找数据
    */
    public function datas(){
        return DB::table('user')->get();
    }
    /**
    * @return mixed  查找数据
    */
    public function datasPage(){
        return DB::table('admin_menu')->paginate(15);
    }
    /**
    * @return mixed  查找数据
    */
    public function whereData($where=array(),$field="*",$orderby=''){
        return DB::table('admin_menu')->where($where)->select($field)->orderBy("list_order", "ASC")->get()->toarray();
    }
      /**
    * @return mixed  查找数据
    */
    public function arrData($where=array(),$field="*",$orderby=''){
        return  DB::table('admin_menu')->where($where)->select($field)->orderBy("list_order", "ASC")->get()->toArray();
    }
    /**
    * @return mixed  查找数据
    */
    public function datasFind($where='',$field='*'){
     // return DB::select('select * from admin where admin_id = ?', [$id]);
        if(is_array($where)){
            return DB::table('admin_menu')->select($field)->where($where)->first();
        }else{
            return false;
        }
    }
    /**
    * @return mixed  添加数据
    */
    public function add($data){
        if(is_array($data)){
            return DB::table('admin_menu')->insert($data);
        }else{
            return false;
        }
    }
    /**
    * @return mixed  修改数据
    */
    public function updatas($where,$data){
        if(is_array($where)&&is_array($data)){
            // DB::enableQueryLog();
            return DB::table('admin_menu')->where($where)->update($data);
            // echo  response()->json(DB::getQueryLog());die;
           
        }else{
            return false;
        }
    }
    /**
    * @return mixed  删除数据
    */
    public function deletes($where){
        if(is_array($where)){
            // DB::enableQueryLog();
            return DB::table('admin_menu')->where($where)->delete();
            // echo  response()->json(DB::getQueryLog());die;
           
        }else{
            return false;
        }
    }
}
