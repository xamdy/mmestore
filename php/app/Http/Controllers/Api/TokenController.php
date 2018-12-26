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

class TokenController extends Controller   {
    public function message(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
//        $signature='4319833cfe224f32e72ec8f4d07428750d7c1023';
//        $timestamp='1543373485';
//        $nonce='871697732';

        $token = 'mengmammstore';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

}