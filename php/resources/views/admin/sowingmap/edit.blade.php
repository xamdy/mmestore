@extends('admin/public/header')
</head>
<link rel="stylesheet" href="{{asset('/static/static/goods/css/normalize.css')}}">
<link rel="stylesheet" href="{{asset('/static/static/goods/css/main.min.css')}}">

<link rel="stylesheet" href="{{asset('/static/static/goods/css/iconfont.css')}}">
<link rel="stylesheet" href="{{asset('/static/static/goods/css/star-rating.css')}}">
<link rel="stylesheet" href="{{asset('/static/static/goods/css/timepicki.css')}}">

<link rel="stylesheet" href="{{asset('/static/static/goods/css/addgoods.css')}}">


<style>
    .addKtv-cont .addGoods, .addKtv-cont .mart8{
        margin-top: 0 !important;
    }
    .uploader-file p {
        position: absolute;
       width: 120px;
        height: 25px;
        margin-top: 300px;
    }
    .flex_wrap button {
        float: left;
        margin-left: 30px !important;
    }
    #img_parent{
        width: 520px;
    }

    #img_son{
        float: left;
        margin-right: 12px;
    }
    .form-horizontal{
        background-color: #ffffff;
        /*margin-left: 20px;*/
    }
    .flex_wrap {
        height: 35px;
        width: auto;
        display: table;
        margin-left: 100px;
        margin-top: 50px;
    }
    .flex_wrap button {
        float: left;
        margin-left: 30px !important;
    }
</style>

<!-- 1.添加meta csrf_token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="wrap">
    <ul class="nav nav-tabs">
        <li><a href="{{ url('admin/sowingmap/Imglist') }}">轮播图列表</a></li>
        <li class="active"><a href="javascript:void(0)">轮播图编辑</a></li>
    </ul>
<form  method="post" class="form-horizontal js-ajax-form margin-top-20" action="{{ url('admin/sowingmap/up') }}" enctype="multipart/form-data">
    <div class="form-horizontal js-ajax-form margin-top-20">
    {{csrf_field()}}

        <div class="form-group">
            <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required">*</span>轮播图名称：</label>
            <div class="col-md-6 col-sm-10 form-inline">
                <input type="hidden" name="id" value="{{$data->id}}">
                <input type="text" class="form-control" id="img_name" name="img_name" style="width: 400px" value="{{$data->img_name}}">
            </div>
        </div>

        <div class="form-group" style="margin-top: 20px;">
            <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required">*</span>轮播图图片：</label>
            <div class="pull-left" style="margin-left: 20px;">
                <input type="file" id="fileselectm" class="form-control" name="img_url"/>
                <input type="hidden" name="old_img_url" class="imgm" value="{{$data->img_url}}">
                <div class="uploader-file" id="uploader-file">
                    <div class="img" >
                        <img src="{{ URL::asset($data->img_url) }}" name = "" id="selsectm" style="height: 105px">
                        <p style="margin-top:100px">点击上传</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>关联商品：</label>
            <div class="houseList pull-left clearfix">
                <div id="img_parent">
                @foreach($goods as $key=>$value)
                    <div id="img_son">
                        <input type="radio"  id="goods_id" name="goods_id" value="{{$value->goods_id}}" @if($value->goods_id==$data->goods_id) checked @endif>
                        <img width="80px" height="80px" src="{{ URL::asset($value->main_img) }}">
                    </div>
                @endforeach
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>轮播图状态：</label>
            <div class="houseList pull-left clearfix">
                @if($data->img_status == 1)
                    <input type="radio"  id="img_status" name="img_status"   style="margin-right:10px;" value="1" checked ><sapn style="margin-right:50px;">是</sapn>
                    <input type="radio"   name="img_status"  style="margin-right:10px;" value="0" >否
                @else
                    <input type="radio"  name="img_status"   style="margin-right:10px;" value="1"  ><sapn style="margin-right:50px;">是</sapn>
                    <input type="radio"   name="img_status"  style="margin-right:10px;" value="0" checked >否
                @endif
            </div>
        </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary js-ajax-submit" id="add">修改</button>
            <button type="button" class="btn btn-primary js-ajax-submit" onclick="history.go(-1);">返回</button>
        </div>
    </div>
    </div>
</form>
</div>





<script src="{{ asset('/static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>
<script src="{{ asset('/static/static/js/admin.js')}}"></script>
<script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>

<script type="text/javascript">

</script>
{{-- <script src="{{ asset('/static/static/goods/js/goodsimg.js')}}"></script> --}}
<script src="{{ asset('/static/ueditor/1.4.3/ueditor.config.js')}}"></script>
<script src="{{ asset('/static/ueditor/1.4.3/ueditor.all.min.js')}}"></script>
<script src="{{ asset('/static/ueditor/1.4.3/lang/zh-cn/zh-cn.js')}}"></script>
<script>
    $(function(){
        $('#selsectm').click(function(event) {
            $('#fileselectm').click();
        });
//选择文件
        $('#fileselectm').change(function(event) {
            event.preventDefault();
            var n=event.target.files.length;
            var file;
            for (var i = 0; i < n; i++) {
                file=event.target.files[i];
                html5upm(file);
            };
        });
    })
</script>
