@extends('admin/public/header')
</head>
<style>
    .unique {
        width: 800px;
        height: auto;
        overflow: hidden;
        zoom: 1;
        margin-left: 283px;
    }
    .unique_input {
        float: left;
        width: 40% !important;
    }
    .unique_input:nth-of-type(1) {
        margin-right: 1%;
    }
</style>
<body>
<div class="wrap">
    <ul class="nav nav-tabs">
        <li><a href="{{ url('admin/sowingmap/Imglist') }}">轮播图列表</a></li>
        <li class="active"><a href="javascript:;">轮播图详情</a></li>
    </ul>
    <form method="post" class="form-horizontal js-ajax-form margin-top-20" id="form" action="#">

        <div class="form-group">
            <label for="input-user_login" class="col-sm-2 control-label">图片名称</label>
            <div class="col-md-2 col-sm-10">
                {{$data->img_name}}
            </div>
        </div>

        <div class="form-group">
            <label  class="col-sm-2 control-label">图片展示</label>
            <div class="col-md-2 col-sm-10">
                <img width="80px" height="80px" src="{{ URL::asset($data->img_url) }}">
            </div>
        </div>

        <div class="form-group">
            <label  class="col-sm-2 control-label">图片状态</label>
            <div class="col-md-2 col-sm-10">
               使用
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <a class="btn btn-default" href="#"  onclick="back();">返回</a>
            </div>
        </div>

    </form>
</div>
</body>
</html>



