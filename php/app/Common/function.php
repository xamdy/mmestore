<?php


//打印函数
if (!function_exists('p')) {
    // 传递数据以易于阅读的样式格式化后输出
    function p($data, $toArray = true)
    {
        // 定义样式
        $str = '<pre>';
        // 如果是 boolean 或者 null 直接显示文字；否则 print
        if (is_bool($data)) {
            $show_data = $data ? 'true' : 'false';
        } elseif (is_null($data)) {
            // 如果是null 直接显示null
            $show_data = 'null';
        } elseif (is_object($data) && in_array(get_parent_class($data), ['Illuminate\Support\Collection', 'App\Models\Base']) && $toArray) {
            // 把一些集合转成数组形式来查看
            $data_array = $data->toArray();
            $show_data = '这是被转成数组的Collection:<br>' . print_r($data_array, true);
        } elseif (is_object($data) && in_array(get_class($data), ['Maatwebsite\Excel\Readers\LaravelExcelReader']) && $toArray) {
            // 把一些集合转成数组形式来查看
            $data_array = $data->toArray();
            $show_data = '这是被转成数组的Collection:<br>' . print_r($data_array, true);
        } elseif (is_object($data) && in_array(get_class($data), ['Illuminate\Database\Eloquent\Builder'])) {
            // 直接调用dd 查看
            dd($data);
        } else {
            $show_data = print_r($data, true);
        }
        $str .= $show_data;
        $str .= '</pre>';
        echo $str;
    }
}

/**
 * 验证密码函数
 *
 */
function password($pass,$code,$oldpass=0){
    $str = md5(md5($pass).$code);
    if($oldpass){   //进行验证不存在返回字符串
        if($oldpass==$str){
            return true;
        }else{
            return false;
        }
    }else{
        return $str;
    }
}

/**
 * @param string $path
 * @param $fname 上传文件
 * file 上传文件  pathinfo  存放路径
 */
function upload( $file,$pathinfo ) {
    if($file -> isValid()) {
        $clientName = $file -> getClientOriginalName();  //上传文件名称
        $extension = $file -> getClientOriginalExtension(); //上传文件的后缀.
        $newName = md5(date('ymdhis').$clientName).".".$extension;  //生成图片文件名
        $path = $file -> move(public_path().$pathinfo,$newName);  // 上传到指定的目录下
        $path_info = $pathinfo.'/'.$newName;
        $url = $path_info;
        return $url;
    }
}

/**
 *  上传图片的类
 * @param $filename
 * @param $filepath
 * @param \Illuminate\Http\Request $request
 * @return int|string
 */
function uploadpic( $filename, $filepath,\Illuminate\Http\Request $request) {
    // 1.首先检查文件是否存在
    if ($request::hasFile($filename)) {
        // 2.获取文件
        $file = $request::file($filename);
        // 3.其次检查图片手否合法
        if ($file->isValid()) {
            // 先得到文件后缀,然后将后缀转换成小写,然后看是否在否和图片的数组内
            if (in_array(strtolower($file->extension()), ['jpeg', 'jpg', 'gif', 'gpeg', 'png'])) {
                // 4.将文件取一个新的名字
                $newName = 'img' . time() . rand(100000, 999999) . $file->getClientOriginalName();
                // 5.移动文件,并修改名字
                if ($file->move($filepath, $newName)) {
                    return $filepath . '/' . $newName;  //返回一个地址
                } else {
                    return 4;
                }
            } else {
                return 3;
            }
        } else {
            return 2;
        }
    }else {
        return 1;
    }
}


/**
 *  base64图片存取
 * @param $imgs
 * @return array
 */
function imgbase($imgs){
    $base64 =$imgs;
    $strs = explode('base64,',$base64);
    if($strs[0]=="data:image/jpeg;"){
        $strimgtype = 'jpeg';
    }else{
        $strimgtype = 'png';
    }
    $filename="goods".rand(100,999).time().'.'.$strimgtype;///要生成的图片名字
    // $jpg = base64_decode($base64);
    $img = str_replace('data:image/'.$strimgtype.';base64,', '', $base64);
    $img = str_replace(' ', '+', $img);
    $jpg = base64_decode($img);
    if (empty($jpg)){
        $data = array('code'=>'999','msg'=>'请上传图片');
        return $data;
    }
    if (!is_dir('./uploads/goods/'.date('Y-m-d'))){
        mkdir('./uploads/goods/'.date('Y-m-d'),0777,true);
    }
    $file = fopen("./uploads/goods/".date('Y-m-d').'/'.$filename,"w");//打开文件准备写入
    fwrite($file,$jpg);//写入
    fclose($file);//关闭
    $filePath = "/uploads/goods/".date('Y-m-d').'/'.$filename;
    //图片是否存在
    if (!file_exists('.'.$filePath)){
        $data = array('code'=>'999','msg'=>'上传失败');
        return $data;
    }else{
        $data = array('code'=>'1000','msg'=>$filePath);
        return $data;
    }
}




/**
 *  把对象转化为数组
 * @param $array
 * @return array
 */
function object_array($array) {
    if(is_object($array)) {
        $array = (array)$array;
    } if(is_array($array)) {
        foreach($array as $key=>$value) {
            $array[$key] = object_array($value);
        }
    }
    return $array;
}

/**
 *  // 共用返回方法
 * @param $code             // code为返回状态
 * @param $msg              // msg 为返回提示
 * @param $data             // data 为返回数据
 * @return string
 */
function publicReturn($code,$msg,$data = array()) {
    $return['code'] = $code;
    $return['msg'] = $msg;
    $return['data'] = $data;
    return json_encode($return);
}

/**
 *   打印sql语句
 */
function getlastSql(){
    DB::connection()->enableQueryLog();
    $sql=DB::getQueryLog();

    DB::enableQueryLog();
    echo  response()->json(DB::getQueryLog());die;
}

/**
 * 获取订单编号
 * @return string
 */
function orderNum() {
    return 'MM'.date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}





