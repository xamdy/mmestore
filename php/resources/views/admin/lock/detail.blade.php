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
        <li><a href="{{ url('admin/lock/list') }}">数据列表</a></li>
        <li class="active"><a href="javascript:;">数据详情</a></li>
    </ul>
    <form method="post" class="form-horizontal js-ajax-form margin-top-20" id="form" action="#">

        <div class="form-group">
            <label for="input-user_login" class="col-sm-2 control-label">开锁人姓名</label>
            <div class="col-md-2 col-sm-10">
                {{json_decode($data->name)}}
            </div>
        </div>

        <div class="form-group">
            <label  class="col-sm-2 control-label">开锁人手机号</label>
            <div class="col-md-2 col-sm-10">
               {{ $data->tel }}
            </div>
        </div>

        <div class="form-group">
            <label  class="col-sm-2 control-label">体验店编号</label>
            <div class="col-md-2 col-sm-10">
                {{$data->container_number}}
            </div>
        </div>
        <div class="form-group">
            <label  class="col-sm-2 control-label">酒店名称</label>
            <div class="col-md-2 col-sm-10">
                {{$data->hotel_name}}
            </div>
        </div>

        <div class="form-group">
            <label  class="col-sm-2 control-label">房间类型</label>
            <div class="col-md-2 col-sm-10">
                {{$data->room_name}}
            </div>
        </div>

        <div class="form-group">
            <label  class="col-sm-2 control-label">房间编号</label>
            <div class="col-md-2 col-sm-10">
                {{$data->room_number}}
            </div>
        </div>

        <div class="form-group">
            <label  class="col-sm-2 control-label">开锁状态</label>
            <div class="col-md-2 col-sm-10">
                    @if($data->status == 0)
                        开锁成功
                    @else
                        开锁失败
                    @endif
            </div>
        </div>


        <div class="form-group">
            <label  class="col-sm-2 control-label">开锁时间</label>
            <div class="col-md-2 col-sm-10">
                {{date("Y-m-d H:i:s",$data->create_time)}}
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <a class="btn btn-default" href="{{ url('admin/lock/list') }}">返回</a>
            </div>
        </div>

    </form>
</div>
</body>
</html>



