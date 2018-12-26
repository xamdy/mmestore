<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sowingmap;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
class ImgController extends Controller
{

    public function index(Request $request){
        $data=  $request->all();
        if(!empty($data)){
            $sow=new Sowingmap();
            $result=$sow->More();
            if(!empty($result)){
                return publicReturn(
                    '1',
                    '成功',
                    $result
                );
            }else{
                return publicReturn(
                    '2',
                    '获取不到数据'
                ) ;
            }
        }else{
            return publicReturn(
                '3',
                '参数为空'
            ) ;
        }
    }







    public function api(){
        /**
        inc
        解析接口
        客户端接口传输规则:
        1.用cmd参数(base64)来动态调用不同的接口,接口地址统一为 http://a.lovexpp.com
        2.将要传过来的参数组成一个数组，数组添加timestamp元素(当前时间戳,精确到秒)，将数组的键值按照自然排序从大到小排序
        3.将数组组成 key=val&key=val的形式的字符串，将字符串与XPP_KEY连接在一起,用md5加密一次(32位小写),得到sign
        4.将sign添加到参数数组中
        5.将参数数组转换成json用post请求请求接口地址,key值为param
        服务端接口解析规则:
        1.接收参数param,将结果解析json得到参数数组
        2.取出sign,去掉参数数组中的sign
        3.将参数数组key值按照自然排序从大到小排序
        4.将排序后的参数数组按照key=val&key=val的形式组成字符串，将字符串与XPP_KEY连接，用md5加密一次(32位小写)，得到sign
        5.将sign与客户端传过来的sign进行比对,如不一样则可能是中途被篡改参数，服务器拒绝此次请求
        6.将sign与session中的sign对比，如果一样，则为重复提交，服务器拒绝此次请求
        7.此次的sign存入session
        8.执行路由cmd(base64解析后),将参数带到该方法中
         */

        $xpp_key = "xxx";

//接收参数param,将结果解析json得到参数数组
        $param = json_decode($_POST['param'] , true);

//取出sign,去掉参数数组中的sign
        $client_sign = $param['sign'];
        unset($param['sign']);

//将参数数组key值按照自然排序从大到小排序
        krsort($param);

//将排序后的参数数组按照key=val&key=val的形式组成字符串，将字符串与XPP_KEY连接，用md5加密一次(32位小写)，得到sign
        $sb = '';
        foreach($param as $key=>$val){
            $sb .= $key . '=' . $val . '&';
        }
        $sb .= $xpp_key;
        $server_sign = md5($sb);

//将sign与客户端传过来的sign进行比对,如不一样则可能是中途被篡改参数，服务器拒绝此次请求
        if($server_sign !== $client_sign){
            echo json_encode(array('code'=>'invalid request'));
            exit;
        }

//将sign与session中的sign对比，如果一样，则为重复提交，服务器拒绝此次请求
        if($server_sign == $_SESSION['last_sign']){
            echo json_encode(array('code'=>'Repeated requests'));
            exit();
        }

//此次的sign存入session
        $_SESSION['last_sign'] = $server_sign;

//执行路由cmd(base64解析后),将参数带到该方法中
        $cmd = base64_decode($param['cmd']);
        list($__controller,$__action) = explode('-' , $cmd);

// 设置请求参数
        unset($param['cmd']);
        unset($param['timestamp']);
        foreach($param as $key => $val){
            $_REQUEST[$key] = $val;
        }
    }


}
