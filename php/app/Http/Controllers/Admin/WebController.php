<?php

// 后台首页类

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;


class WebController extends Controller
{
    // 后台首页
    public function index(){
       echo 2;die;
    }

    public function action( Request $request ){
        echo 5;die;
    }
    

}