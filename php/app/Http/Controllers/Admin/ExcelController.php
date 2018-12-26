<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;
use Illuminate\Support\Facades\DB;
use QRcode;
use App\Models\Common;
class ExcelController extends Controller
{
    /**
     * 房间上传模板
     * @author j
     * @DateTime 2018-07-19T17:20:29+0800
     * @return   [type]                   [description]
     */
    public function export()
    {
        $cellData = [
            ['酒店名称', '房间编号', '房间类型', '体验店编号'],
            ['北京hotel', '2508', '豪华大床房', '12342234'],
        ];
        Excel::create("房间模板", function ($excel) use ($cellData) {
            $excel->sheet('模板', function ($sheet) use ($cellData) {
                $sheet->rows($cellData);
                // 设置单元格大小
                $sheet->setSize(array(
                    'A1' => array(
                        'width' => 20
                    ),
                    'B1' => array(
                        'width' => 20
                    ),
                    'C1' => array(
                        'width' => 20
                    ),
                    'D1' => array(
                        'width' => 20
                    )
                ));
            });
        })->export('xlsx');

    }

    public function import(Request $request)
    {
        $data = $request->except('_token');
        // 上传封面图
        if ($request->hasFile('cover')) {
            $result = upload($request->cover, '/uploads/roomExcel');
            $filePath = public_path() . $result;
        }
        Excel::load($filePath, function ($reader) {
            $data = $reader->all()->toArray();
            $rAr=array();
            $rArs=array();
            //去掉重复的房间编号数据
            for($i=0;$i<count($data);$i++)
            {
                if(!isset($rAr[$data[$i]['房间编号']]))
                {
                    $rAr[$data[$i]['房间编号']]=$data[$i];
                }
            }
            $NewData=array_values($rAr);
            //去掉重复的体验店编号数据
            for($i=0;$i<count($NewData);$i++)
            {
                if(!isset($rArs[$NewData[$i]['体验店编号']]))
                {
                    $rArs[$NewData[$i]['体验店编号']]=$NewData[$i];
                }
            }
            $NewData=array_values($rArs);
            DB::beginTransaction();
            foreach ($NewData as $k => $v) {
                $hotel_id = $this->getHotelId($v['酒店名称']);//查询酒店id
                $arr=[
                    'hotel_id' => $hotel_id,
                    'room_number' => $v['房间编号'],
                    'room_name' => $v['房间类型'],
                    'create_time' => time()
                ];
                $room_id = DB::table('room')->insertGetId($arr);
                if ($room_id) {
                    $where = [
                        'container_number' => $v['体验店编号']
                    ];
                    $save['hotel_id'] = $hotel_id;
                    $save['room_id'] = $room_id;
                    $result=1;
                    $resu= DB::table('container')->where($where)->update($save);
                    if($resu){
                        $resu=1;
                    }
                }
                else {
                    DB::rollback();
                    echo error('上传失败', 'admin/room/index');
                }

            }
            if ($result==1 && $resu==1) {
                DB::commit();
                echo success("上传成功", 'admin/room/index');

            } else {
                DB::rollback();
                echo error('上传失败', 'admin/room/index');
            }

        });
    }

    public function getHotelId($name)
    {
        return DB::table('hotel')->where('name', $name)->value('id');
    }

    public function lockExcel()
    {
        $Data = [
            ['体验店编号', '体验锁编号'],
            ['123456', '286037563892'],
        ];
        Excel::create("体验店模板", function ($excel) use ($Data) {
            $excel->sheet('模板', function ($sheet) use ($Data) {
                $sheet->rows($Data);
                // 设置单元格大小
                $sheet->setSize(array(
                    'A1' => array(
                        'width' => 20
                    ),
                    'B1' => array(
                        'width' => 20
                    ),
                    'C1' => array(
                        'width' => 20
                    ),
                    'D1' => array(
                        'width' => 20
                    )
                ));
            });
        })->export('xlsx');
    }

    /**
     * @param Request $request
     * 批量上传体验锁编号和体验店编号
     */
    public function MoreContainer(Request $request)
    {
        $common=new Common();
        $data = $request->except('_token');

        // 上传封面图
        if ($request->hasFile('cover')) {
            $result = upload($request->cover, '/uploads/ContainerExcel');
            $filePath = public_path() . $result;
        }
        Excel::load($filePath, function ($reader) {
            $data = $reader->all()->toArray();
            $rAr=array();
            $rArs=array();
            //去掉重复的体验店编号数据
            for($i=0;$i<count($data);$i++)
            {
                if(!isset($rAr[$data[$i]['体验店编号']]))
                {
                    $rAr[$data[$i]['体验店编号']]=$data[$i];
                }
            }
            $NewData=array_values($rAr);
            //去掉重复的体验锁编号数据
            for($i=0;$i<count($NewData);$i++)
            {
                if(!isset($rArs[$NewData[$i]['体验锁编号']]))
                {
                    $rArs[$NewData[$i]['体验锁编号']]=$NewData[$i];
                }
            }
            $NewData=array_values($rArs);
            foreach ($NewData as $k => $v) {
                if (preg_match("/\d{12}$/", $v['体验锁编号'])) {
                    $list= DB::table('container')->select('lock_code')->where(array('is_del' => 1, 'status' => 1, 'lock_code' => $v['体验锁编号']))->first();
                    $number= DB::table('ceshi')->select('number')->where(array('number' => $v['体验锁编号']))->first();
                    if(empty($number)){
                        $ceshi=array(
                            'number'=>$v['体验锁编号'],
                            'time'=>time(),
                        );
                        DB::table('ceshi')->insert($ceshi);
                    }
                    if (!empty($list->lock_code)) {
                        unset($k);
                    }
                }
            }
            foreach ($NewData as $k => $v) {
                $list= DB::table('container')->select('container_number')->where(array('is_del' => 1, 'status' => 1, 'container_number' => $v['体验店编号']))->first();
                if (!empty($list->container_number)) {
                    unset($k);
                }else{
                    $url= 'https://www.mmestore.com/'. $v['体验店编号'];
                    $img = $this->scerweima($url, $v['体验店编号']);
                    $arr = [
                        'container_number' => $v['体验店编号'],
                        'lock_code' => $v['体验锁编号'],
                        'create_time' => time(),
                        'status' => 1,
                        'is_del' => 1,
                        'img'=>$img
                    ];
                    $result = DB::table('container')->insert($arr);
                    if($result){
                        $log=1;
                    }
                }

            }
            if($log=1){
                echo redirect( 'admin/container/index');
            }

        });
    }

    // 1. 生成原始的二维码(生成图片文件)
    public function scerweima($url,$name)
    {
        $value = $url;                    //二维码内容
        $errorCorrectionLevel = 'L';    //容错级别
        $matrixPointSize = 16;            //生成图片大小
        //生成二维码图片
        $filename = '../public/qrcode/' . time().$name . '.png';
        QRcode::png($value, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
        $res = substr($filename, 10);
        return $res;

    }
}