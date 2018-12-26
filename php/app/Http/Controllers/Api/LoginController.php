<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Aliyun\DySDKLite\SignatureHelper;

class LoginController extends Controller
{
    static $openid;
    /**
     *  // 发送验证码
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function code( Request $request) {
        set_time_limit(0);
        // 接受数据
        $data = $request->all();
        // 判断是否传手机号参数
        if(empty($data['phone'])) {
            return publicReturn(
                '2',
                '手机号不能为空'
            );
        }
        if (preg_match("/^1[3456789]\d{9}$/", $data['phone'])) {
            //第一步：引入配置文件，得到需要的预先设置好的参数
//            $smsconf = config('alisms');
            $mobile = $data['phone']; // 用户手机号，接收验证码
            $name = '梦马体验店';  // 短信签名,可以在阿里大鱼的管理中心看到
            $num = rand(100000, 999999); // 生成随机验证码 **键值和之前设定的短信模板变量保持一致
            $code = 'SMS_139226396';   // 阿里大于(鱼)短信模板ID
            $interval = 300;
            $arr = array('code'=>strval($num),'product' => '1213');
            $content = json_encode($arr); // 转换成json格式的
            $result = $this->sendSms($mobile, $name, $content, $code);
//            p($result);die;
            //property_exists — 检查对象或类是否具有该属性
            if (property_exists($result, "Code") && $result->Code != 'OK') {
                return publicReturn(
                    '2',
                    '发送失败'
                );
            }else{
                // 先删除redis里手机信息  再存储新的验证码
                Redis::del($mobile);
                Redis::setex($mobile, $interval, json_encode([
                    'captcha' => $num,
                ]));
                return publicReturn(
                    '1',
                    '发送成功'
                );
            }
        }else {
            return publicReturn(
                '2',
                '手机号码格式错误'
            );
        }
    }

    /**
     * 发送短信
     */
    function sendSms( $phone,$name, $content, $code ) {

        $params = array ();

        // *** 需用户填写部分 ***

        // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
        $accessKeyId = "LTAI2w3QRnVu9qlm";
        $accessKeySecret = "MeeE1KvBGDQRBoLEGuXQNWxRKvP1M6";

        // fixme 必填: 短信接收号码
        $params["PhoneNumbers"] = $phone;

        // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $params["SignName"] = $name;

        // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $params["TemplateCode"] = $code;

        // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
        $params['TemplateParam'] = $content;
//    $params['TemplateParam'] = Array (
//        "code" => "7894654",
//        "product" => "1213"
//    );

        // fixme 可选: 设置发送短信流水号
        $params['OutId'] = "12345";

        // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
        $params['SmsUpExtendCode'] = "1234567";

        // *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
        if(!empty($params["TemplateParam"]) && is_array($params["TemplateParam"])) {
            $params["TemplateParam"] = json_encode($params["TemplateParam"], JSON_UNESCAPED_UNICODE);
        }

        // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
        $helper = new SignatureHelper();

        // 此处可能会抛出异常，注意catch
        $content = $helper->request(
            $accessKeyId,
            $accessKeySecret,
            "dysmsapi.aliyuncs.com",
            array_merge($params, array(
                "RegionId" => "cn-hangzhou",
                "Action" => "SendSms",
                "Version" => "2017-05-25",
            ))
        // fixme 选填: 启用https
        // ,true
        );

        return $content;
    }

