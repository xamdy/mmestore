@extends('admin/public/header')
</head>
<body>
    <div class="wrap">
        <ul class="nav nav-tabs">
            <li><a href="{{ url('admin/room/index') }}">房间列表</a></li>
            <li class="active"><a href="javascript:;">房间详情</a></li>
        </ul>
        <form method="post" class="form-horizontal js-ajax-form margin-top-20" action="#" >
            <div class="form-group">
                <label for="input-user_login" class="col-sm-2 control-label">房间类型：</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="form-control"  name="room_name"  value="{{ $result->room_name }}">
                </div>
            </div>
            <div class="form-group">
                <label for="input-user_login" class="col-sm-2 control-label">房间编号：</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="form-control"  name="room_number" value="{{ $result->room_number }}">
                </div>
            </div>
            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label">酒店名称：</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="form-control" id="input-user_email"   value="{{ $result->name }}">
                </div>
            </div>
            <div class="form-group">
                <label for="input-user_email" class="col-sm-2 control-label">体验店编号</label>
                <div class="col-md-6 col-sm-10">
                    <input type="text" class="form-control" id="input-user_email"  value="{{ $result->container_number }}">
                </div>
            </div>
            <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="button" class="btn btn-primary js-ajax-submit" onclick="history.go(-1);">返回</button>
            </div>
        </div>
        </form>
    </div>
</body>
</html>



