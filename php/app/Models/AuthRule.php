<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuthRule extends Model
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
    * @return mixed  查找数据
    */
    public function datas(){
        return DB::table('auth_rule')->get();
    }
    /**
    * @return mixed  查找数据
    */
    public function whereData($where=array(),$field="*",$orderby=''){
        return DB::table('auth_rule')->where($where)->select($field)->orderBy("list_order", "ASC")->get()->toarray();
    }
      /**
    * @return mixed  查找数据
    */
    public function arrData($where=array(),$field="*",$orderby=''){
        return  DB::table('auth_rule')->where($where)->select($field)->orderBy("list_order", "ASC")->get()->toArray();
    }
    /**
    * @return mixed  查找数据
    */
    public function datasFind($where='',$field='*'){
     // return DB::select('select * from admin where admin_id = ?', [$id]);
        if(is_array($where)){
            return DB::table('auth_rule')->select($field)->where($where)->first();
        }else{
            return false;
        }
    }
    /**
    * @return mixed  添加数据
    */
    public function add($data){
        if(is_array($data)){
            return DB::table('auth_rule')->insert($data);
        }else{
            return false;
        }
    }
    /**
    * @return mixed  修改数据
    */
    public function updatas($where,$data){
        if(is_array($where)&&is_array($data)){
            return DB::table('auth_rule')->where($where)->update($data);
        }else{
            return false;
        }
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

                $findAuthRuleCount = Db::name('auth_rule')->where([
                    'name' => $name
                ])->count();

                if ($findAuthRuleCount == 0) {//没有规则时,不验证!
                    return true;
                }

                $name = [$name];
            }
        }

        $list   = []; //保存验证通过的规则名
        $groups = Db::name('RoleUser')
            ->alias("a")
            ->join('__ROLE__ r', 'a.role_id = r.id')
            ->where(["a.user_id" => $uid, "r.status" => 1])
            ->column("role_id");

        if (in_array(1, $groups)) {
            return true;
        }

        if (empty($groups)) {
            return false;
        }
        $rules = Db::name('AuthAccess')
            ->alias("a")
            ->join('__AUTH_RULE__ b ', ' a.rule_name = b.name')
            ->where(["a.role_id" => ["in", $groups], "b.name" => ["in", $name]])
            ->select();
        foreach ($rules as $rule) {
            if (!empty($rule['condition'])) { //根据condition进行验证
                $user = $this->getUserInfo($uid);//获取用户信息,一维数组

                $command = preg_replace('/\{(\w*?)\}/', '$user[\'\\1\']', $rule['condition']);
                //dump($command);//debug
                @(eval('$condition=(' . $command . ');'));
                if ($condition) {
                    $list[] = strtolower($rule['name']);
                }
            } else {
                $list[] = strtolower($rule['name']);
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
     * 获得用户资料
     * @param $uid
     * @return mixed
     */
    private function getUserInfo($uid)
    {
        static $userInfo = [];
        if (!isset($userInfo[$uid])) {
            $userInfo[$uid] = Db::name('user')->where(['id' => $uid])->find();
        }
        return $userInfo[$uid];
    }
}