    /**
     *  // 判断验证码接口
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function isCode(Request $request) {
        $data = $request->all();
        if (preg_match("/^1[3456789]\d{9}$/", $data['phone'])) {
            $ret = Redis::get($data['phone']);
            $arr = json_decode($ret,true);

            if($arr['captcha']==$data['code']) {

                if(empty($data['name']) || empty($data['languages'])|| empty($data['img']) || empty($data['open_id'])) {
                    return publicReturn(
                        '2',
                        '参数不能为空',
			$data
                    );
                }

                if($data['languages'] == 'zh_CN') {
                    $data['languages'] = 1;
                }else {
                    $data['languages'] = 2;
                }

                // 先查询该用户是否登录过
                $open_id = $this->common->datasFind(
                    'users',
                    array(
                        'open_id' => $data['open_id']
                    ),
                    array(
                        'user_id'
                    )
                );

                // 如果为空的话则入库  如果查询到则修改
                if(empty($open_id)) {

                    $user = $this->common->addCommon(
                        'users',
                        array(
                            'name' => json_encode($data['name']),
                            'img' => $data['img'],
                            'open_id' => $data['open_id'],
                            'sex' => $data['sex'],
                            'languages' => $data['languages'],
                            'tel' => $data['phone'],
                            'register_time' => time(),
                        )
                    );

                    $newUserId['user_id'] = $user;
                    $newUserId['status'] = 1;
                    if($user) {
                        // 如果匹配上就删除存储在redis的信息
                        Redis::del($data['phone']);
                        return publicReturn(
                            '1',
                            '登录成功',
                            $newUserId
                        );
                    }


                }else {

                    $user = $this->common->UpdateCommon(
                        'users',
                        array(
                            'open_id' => $data['open_id']
                        ),
                        array(
                            'name' => json_encode($data['name']),
                            'img' => $data['img'],
                            'open_id' => $data['open_id'],
                            'sex' => $data['sex'],
                            'languages' => $data['languages'],
                            'tel' => $data['phone'],
                        )
                    );
                    $userInfo['user_id']=$open_id->user_id;
                    $userInfo['status']=2;
                    if($user === false) {

                        return publicReturn(
                            '2',
                            '登录失败',
                            $userInfo
                        );

                    }else {
                        // 如果匹配上就删除存储在redis的信息
                        Redis::del($data['phone']);
                        return publicReturn(
                            '1',
                            '登录成功',
                            $userInfo
                        );
                    }

                }

            }else {
                return publicReturn(
                    '2',
                    '验证码错误或者已经失效'
                );
            }
        }else {
            return publicReturn(
                '2',
                '手机号码格式错误'
            );
        }
    }

    /**
     * lj
     *  微信授权登录 获取到session_key 和 openid
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function wxLogin( Request $request ) {
        $APPID = 'wxa032c3a1803ad256';
        $AppSecret = '845cddc0e0d5a6d31d4b6d6b028fd431';

        $data = $request->all();
        $code = $data['code'];
        if(empty($code)) {
            return publicReturn(
                '2',
                'code不能为参数为空'
            );
        }
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$APPID.'&secret='.$AppSecret.'&js_code='.$code.'&grant_type=authorization_code';
        $arr = $this ->vegt($url);
        $ars = json_decode($arr,true);
        if($ars) {
            $return['code'] = 1;
            $return['msg'] = '成功';
            $return['data'] = $ars;
            return json_encode($return);
        }else {
            $return['code'] = 2;
            $return['msg'] = '失败';
            $return['data'] = '';
            return json_encode($return);
        }

    }

    /**
     * @param Request $request
     * @return string
     * 获取access_token
     */
    public function token(Request $request){
        $APPID = 'wxa032c3a1803ad256';
        $AppSecret = '845cddc0e0d5a6d31d4b6d6b028fd431';
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$APPID."&secret=".$AppSecret."";
        $arr = $this ->vegt($url);
        $ars = json_decode($arr,true);
        if($ars) {
            $return['code']=1;
            $return['msg'] = '成功';
            $return['data'] = $ars;
            return json_encode($return);
        }else {
            $return['code']=2;
            $return['msg'] = '失败';
            $return['data'] = '';
            return json_encode($return);
        }
    }

    public function vegt($url){
        $info = curl_init();
        curl_setopt($info,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($info,CURLOPT_HEADER,0);
        curl_setopt($info,CURLOPT_NOBODY,0);
        curl_setopt($info,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($info,CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($info,CURLOPT_URL,$url);
        $output= curl_exec($info);
        curl_close($info);
        return $output;
    }

}
