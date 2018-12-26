<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use iscms\Alisms\SendsmsPusher as Sms;
use Illuminate\Support\Facades\Redis;
class LoginController extends Controller
{
       /*初始化 得到appkey+secretKey */
    public function __construct(Sms $sms,User $user,request $request)
    {
        $this->sms=$sms;   //得到在SendSmsPusher.php中的所有配置信息
        $this->user=$user; 
        $this->request=$request; 
    }
    /**
     * 登录接口zth
     *
     * @return datas   用户信息
     */
    public function index()
    {
       $datas = $this->request->input();
       $return = ['code'=>2];

       if($datas['phone'] && $datas['password']){
             if (preg_match("/^1[3456789]\d{9}$/", $datas['phone'])) {
                $ret = $this->user->datasFind(['phone'=>$datas['phone']]); //查找数据库
                if(!$ret){
                  $return['msg'] = '该账号不存在';
                   return json_encode($return);
                }
                if(password($datas['password'],$ret->code,$ret->password)){     //验证密码是否正确

                        $return['code']=1;
                        $return['data']=$ret; 
                }else{
                    $return['msg'] = '密码不正确';
                }
            }else{
                $return['msg'] = '手机号码格式错误';
            }
       }else{
            $return['msg'] = '参数不能为空';
       }
        return json_encode($return);
        
    }
     /**
     * 注册获取验证码接口
     *
     * @return datas   用户信息
     */
    public function code(user $user,request $request)
    {
        $datas = $request->input();
        $return = ['code'=>2];
        if (preg_match("/^1[3456789]\d{9}$/", $datas['phone'])) {
            $ret = $user->datasFind(['phone'=>$datas['phone']]); //查找数据库
            if(!$ret){//不存在发送短信
                  //第一步：引入配置文件，得到需要的预先设置好的参数
                    $smsconf = config('alisms');
                    $mobile = $datas['phone']; // 用户手机号，接收验证码
                    $name = '注册验证';  // 短信签名,可以在阿里大鱼的管理中心看到
                    $num = rand(100000, 999999); // 生成随机验证码 **键值和之前设定的短信模板变量保持一致
                    $code = 'SMS_67321065';   // 阿里大于(鱼)短信模板ID
                    $interval = 300;
                    $arr = array('code'=>strval($num),'product' => 'OA');
                    $content = json_encode($arr); // 转换成json格式的
                    Redis::set($mobile, json_encode([
                        'captcha' => $num,
                        'expire' => time() + $interval
                    ]));
                    $result = $this->sms->send($mobile, $name, $content, $code);
                    //property_exists — 检查对象或类是否具有该属性
                    if (property_exists($result, "code") && $result->code > 0) {
                       $return['msg'] = '发送失败';
                    }else{
                        $return['msg'] = '发送成功';
                        $return['code'] = 1;
                    }
            }else{
                $return['msg'] = '该手机号已经注册过了';
            }
        }else{
            $return['msg'] = '手机号码格式错误';
        }
        return json_encode($return);
    }
    /**
     * 判断验证码接口
     *
     * @return datas   用户信息
     */
    public function iscode()
    {
        $datas = $this->request->input();
        $return = ['code'=>2];
        if(array_key_exists('phone',$datas)){
            if(array_key_exists('code',$datas)){
              $ret = Redis::get($datas['phone']);
              if($ret){
                $check = json_decode($ret,true);
                if($datas['code']==$check['captcha']){
                    //销毁redis的键
                    $return['msg']='成功';
                    $return['code']=1;
                }else{
                  $return['msg']='验证码不正确';
                }
              }else{
                $return['msg']='请重新发送验证码';
              }
            }else{
                $return['msg']='请输入验证码';
            }
        }else{
          $return['msg']='请输入手机号';
        }
        return json_encode($return);
    }
    /**
     * 注册接口
     *
     * @return datas   用户信息
     */
    public function register()
    {
        $datas = $this->request->input();
        $return = ['code'=>2];
        if($datas['phone'] && $datas['checkpass'] && $datas['password']){
            if($datas['checkpass']===$datas['password']){
                $ret = $this->user->datasFind(['phone'=>$datas['phone']]); //检测用户是否注册过
                if($ret){
                    $return['msg']='该用户已注册';
                }else{  //没有注册则存入数据库
                    $code = rand(100000,999999);
                    $pass = password($datas['checkpass'],$code);
                    $add = array('phone'=>$datas['phone'],'code'=>$code,'password'=>$pass);
                    $ret = $this->user->add($add);
                    if($ret){
                        $return['code']=1;
                        $return['msg']='注册成功';
                    }else{
                        $return['msg']='注册失败';
                    }
                }
            }else{
                $return['msg']='两次输入的密码不一致';
            }
        }else{
            $return['msg']='参数不能为空';
        }

        return json_encode($return);
    }
    /**
     * 忘记密码  发送短信
     *
     * @return datas   用户信息
     */
    public function modify()
    {
        $datas = $this->request->input();
        $return = ['code'=>2];

        if (preg_match("/^1[3456789]\d{9}$/", $datas['phone'])) {
            $ret = $this->user->datasFind(['phone'=>$datas['phone']]); //查找数据库
            if($ret){//不存在发送短信
                  //第一步：引入配置文件，得到需要的预先设置好的参数

                    $smsconf = config('alisms');
                    $mobile = $datas['phone']; // 用户手机号，接收验证码
                    $name = '注册验证';  // 短信签名,可以在阿里大鱼的管理中心看到
                    $num = rand(100000, 999999); // 生成随机验证码 **键值和之前设定的短信模板变量保持一致
                    $code = 'SMS_67321065';   // 阿里大于(鱼)短信模板ID
                    $interval = 300;
                    $arr = array('code'=>strval($num),'product' => 'OA');
                    $content = json_encode($arr); // 转换成json格式的
                    Redis::set($mobile, json_encode([
                        'captcha' => $num,
                        'expire' => time() + $interval
                    ]));
                    $result = $this->sms->send($mobile, $name, $content, $code);
                    //property_exists — 检查对象或类是否具有该属性

                    if (property_exists($result, "code") && $result->code > 0) {
                       $return['msg'] = '发送失败';
                    }else{
                        $return['msg'] = '发送成功';
                        $return['code'] = 1;
                    }
            }else{
                $return['msg'] = '该手机号还未注册,请注册';
            }
        }else{
            $return['msg'] = '手机号码格式错误';
        }
        return json_encode($return);
        
    }
        /**
     * 忘记密码  修改密码
     *
     * @return datas   用户信息
     */
    public function modifypass()
    {
        $datas = $this->request->input();

        $return = ['code'=>2];
        if($datas['phone'] && $datas['checkpass'] && $datas['password']){
            if($datas['checkpass']===$datas['password']){
                $ret = $this->user->datasFind(['phone'=>$datas['phone']]); //检测用户是否注册过
                if(!$ret){
                    $return['msg']='该用户还未注册';
                }else{  //没有注册则存入数据库
                    $code = rand(100000,999999);
                    $pass = password($datas['checkpass'],$code);
                    $updata = array('code'=>$code,'password'=>$pass);
                    $ret = $this->user->updatas(['phone'=>$datas['phone']],$updata);
                    if($ret){
                        $return['code']=1;
                        $return['msg']='修改成功';
                    }else{
                        $return['msg']='修改失败';
                    }
                }
            }else{
                $return['msg']='两次输入的密码不一致';
            }
        }else{
            $return['msg']='参数不能为空';
        }
        return json_encode($return);
    }
     /**
     * 扫码接口
     *
     * @return datas   用户信息
     */
    public function scan()
    {
        return view("home.index.code");
    }
    public function scans(){
      
    }
}
 