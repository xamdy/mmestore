<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Common;
use Illuminate\Support\Facades\DB;
use Excel;
class CeshiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        return view('admin.ceshi.add');
    }

    /**
     * 添加入库
     * @param Request $request
     */
    public function addPost(Request $request,Common $common) {
        $data = $request->all();
        $info=$common->datasFind('ceshi',array('number'=>$data['number']),'number');
        if (preg_match("/\d{12}$/", $data['number'])){

            if(empty($info)){
                $add = $common->addCommon(
                    'ceshi',
                    array(
                        'number' => $data['number'],
                        'time' => time()
                    )
                );
                return success('添加成功！','admin/ceshi/lists');
            }else{
                return success('体验店编号已存在','admin/ceshi/lists');
            }
        }else{
            return success('硬件编号为纯数字且12位','admin/ceshi/lists');
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function lists(Request $request,Common $common) {
        $list = $common->mysqlList('ceshi');
        return view('admin.ceshi.index',array('list'=>$list));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function del($id,Common $common) {
        $del = $common->delCommon(
            'ceshi',
            array(
                'id' => $id
            )
        );
        return success('删除成功！','admin/ceshi/lists');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function opend($id)
    {
        $client = new \swoole_client(SWOOLE_SOCK_TCP|SWOOLE_KEEP);
        if($client->connect('0.0.0.0',9789)){
            $message = json_encode(['deviceid'=>$id,'action'=>'action']);
            $client->send($message);
            sleep(2);
            @$datas = $client->recv();
            $res = json_decode($datas,true);
            if($res['status']==1){
                return success('开锁成功！','admin/ceshi/lists');
            }else {
                $swoole = new \swoole_client(SWOOLE_SOCK_TCP | SWOOLE_KEEP);
                $me = json_encode(['deviceid'=>$id,'action'=>'action']);
                if ($swoole->connect('0.0.0.0', 9789)) {
                    $swoole->send($me);
                    sleep(2);
                    @$datas = $swoole->recv();
                    $rest = json_decode($datas, true);
                    if ($rest['status'] == 1) {
                        return success('开锁成功！', 'admin/ceshi/lists');
                    } else {
                        return success('开锁失败！', 'admin/ceshi/lists');
                    }

                }
            }
        }else{
            return success('未连接服务器！','admin/ceshi/lists');
        }
    }


    /**
     * @param $id
     * @return $this
     * 关闭灯带
     */

    public function close($id){
        $client = new \swoole_client(SWOOLE_SOCK_TCP|SWOOLE_KEEP);
        if($client->connect('0.0.0.0',9789)){
            $message = json_encode(['deviceid'=>$id,'action'=>'a']);
            $client->send($message);
            sleep(2);
            @$datas = $client->recv();
            $res = json_decode($datas,true);
            if($res['status']==1){
                return success('关闭成功！','admin/ceshi/lists');
            }else{
                $client->send($message);
                sleep(2);
                @$datas = $client->recv();
                $rest = json_decode($datas,true);
                if($rest['status']==1){
                    return success('关闭成功！','admin/ceshi/lists');
                }else{
                    return success('关闭失败！','admin/ceshi/lists');
                }

            }
        }else{
            return success('未连接服务器！','admin/ceshi/lists');
        }

    }

    /**
     * @param $id
     * @return $this
     * 还原灯带
     */

    public function restore($id){
        $client = new \swoole_client(SWOOLE_SOCK_TCP|SWOOLE_KEEP);
        if($client->connect('0.0.0.0',9789)){
            $message = json_encode(['deviceid'=>$id,'action'=>"b"]);
            $client->send($message);
            sleep(2);
            @$datas = $client->recv();
            $res = json_decode($datas,true);
            if($res['status']==1){
                return success('还原成功','admin/ceshi/lists');
            }else{
                $client->send($message);
                sleep(2);
                @$datas = $client->recv();
                $rest = json_decode($datas,true);
                if($rest['status']==1){
                    return success('还原成功！','admin/ceshi/lists');
                }else{
                    return success('还原失败！','admin/ceshi/lists');
                }

            }
        }else{
            return success('未连接服务器！','admin/ceshi/lists');
        }


    }

    public function closeLock(Common $common){
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
            array_multisort($time_str,SORT_DESC,$new);
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
            $data=array();
            foreach ($NewData as $k=>$v){
                if(substr($v['lock'],-1) == 1){
                    $lock_info=object_array(DB::table('container')->select('container_number','lock_code','hotel_id','room_id','id')->where(array('lock_code'=>$v['deviceid'],'status'=>1))->first());
                    if(!empty($lock_info['container_number'])){
                        $NewData[$k]['container_number']=$lock_info['container_number'];
                    }else{
                        $NewData[$k]['container_number']='ceshi666';
                    }
                        $hotelName=object_array(DB::table('hotel')->select(array('name','name_en'))->where(array('id'=>$lock_info['hotel_id']))->first());
                        if(!empty($hotelName)){
                            $NewData[$k]['hotel_name']=$hotelName['name'];
                            $NewData[$k]['hotel_name_en']=$hotelName['name_en'];
                        }else{
                            $NewData[$k]['hotel_name']='测试';
                            $NewData[$k]['hotel_name_en']='ceshi';
                        }
                        $roomName=object_array(DB::table('room')->select(array('room_number','room_name'))->where(array('id'=>$lock_info['room_id']))->first());
                        if(!empty($roomName)){
                            $NewData[$k]['room_number']=$roomName['room_number'];
                            $NewData[$k]['room_name']=$roomName['room_name'];
                        }else{
                            $NewData[$k]['room_number']='大床房';
                            $NewData[$k]['room_name']='666';
                        }
                    $userInfo=object_array(DB::table('users')->select('name','tel')->where(array('c_id'=>$lock_info['id']))->orderBy('lock_time','desc')->limit(1)->first());
                    if(empty($userInfo)){
                        $NewData[$k]['user_name']='@';
                        $NewData[$k]['user_tel']='00000000000';
                    }else{
                        $NewData[$k]['user_name']=$userInfo['name'];
                        $NewData[$k]['user_tel']=$userInfo['tel'];
                    }
                }else{
                    unset($NewData[$k]);
                }
            }
//            echo "<pre>";
//            var_dump($NewData);
            return view('admin.ceshi.close',['data'=>$NewData]);
        }else{
            echo "没有关锁数据";
        }
    }

    public function export($data){
        $CloseLock=json_decode($data);
        $cellData=array(0=>array('关锁人姓名','关锁人手机号','体验店编号','酒店名称','房间类型','房间编号','关锁状态','关锁时间'));
        foreach ($CloseLock as $k =>$v ){
            if($v->user_name=='@'){
                $cellData[$k+1][0]=$v->user_name;
            }else{
                $cellData[$k+1][0]=json_decode($v->user_name);
            }
            $cellData[$k+1][1]=$v->user_tel;
            $cellData[$k+1][2]=$v->container_number;
            $cellData[$k+1][3]=$v->hotel_name;
            $cellData[$k+1][4]=$v->room_name;
            $cellData[$k+1][5]=$v->room_number;
            if(substr($v->lock,-1) == 1){
                $cellData[$k+1][6]='关锁成功';
            }else{
                $cellData[$k+1][6]='关锁失败';
            }
            $cellData[$k+1][7]=$v->lock_time;
        }

        $name = date('Y-m-d H:i:s');
        Excel::create('关锁详情'.$name,function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
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
    public function destroy($id)
    {
        //
    }
}
