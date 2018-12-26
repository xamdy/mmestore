@extends('admin/public/header')
</head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .form-control{
        width: 70% !important;
    }
    #img_son{
        width: 50px;
        float: left;
        margin-right:40px;
        margin-bottom: 8px;
    }
    #img_parent{
        width: 500px;
    }

</style>
<body>
<div class="wrap">
    <ul class="nav nav-tabs">
        <li><a href="{{ url('admin/sowingmap/Imglist') }}">轮播图列表</a></li>
        <li class="active"><a href="{{ url('admin/sowingmap/ImgAdd') }}">轮播图添加</a></li>
    </ul>
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="post" class="form-horizontal js-ajax-form margin-top-20" action="{{ url('admin/sowingmap/ImgAdd') }}" enctype="multipart/form-data" id="fm">
        {{csrf_field()}}
    <div class="form-horizontal js-ajax-form margin-top-20">

        <div class="form-group">
            <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required">*</span>轮播图名称：</label>
            <div class="col-md-6 col-sm-10 form-inline">
                <input type="text" class="form-control"  name="img_name"   placeholder="请输入图片名称" required>
            </div>
        </div>
        <div class="form-group">
            <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required">*</span>上传图片：</label>
            <div class="col-md-6 col-sm-10 form-inline">
                <input type="file" class="form-control"  name="img_url" style="border: 0px;outline:none;cursor: pointer;" required>
            </div>
        </div>
        <div class="form-group">
            <label for="input-user_login"  class="col-sm-2 control-label"><span class="form-required">*</span>关联商品：</label>
            <div class="col-md-6 col-sm-10 form-inline">
                <div id="img_parent">
                @foreach($goods as $key=>$value)
                    <div id="img_son">
                    <input type="radio" value="{{$value->goods_id}}" name="goods_id" required>
                     <img width="50px" height="50px" src="{{ URL::asset($value->main_img) }}">
                    </div>
                @endforeach
                </div>
                {{--<input type="file" class="form-control"  id="img_url" name="img_url"  style="border: 0px;outline:none;cursor: pointer;" required>--}}
            </div>
        </div>

        <div class="form-group">
            <label for="input-user_login" class="col-sm-2 control-label"><span class="form-required">*</span>图片状态：</label>
            <div class="col-md-6 col-sm-10 form-inline">
                <input type="radio"  name="img_status"   style="margin-right:10px;" value="1" required ><sapn style="margin-right:50px;">是</sapn>
                <input type="radio"   name="img_status"  style="margin-right:10px;" value="0" required>否
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary js-ajax-submit" id="add">添加</button>
                <button type="button" class="btn btn-primary js-ajax-submit" onclick="history.go(-1);">返回</button>
            </div>
        </div>
    </div>

    </form>
</div>
</body>
</html>
<script src="{{ asset('static/themes/admin_simpleboot3/public/assets/js/jquery-1.10.2.min.js')}}"></script>
<script src="{{ asset('static/themes/admin_simpleboot3/public/assets/js/jquery.validate.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('/lib/layer/2.4/layer.js') }}"></script>



