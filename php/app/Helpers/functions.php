<?php
function tests(){
	return 1;
}
// function jummps($msg,$url,$time){
function set_password($pw, $authCode = '')
{
    if (empty($authCode)) {
        $authCode = 'ZCate8yvj5o7Lk929cs';
    }
    $result = "###" . md5(md5($authCode . $pw));
    return $result;
}
//页面错误跳转
function error($msg,$url,$time=3){
  return view('admin.public.jump')->with([
        'message'=>$msg,
        'url' =>$url,//控制器路径
        'jumpTime'=>$time,//执行时间
        ]);
}
//页面成功跳转
function success($msg,$url,$time=3){
  return view('admin.public.jump')->with([
        'message'=>$msg,
        'url' =>$url,//控制器路径
        'jumpTime'=>$time,//执行时间
        ]);
}
/**
 * 密码比较方法,所有涉及密码比较的地方都用这个方法
 * @param string $password 要比较的密码
 * @param string $passwordInDb 数据库保存的已经加密过的密码
 * @return boolean 密码相同，返回true
 */
function sky_compare_password($password, $passwordInDb)
{
    if (strpos($passwordInDb, "###") === 0) {
        return sky_password($password) == $passwordInDb;
    } else{
        return false;
    }
}

/**
 * CMF密码加密方法
 * @param string $pw 要加密的原始密码
 * @param string $authCode 加密字符串
 * @return string
 */
function sky_password($pw, $authCode = '')
{
    if (empty($authCode)) {
        $authCode = 'ZCate8yvj5o7Lk929cs';
    }
    $result = "###" . md5(md5($authCode . $pw));
    return $result;
}
function ppp($datas){
	print('<pre>');
	print_r($datas);die;
}
function sess(){
    var_dump(session('ADMIN_ID'));
}
