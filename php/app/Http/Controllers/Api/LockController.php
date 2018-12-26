<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 2018/11/26
 * Time: 13:29
 */

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Common;
use Illuminate\Support\Facades\DB;

class LockController extends Controller   {
    public function token(Request $request){
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=';
        $arr = $this ->vegt($url);
        $ars = json_decode($arr,true);
        if($ars) {
            $return['code']=1;
            $return['msg'] = '成功';
            $return['data'] = $ars;
            var_dump($return);
//            return json_encode($return);
        }else {
            $return['code']=2;
            $return['msg'] = '失败';
            $return['data'] = '';
//            return json_encode($return);
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
    public function closeData(Request $request,Common $common){
        $time=date('Ymd',time());
        $file="lockLog/".$time.'.txt';
        //判断文件是否存在
        if(file_exists($file)){
            $content=file($file);
            $new=array();
            foreach ($content as $key =>$value){
                if(strlen($value)==76){
                    $time= substr($value,0,19);
                    $action=substr($value,19);
                    $status=object_array(json_decode($action));
                    $new[$key]['lock_time']=$time;
                    $new[$key]['deviceid']=$status['deviceid'];
                    $new[$key]['lock']=$status['lock'];
                }

            }
            $time_str = array();
            $NewData=array();
            //按最新时间排序
            foreach($new as $key=>$v){
                $new[$key]['time'] = strtotime($v['lock_time']);
                $time_str[] = $new[$key]['time'];
            }
            array_multisort($time_str,SORT_ASC,$new);
            //去重到每次心跳一样的数据
            foreach($new as $key=>$v)
            {
                if(substr($v['lock'],-1) == 1) {
                    if (!isset($NewData[$new[$key]['deviceid']])) {
                        $NewData[$new[$key]['deviceid']] = $new[$key];
                    }
                }
            }
            //根据锁编号 获取到体验店 酒店 房间和用户等信息
            foreach ($NewData as $k=>$v){
                if(substr($v['lock'],-1) == 1){
                    $lock_info= $common->datasFind('container',array('lock_code'=>$v['deviceid'],'status'=>1),array('container_number','lock_code','hotel_id','room_id','id'));
                    if(!empty($lock_info)){
                        $hotelName=$common->datasFind('hotel',array('is_del'=>1),array('name','name_en'));
                        $NewData[$k]['container_number']=$lock_info->container_number;
                        if(!empty($hotelName->name)){
                            $NewData[$k]['hotel_name']=$hotelName->name;
                            $NewData[$k]['hotel_name_en']=$hotelName->name_en;
                        }
                        $roomName=$common->datasFind('room',array('is_del'=>1),array('room_name','room_number'));
                        if(!empty($roomName->room_number)){
                            $NewData[$k]['room_number']=$roomName->room_number;
                            $NewData[$k]['room_name']=$roomName->room_name;
                        }
                        $userInfo=DB::table('users')->select('name','tel')->where(array('c_id'=>$lock_info->id))->orderBy('lock_time','desc')->limit(1)->first();
                        if(empty($userInfo)){
                            $NewData[$k]['user_name']='@';
                            $NewData[$k]['user_tel']='00000000000';
                        }else{
                            $NewData[$k]['user_name']=$userInfo->name;
                            $NewData[$k]['user_tel']=$userInfo->tel;
                        }
                    }
                }else{
                    unset($NewData[$k]);
                }
            }
            return view('admin.ceshi.close',['data'=>$NewData]);
        }else{
            echo "没有关锁数据";
        }
    }


}